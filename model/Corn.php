<?php
require_once('_database.php');
require_once('_const.php');
require_once('Stats.php');

class Corn {
    var $conn;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }
 
    public function updateUserToNextLevelIfAny() {
        try {
            $this->conn->beginTransaction();
            // $query = "UPDATE stats s INNER JOIN user u ON s.userId = u.userId SET s.level = s.level + 1 WHERE u.status != 0 AND s.level = :level AND s.noOfDownlines > :neededDownlines AND s.activeSponserCount >= :neededActiveSponserCount";
            $query = "UPDATE stats s INNER JOIN user u ON s.userId = u.userId SET s.level = s.level + 1 WHERE u.status != 0 AND s.level = :level AND s.noOfDownlines > :neededDownlines AND s.activeSponserCount >= :neededActiveSponserCount AND (s.topupNeededLevel = 0 OR s.topupNeededLevel > s.level)";

            $neededActiveSponserCount = 0;
            $level = 1;
            $count = 0;
            foreach (LEVEL_STATS2 as $ls) {
                $neededDownlines = $ls[0];
                $neededActiveSponserCount += $ls[1];
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":level", $level);
                $stmt->bindParam(":neededDownlines", $neededDownlines);
                $stmt->bindParam(":neededActiveSponserCount", $neededActiveSponserCount);

                $stmt->execute();
                if($stmt->rowCount()) {
                    $count++;
                }

                $level++;
            }
            $res = ["status"=>1, "status_message"=>"Users checked successfully, Total: $count"];
            if($count > 0)
                $cronResult = $this->createCronLog("updateUserToNextLevelIfAny", "Users checked successfully, Total: $count");
            $this->conn->commit();
        } catch (Exception $e) {
            $res = ["status"=>0, "status_message"=>"updateUserToNextLevelIfAny: ".$e->getMessage()];
            $this->conn->rollBack();
        }
        return $res;
    }
    
    public function createCronLog($logType, $data) {
        try {
            $query = "INSERT INTO cron_log (cron_type, logData) VALUES (:logType, :data)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":logType", $logType);
            $stmt->bindParam(":data", $data);

            $stmt->execute();

            $res = ["status"=>1, "status_message"=>"Log created"];
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem creating log"];
        }
        return $res;
    }
}
?>

