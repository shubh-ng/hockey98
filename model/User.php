<?php
require_once('_database.php');
require_once('Stats.php');

class UserInfo {
    var $conn;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }

    public function addUser() {
        try {
            $query = "INSERT INTO user(userId, password, firstName, lastName, mobile, parentId, pan) VALUES (:userId, :password, :fname, :lname, :mobile, :parentId, :pan)";

            $stmt = $this->conn->prepare($query);

            $userId = "H".$this->getUserCount().rand(1, 10000);
            $fname = htmlspecialchars($_POST["fname"]);
            $lname = htmlspecialchars($_POST["lname"]);
            $mobile = htmlspecialchars($_POST["mobile"]);
            $panNumber = strtoupper(htmlspecialchars($_POST["panNumber"]));
            $parentId = "H09690";
            if(isset($_POST['parentId'])) {
                $parentId = htmlspecialchars($_POST["parentId"]);
            }
            $password = htmlspecialchars($_POST["password"]);
            // $createdAt = htmlspecialchars($_POST["createdAt"]);


            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':mobile', $mobile);
            $stmt->bindParam(':parentId', $parentId);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':pan', $panNumber);
            // $stmt->bindParam(':createdAt', $createdAt);

            $result = $stmt->execute();

            if($result) {
                $statsInfo = new StatsInfo();
                $result = $statsInfo->initStat($userId, 0);
                $response = array("status"=>1, "status_message"=>"User created", "data"=> array("userId"=>$userId));
            }else {
                $response=array("status"=>0,"status_message"=>"error in inserting");
            }
        }catch (PDOException $e) {
            if($e->getCode()==23000){
                $response=array("status"=>0,"status_message"=>"Invalid Sponser ID or Duplicate Entry");
            }else {
                $response=array("status"=>0,"status_message"=>"Internal Error ".$e->getMessage());    
            }
        }
        return $response;
    }

    public function addDirectUser() {
        try {
            $query = "INSERT INTO user(userId, password, firstName, lastName, mobile, parentId, status, pan) VALUES (:userId, :password, :fname, :lname, :mobile, :parentId, :status, :pan)";

            $stmt = $this->conn->prepare($query);

            $userId = "H".$this->getUserCount().rand(1, 10000);
            // $epin = htmlspecialchars($_POST["epin"]);
            $fname = htmlspecialchars($_POST["fname"]);
            $lname = htmlspecialchars($_POST["lname"]);
            $mobile = htmlspecialchars($_POST["mobile"]);
            $parentId = htmlspecialchars($_POST["parentId"]);
            $password = htmlspecialchars($_POST["password"]);
            $panNumber = strtoupper(htmlspecialchars($_POST["panNumber"]));
            $status = 0; // INACTIVE

            // $epinInfo = new EpinInfo();
            // if(!$epinInfo->verifyEpin($epin, $parentId)) {
            //     throw new Exception("Sorry! Selected E-Pin is used!");
            // }

            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':mobile', $mobile);
            $stmt->bindParam(':parentId', $parentId);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':pan', $panNumber);

            $result = $stmt->execute();
            if($result) {
                $statsInfo = new StatsInfo();
                $result = $statsInfo->initStat($userId, 0);
                $response = array("status"=>1, "status_message"=>"Direct User Created", "data"=> array("userId"=>$userId));
            }else {
                $response=array("status"=>0,"status_message"=>"Error in inserting");
            }
        }catch (PDOException $e) {
            if($e->getCode()==23000){
                $response=array("status"=>0,"status_message"=>"Invalid Sponser ID or Duplicate Entry");
            }else {
                $response=array("status"=>0,"status_message"=>"Internal Error ".$e->getMessage());    
            }
        }catch (Exception $e) {
            $response=array("status"=>0,"status_message"=>$e->getMessage());
        }
        return $response;
    }    

    public function updateUser() {
        try {
            $query = "UPDATE user SET firstName = :firstName, lastName = :lastName, mobile = :mobile, pan=:pan, bankName=:bankName, branch=:branch, accountNumber=:accountNumber, ifsc=:ifsc WHERE userId = :userId";

            $stmt = $this->conn->prepare($query);

            $userId = isset($_SESSION['userId'])?$_SESSION['userId']:htmlspecialchars($_POST['userId']);
            $fname = isset($_POST['firstName']) ? htmlspecialchars($_POST["firstName"]) : htmlspecialchars($_POST["fname"]);
            $lname = isset($_POST['lastName']) ? htmlspecialchars($_POST["lastName"]) : htmlspecialchars($_POST["lname"]);;
            $mobile = htmlspecialchars($_POST["mobile"]);
            $pan = htmlspecialchars($_POST["pan"]);
            $bankName = htmlspecialchars($_POST["bankName"]);
            $branch = htmlspecialchars($_POST["branch"]);
            $accountNumber = htmlspecialchars($_POST["accountNumber"]);
            $ifsc = htmlspecialchars($_POST["ifsc"]);

            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':firstName', $fname);
            $stmt->bindParam(':lastName', $lname);
            $stmt->bindParam(':mobile', $mobile);
            $stmt->bindParam(':pan', $pan);
            $stmt->bindParam(':bankName', $bankName);
            $stmt->bindParam(':branch', $branch);
            $stmt->bindParam(':accountNumber', $accountNumber);
            $stmt->bindParam(':ifsc', $ifsc);

            $result = $stmt->execute();

            if($stmt->rowCount()) {
                $response = array("status"=>1, "status_message"=>"User updated", "data"=> array("userId"=>$userId));
            }else {
                throw new Exception("No changes done");
            }
        }catch (Exception $e) {
            $response=array("status"=>0,"status_message"=>$e->getMessage());    
        }
        return $response;
    }

    function activateUser() {
        try {

            // $this->conn->beginTransaction();

            $query = "UPDATE user u INNER JOIN stats s ON u.userId = s.userId SET u.status=1, s.level=1 WHERE u.userId = :userId;";

            $stmt = $this->conn->prepare($query);

            $userId = htmlspecialchars($_POST['userId']);
            $epinId = htmlspecialchars($_POST['epinId']);
            $sessionId = $_SESSION['userId'];

            $epinInfo = new EpinInfo();
            if(!$epinInfo->verifyEpin($epinId, $sessionId)) {
                throw new Exception("Sorry! Selected E-Pin is already used!");
            }

            $stmt->bindParam(":userId", $userId);

            $res = $stmt->execute();
            if($res && $stmt->rowCount()) {
                $result = $epinInfo->transferEpin($epinId, $sessionId, $userId, 1);
                if($result)
                    $response = array("status"=>1, "status_message"=>"User ID: ".$userId." Activated Successfully", "data"=>array("userId"=>$userId));
                else
                    throw new Exception("Error Occured");
            }else {
                throw new Exception("ID Already Active OR Activate User ID not found");
            }
        }catch(PDOException $e) {
            echo "activateUser: ".$e->getMessage();
        }catch(Exception $e) {
            $response = array("status"=>0, "status_message"=>$e->getMessage());
        }
        // $this->conn->rollback();
        return $response;
    }

    function activateUserAfterDmatRegistration($userId) {
        $response = [];
        try {

            // $this->conn->beginTransaction();

            $query = "UPDATE user u INNER JOIN stats s ON u.userId = s.userId SET u.status=1, s.level=1 WHERE u.userId = :userId;";

            $stmt = $this->conn->prepare($query);
            $sessionId = $_SESSION['userId'];

            $stmt->bindParam(":userId", $userId);

            $res = $stmt->execute();
            if($res && $stmt->rowCount()) {
                $statsInfo = new StatsInfo();
                $result = $statsInfo->updateNoOfDownlines($userId);
                // if( $result > 0 ) {
                    $statsInfo->processAfterActivatingID($userId);
                    $response = array("status"=>1, "status_message"=>"User ID: ".$userId." Activated Successfully", "data"=>array("userId"=>$userId));
                // }else {
                //     throw new Exception("Problem in activating id:$userId");
                // }
            }else {
                throw new Exception("ID Already Active OR User ID not found:$userId");
            }
        }catch(PDOException $e) {
            echo "activateUser: ".$e->getMessage();
            $response = array("status"=>0, "status_message"=>$e->getMessage());
        }catch(Exception $e) {
            $response = array("status"=>0, "status_message"=>$e->getMessage());
        }
        // $this->conn->rollback();
        return $response;
    }

    public function getUserCount() {
        try {
            $query = "SELECT count(*) as count FROM user";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['count'];
        }catch(PDOException $e) {
            return 0;
        } 
    }

    public function getUserInfo($userId) {
        try {
            $query = "SELECT userId, clientCode, status, parentId, firstName, lastName, mobile, email, pan, bankName, branch, accountNumber, ifsc, createdAt, updatedAt FROM user WHERE userId = :userId;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            $response = $res;
        }catch(PDOException $e) {
            $response = [];
        }
        return $response;
    }

    public function getAllUsers() {
        try {
            $query = "SELECT userId, status, password, clientCode, parentId, firstName, lastName, mobile, email, pan, bankName, branch, accountNumber, ifsc, createdAt, updatedAt FROM user;";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $res;
        }catch(PDOException $e) {
            $response = ["status"=>0, "status_message"=>"Problem in getUsers: ".$e->getMessage()];
        }
        return $response;
    }

    public function getAllNonActiveUsers() {
        try {
            $query = "SELECT id, userId, status FROM user where status = 0;";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $res;
        }catch(PDOException $e) {
            $response = ["status"=>0, "status_message"=>"Problem in getUsers: ".$e->getMessage()];
        }
        return $response;
    }

    public function getDirectUsers($parentId) {
        try {
            $query = "SELECT userId, mobile, firstName, lastName, createdAt, updatedAt, status FROM user WHERE parentId = :parentId;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":parentId", $parentId);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $res;
        }catch(PDOException $e) {
            $response = array();
        }
        return $response;
    }

    public function getDirectUsersCount($parentId) {
        try {
            $query = "SELECT count(*) as count FROM user WHERE parentId = :parentId;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":parentId", $parentId);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $res;
        }catch(PDOException $e) {
            $response = [["count"=>0]];
        }
        return $response;
    }

    public function changePassword() {
        try {
            $query = "UPDATE user SET password = :newPassword WHERE userId = :userId AND password = :oldPassword;";

            $stmt = $this->conn->prepare($query);

            $userId = $_SESSION['userId'];
            $oldPassword = trim(htmlspecialchars($_POST['oldPassword']));
            $newPassword = trim(htmlspecialchars($_POST['newPassword']));

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":newPassword", $newPassword);
            $stmt->bindParam(":oldPassword", $oldPassword);

            $stmt->execute();

            if($stmt->rowCount()) {
                $response = ["status"=>1, "status_message"=>"Password Changed Successfully"];
            }else {
                throw new Exception("Please Enter Correct Old Password");
            }
        }catch(Exception $e) {
            $response = ["status"=>0, "status_message"=>$e->getMessage()];
        }
        return $response;
    }

    public function topupId($userId, $topupId) {
        try {
            $this->conn->beginTransaction();

            $epinInfo = new EpinInfo();
            $sessionId = $_SESSION['userId'];
            if(!$epinInfo->verifyEpin($topupId, $sessionId)) {
                throw new Exception("Sorry! Selected E-Pin is already used!");
            }

            if(!$this->checkIfTopupNecessary($userId, $topupId)) {
                throw new Exception("ID is already activated for given topup");
            }

            $topupUsed = "UPDATE epin SET status = 1 WHERE epinId = :epinId";

            $stmt = $this->conn->prepare($topupUsed);
            $stmt->bindParam(":epinId", $topupId);
            $stmt->execute();
            if(!$stmt->rowCount()) {
                throw new Exception("Sorry! Selected E-Pin is already used!");
            }

            $topupNeededNext = "UPDATE stats SET topupNeededLevel = topupNeededLevel+1 WHERE userId = :userId;";

            $stmt = $this->conn->prepare($topupNeededNext);
            $stmt->bindParam(":userId", $userId);
            $stmt->execute();
            if(!$stmt->rowCount()) {
                throw new Exception("Problem in Top Up. Please try after some time.");
            }
            $response = ["status"=>1, "status_message"=>"Top Up Done Successfully", "data"=>["userId"=>$userId]];

            $this->conn->commit();
        }catch(Exception $e) {
            $response = array("status"=>0, "status_message"=>$e->getMessage());
            $this->conn->rollBack();
        }
        return $response;
    }

    public function checkIfTopupNecessary($userId, $topupId) {
        try {
            $epinInfo = new EpinInfo();
            $epin = $epinInfo->getEpinById($topupId);

            $statsInfo = new StatsInfo();
            $stats = $statsInfo->getStatsByUserId($userId);
            $level = $stats['topupNeededLevel'];

            $levelNeeded = array_search($epin['cost'], TOPUP_TYPES)+1;


            if($levelNeeded == $level) {
                return 1;
            }
            return 0;
        }catch(Exception $e) {
            echo "checkIfTopupNecessary:".$e->getMessage();
            return 0;
        }
    }
    
    // delete inactive user for 120hours
    public function deleteInactiveUsers() {
        $corn = new Corn();
        try {
            $query = "DELETE FROM user WHERE status = 0 AND userId != 'HOCKEY98' AND createdAt < ADDDATE(NOW(), INTERVAL -120 HOUR)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->execute();
            if($stmt->rowCount()) {
                $count = $stmt->rowCount();
                $corn->createCronLog("_deleteInactiveUsers", "$count users deleted");
                
                $res = ["status"=>1, "status_message"=>"$count users deleted"];
            }else {
                throw new Exception("0");
            }
        }catch(Exception $e) {
            $msg = $e->getMessage();
            if($msg != 0) { // some other error
                $corn->createCronLog("_deleteInactiveUsers", "Error in deleting user: ".$msg);
                $res = ["status"=>0, "status_message"=>$msg];
            }else {
                $res = ["status"=>0, "status_message"=>"No users available"];
            }
        }
        return $res;
    }

    // delete user
    public function deleteUser($userId) {
        try {
            $query = "DELETE FROM user WHERE userId != 'HOCKEY98' AND userId=:userId;";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam("userId", $userId);
            $stmt->execute();
            
            $query = "DELETE FROM stats WHERE userId != 'HOCKEY98' AND userId=:userId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam("userId", $userId);
            $stmt->execute();

            $res = ["status"=>1, "status_message"=>"User deleted successfully: $userId"];
        }catch(Exception $e) {
            $msg = $e->getMessage();
            $res = ["status"=>0, "status_message"=>$msg];
        }
        return $res;
    }

    
    public function updateClientCode($userId, $clientCode) {
        try {
            $query = "UPDATE user SET clientCode=:clientCode WHERE userId = :userId";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':clientCode', $clientCode);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            // $stmt->debugDumpParams();

            if($stmt->rowCount()) {
                $response = array("status"=>1, "status_message"=>"Your activation request has been initiated.", "data"=> ["userId"=>$userId, "clientCode"=>$clientCode]);
            }else {
                throw new Exception("Client Code Already Provided.");
            }
        }catch (Exception $e) {
            $response=array("status"=>0,"status_message"=>$e->getMessage());    
        }
        return $response;
    }

}
?>