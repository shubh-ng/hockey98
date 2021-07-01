<?php
require_once('_database.php');
require_once('_const.php');
require_once('Stats.php');

class BonusIncome {
    var $conn;
    var $dailyIncomeLimit = 1; // count of incomes per day per level

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }

    public function getIncomeCountByLevel($userId, $bonusLevel, $amountType) {
        try {
            $query = "SELECT count(*) as count FROM bonusincome WHERE bonusLevel = :bonusLevel AND userId = :userId";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":bonusLevel", $bonusLevel);

            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['count'];
        }catch(Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    function assignIncome() {
        try {

            // begin transaction
            $this->conn->beginTransaction();
            
            $statInfo = new StatsInfo();
            $users = $statInfo->getAllUserStats();
            $count = 0;
            $i=1;
            foreach (LEVEL_STATS2 as $stat) {
                $levelRequired = $i;
                $noOfIncomeShouldTake = $stat[3];
                $income = $stat[2];
        
                foreach ($users as $user) {
                    $activeLevel = $user['level'];
                    $noOfIncomeTaken = $this->getNoOfIncomeTaken($user['userId'], $levelRequired);
                    $noOfIncomeTakenToday = $this->getNoOfIncomeTakenToday($user['userId'], $levelRequired);
                   
                    if($activeLevel > $levelRequired && $noOfIncomeTaken < $noOfIncomeShouldTake && $noOfIncomeTakenToday < $this->dailyIncomeLimit) {
                        // Provide payment
                        $res = $this->provideIncome($user, $income, $levelRequired);
                        if(!$res) {
                            throw new Exception("Problem in providing income");
                        }else {
                            $count++;
                        }
                    }
                }
                $i++;
            }
            $this->conn->commit();
            $response = ["status"=>1, "status_message"=>"$count amounts credited."];
        }catch(Exception $e) {
            $this->conn->rollBack();
            $response = ["status"=>0, "status_message"=>$e->getMessage()];
        }
        return $response;
    }
    
    function getNoOfIncomeTaken($userId, $level) {
        try {
            $query = "SELECT count(*) as count FROM bonusincome WHERE userId = :userId AND bonusLevel = :level AND amountType='SLB';";
           
            $stmt = $this->conn->prepare($query);
           
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":level", $level);
           
            $stmt->execute();
           
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
           
            return $res['count'];
        }catch(Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    
    function getNoOfIncomeTakenToday($userId, $level) {
        try {
            $query = "SELECT count(*) as count FROM bonusincome WHERE userId = :userId AND bonusLevel = :level AND amountType='SLB' AND date(createdAt) = CURDATE();";
           
            $stmt = $this->conn->prepare($query);
           
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":level", $level);
           
            $stmt->execute();
           
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
           
            return $res['count'];
        }catch(Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }    
    
    function provideIncome($user, $income, $level) {
        try {
            $query = "INSERT INTO bonusincome (userId, amount, bonusLevel) VALUES (:userId, :income, :level);";
           
            $stmt = $this->conn->prepare($query);
           
            $stmt->bindParam(":userId", $user['userId']);
            $stmt->bindParam(":income", $income);
            $stmt->bindParam(":level", $level);
           
            $stmt->execute();

            $query2 = "UPDATE stats SET slBonus = slBonus + :amount, totalBalance = slBonus + sponserBonus WHERE id = :id";

            $stmt = $this->conn->prepare($query2);
           
            $stmt->bindParam(":amount", $income);
            $stmt->bindParam(":id", $user['id']);
           
            $stmt->execute();
            return 1;
        }catch(Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public function getBonusIncomeReports($userId, $type) {
        try {
            $query = "SELECT * FROM bonusincome WHERE userId = :userId AND amountType = :amountType";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":amountType", $type);
            
            $stmt->execute();

            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e) {
            echo $e->getMessage();
            return [];
        }
        return $res;
    }

}
?>

