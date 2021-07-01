<?php
require_once('_database.php');

class StatsInfo {
    var $conn;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }

    public function initStat($userId, $level) {
        try {
            $query = "INSERT INTO stats(userId, level) VALUES (:userId, :level)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':level', $level);

            $result = $stmt->execute();
            if($result)
                $response=array("status"=>1,"status_message"=>"Success");
            else
                $response=array("status"=>0,"status_message"=>"Problem in init state");
        }catch(PDOException $e) {
            $response=array("status"=>0,"status_message"=>"Problem in init state");
        }
        return $response;
    }

    public function getStatsByUserId($userId) {
        try {
            $query = "SELECT * FROM stats WHERE userId = :userId;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':userId', $userId);

            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            $response = $res;
        }catch(PDOException $e) {
            echo "Error: ".$e->getMessage();
            $response = [];
        }catch(Exception $e) {
            $response = [];
        }
        return $response;
    }

    public function getStatsByUserId2($userId) {
        try {
            $query = "SELECT s.*, u.* FROM user u INNER JOIN stats s ON u.userId = s.userId WHERE s.userId = :userId;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':userId', $userId);

            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            $response = $res;
        }catch(PDOException $e) {
            echo "Error: ".$e->getMessage();
            $response = [];
        }catch(Exception $e) {
            $response = [];
        }
        return $response;
    }

    public function getAllUserStats() {
        try {
            $query = "SELECT * FROM stats";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $res;
        }catch(PDOException $e) {
            echo "Error: ".$e->getMessage();
            $response = [];
        }catch(Exception $e) {
            $response = [];
        }
        return $response;
    }    

    public function updateNoOfDownlines($userId) {
        try {
            $stats = $this->getStatsByUserId($userId);

            $query = "UPDATE user INNER JOIN stats ON user.userId = stats.userId SET noOfDownlines = noOfDownlines+1 WHERE stats.id < :id AND user.status > 0;";

            $stmt = $this->conn->prepare($query);

            $id = $stats['id'];

            $stmt->bindParam(":id", $id);

            $res = $stmt->execute();
            if($res && $stmt->rowCount()) {
                $response = 1;
            }else {
                $response = 0;
            }
        }catch(PDOException $e) {
            echo $e->getMessage();
            $response = 0;
        }catch(Exception $e) {
            $response = 0;
        }
        return $response;
    }

    public function processAfterActivatingID($userId) {
        try {
            $this->conn->beginTransaction();

            // INCREMENT PARENT'S TOTAL ACTIVE SPONSER
            $updateParentTotalSponser = "UPDATE stats SET activeSponserCount = activeSponserCount+1 WHERE userId = (SELECT parentId FROM user WHERE userId = :userId)";
            
            $stmt = $this->conn->prepare($updateParentTotalSponser);
            $stmt->bindParam(":userId", $userId);
            $stmt->execute();

            // PROVIDE SPONSER INCOME TO PARENT
            $this->provideSponserBonusToParent($userId);

            // IF PARENT NEEDED SPONSER THEN DECREMENT NEEDED
            $getParentStats = "SELECT * FROM stats WHERE userId = (SELECT parentId FROM user WHERE userId = :userId)";
            $stmt = $this->conn->prepare($getParentStats);
            $stmt->bindParam(":userId", $userId);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            $neededCount = $res['sponserNeededCount'];

            if($neededCount > 0) {
                $decrementNeeded = "UPDATE stats SET sponserNeededCount = sponserNeededCount-1 WHERE userId = (SELECT parentId FROM user WHERE userId = :userId)";
                $stmt = $this->conn->prepare($decrementNeeded);
                $stmt->bindParam(":userId", $userId);
                $stmt->execute();
            }

            // IF NEEDED BECOMES 0, THEN UPDATE NEEDED TO NEXT LEVEL NEEDED
            if($neededCount-1 <= 0) {
                $currentLevel = $res['level'];
                $nextLevelNeeded = $this->getNextLevelNeededCount($currentLevel);
                $updateNeeded = "UPDATE stats SET sponserNeededCount = :neededCount WHERE userId = (SELECT parentId FROM user WHERE userId = :userId)";
                $stmt = $this->conn->prepare($updateNeeded);
                $stmt->bindParam(":neededCount", $nextLevelNeeded);
                $stmt->bindParam(":userId", $userId);
                $stmt->execute();

                // IF REQUIRED DOWNLINES ARE FULFILLED THEN SEND USER TO NEXT LEVEL (ELLIGIBLE FOR THIS LEVEL INCOME)
                $noOfDownlines = $res['noOfDownlines'];
                $isSufficientDownlines = $this->checkDownlineScoreFulfillment($noOfDownlines, $currentLevel);
                if($isSufficientDownlines) {
                    $sendNextLevel = "UPDATE stats SET level = level + 1 WHERE userId = (SELECT parentId FROM user WHERE userId = :userId)";
                    $stmt = $this->conn->prepare($sendNextLevel);
                    $stmt->bindParam(":userId", $userId);
                    $stmt->execute();
                }
            }
            $this->conn->commit();
        }catch(PDOException $e) {
            echo "processAfterActivatingID: ".$e->getMessage();
        }catch(Exception $e) {
            $this->conn->rollBack();
        } 
    }

    // TODO: Handle edge cases.
    public function getNextLevelNeededCount($currentLevel) {
        return LEVEL_STATS[$currentLevel+1][1];
    }

    public function checkDownlineScoreFulfillment($noOfDownlines, $level) {
        return $noOfDownlines > LEVEL_STATS[$level][0];
    }

    public function checkBalance($balance) {
        try {
            if($balance != 500) {
                return false;
            }

            $query = "SELECT totalBalance-totalWithdrawal as balance FROM stats WHERE userId = :userId";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $_SESSION['userId']);
            $stmt->execute();

            
            $result = $stmt->fetch(FETCH_ASSOC);
            $remainingAmount = $result['balance'];

            if($balance > $remainingAmount) {
                return false;
            }
            return true;
        }catch(Exception $e) {
            return false;
        }
    }

    public function provideSponserBonusToParent($userId) {
        $response = 1;
        try {
            if( SPONSER_BONUS > 0 ) {
                $query = "UPDATE stats SET sponserBonus = sponserBonus + :sponserBonus, totalBalance = slBonus + sponserBonus WHERE userId = (SELECT parentId FROM user WHERE userId = :userId);";

                $stmt = $this->conn->prepare($query);

                $bonus = SPONSER_BONUS;

                $stmt->bindParam(":sponserBonus", $bonus);
                $stmt->bindParam(":userId", $userId);

                $stmt->execute();

                $bonusIncomeReport = "INSERT INTO bonusincome (userId, amountType, amount) VALUES ((SELECT parentId FROM user WHERE userId = :userId), 'SB', :amount);";

                $stmt = $this->conn->prepare($bonusIncomeReport);

                $stmt->bindParam(":userId", $userId);
                $stmt->bindParam(":amount", $bonus);

                $stmt->execute();

                $response = 1;
            }
        }catch(Exception $e) {
            $response = 0;
        }
        return $response;
    }

    public function getTodaysUsers() {
        try {
            $query = "SELECT level, count(*) as count FROM stats WHERE date(createdAt) = CURDATE() AND (level=0 OR level=1) GROUP BY level;";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $data = $stmt->fetchAll();
            $res = $data;
        }catch(Exception $e) {
            echo "getTodaysUsers:".$e->getMessage();
            $res = [];
        }
        return $res;
    }

    public function getAllLevelStats() {
        try {
            $query = "SELECT level, count(*) as count FROM stats WHERE date(createdAt) = CURDATE() AND (level=0 OR level=1) GROUP BY level;";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $res = $data;
        }catch(Exception $e) {
            echo "getTodaysUsers:".$e->getMessage();
            $res = [];
        }
        return $res;
    }

    public function getLevelIncomeCountsByUserId($userId) {
        try {
            $query = "SELECT bonusLevel, count(bonusLevel) as count FROM `bonusincome` WHERE userId=:userId AND amountType = 'SLB'  group by bonusLevel order by bonusLevel;";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $res = $data;
        }catch(Exception $e) {
            echo "getLevelIncomeCountsByUserId:".$e->getMessage();
            $res = [];
        }
        return $res;
    }
}
?>