<?php 
class Database{
	var $env = "developement";
    var $servername = "localhost";
    // Developement
    // var $username = "root"; // kitabosi_hockey --> Prod
    // var $password = ""; // Hockey@123 --> Prod
    // var $dbname="hockey"; // kitabosi_hockey --> Prod
    
    // Production
    var $username = "kitabosi_hockey"; // kitabosi_hockey --> Prod
    var $password = "Hockey@123"; // Hockey@123 --> Prod
    var $dbname="kitabosi_hockey"; // kitabosi_hockey --> Prod
    public function getConnection() {
        try{
           $conn = new PDO("mysql:host=$this->servername;", $this->username, $this->password);
            $createDb = "CREATE DATABASE IF NOT EXISTS $this->dbname";
            $stmt = $conn->prepare($createDb);
            $stmt->execute(); 

            $use = "use $this->dbname";
            $stmt = $conn->prepare($use);
            $stmt->execute();

            $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);

            $adminTable = "CREATE TABLE IF NOT EXISTS admin(id int PRIMARY KEY AUTO_INCREMENT, adminId VARCHAR(250) UNIQUE, password VARCHAR(250) NOT NULL, firstName VARCHAR(250), lastName VARCHAR(250), canWithdraw int DEFAULT 1, amount decimal(20,2) DEFAULT 0.0, activeDenominations VARCHAR(250) DEFAULT '500,1000,3000,5000,10000', createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
            $stmt = $conn->prepare($adminTable);
            $stmt->execute();

            $userTable = "CREATE TABLE IF NOT EXISTS user(id int AUTO_INCREMENT PRIMARY KEY, userId VARCHAR(250) UNIQUE NOT NULL, password VARCHAR(250) NOT NULL, clientCode VARCHAR(250), status int NOT NULL DEFAULT 0, parentId VARCHAR(250), firstName VARCHAR(250) NOT NULL, lastName VARCHAR(250) NOT NULL, mobile VARCHAR(250) NOT NULL, email VARCHAR(250), pan VARCHAR(250), bankName VARCHAR(250), branch VARCHAR(250), accountNumber VARCHAR(250), ifsc VARCHAR(250), verificationStatus VARCHAR(250) DEFAULT 'PENDING', statusUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(parentId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=INNODB;";
            $stmt = $conn->prepare($userTable);
            $stmt->execute();

            $epinTable = "CREATE TABLE IF NOT EXISTS epin (id int PRIMARY KEY AUTO_INCREMENT, epinId VARCHAR(250) NOT NULL UNIQUE, ownerId VARCHAR(250) NOT NULL, cost decimal(5,2) DEFAULT 98.00, status int DEFAULT 0, createdBy VARCHAR(250) DEFAULT 'USER',
             createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(ownerId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE, UNIQUE(epinId, ownerId)) ENGINE=INNODB;";
            $stmt = $conn->prepare($epinTable);
            $stmt->execute();            

            $epinReportTable = "CREATE TABLE IF NOT EXISTS epinReport (id int PRIMARY KEY AUTO_INCREMENT, epinId VARCHAR(250) NOT NULL, fromId VARCHAR(250) NOT NULL, toId VARCHAR(250) NOT NULL, status int DEFAULT 0, createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(fromId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE, FOREIGN KEY(toId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE, UNIQUE(epinId, fromId, toId, createdAt)) ENGINE=INNODB;";
            $stmt = $conn->prepare($epinReportTable);
            $stmt->execute();    

            $statsTable = "CREATE TABLE IF NOT EXISTS stats (id int PRIMARY KEY AUTO_INCREMENT, userId VARCHAR(250) UNIQUE NOT NULL, level int DEFAULT 0, 
            topupNeededLevel int DEFAULT 0,
            noOfDownlines int DEFAULT 0, 
            sponserNeededCount int DEFAULT 3, activeSponserCount int DEFAULT 0, 
            slBonus decimal(20, 2) DEFAULT 0.0,
            sponserBonus decimal(20,2) DEFAULT 0.0,
            totalBalance decimal(20,2) DEFAULT 0.0,
            closingBalance decimal(20,2) DEFAULT 0.0,
            totalWithdrawal decimal(20,2) DEFAULT 0.0,
             createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(userId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=INNODB";        
            $stmt = $conn->prepare($statsTable);
            $stmt->execute();

            $bonusIncomeTable = "CREATE TABLE IF NOT EXISTS bonusincome (id int PRIMARY KEY AUTO_INCREMENT, userId VARCHAR(250) NOT NULL, amountType VARCHAR(250) DEFAULT 'SLB', amount decimal(20,2) NOT NULL, bonusLevel int DEFAULT 0,createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(userId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=INNODB";        
            $stmt = $conn->prepare($bonusIncomeTable);
            $stmt->execute();

            $withdrawreportTable = "CREATE TABLE IF NOT EXISTS withdrawreport (id int PRIMARY KEY AUTO_INCREMENT, userId VARCHAR(250) NOT NULL, amount decimal(20,2) DEFAULT 0.0 NOT NULL, withdrawType VARCHAR(250) DEFAULT 'SL', createdAt timestamp DEFAULT CURRENT_TIMESTAMP, updatedAt timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(userId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE, UNIQUE(userId, createdAt)) ENGINE=INNODB;";        
            $stmt = $conn->prepare($withdrawreportTable);
            $stmt->execute();

            // $txnDetail = "CREATE TABLE IF NOT EXISTS txn_detail (id int PRIMARY KEY AUTO_INCREMENT, userId VARCHAR(250) NOT NULL, txnId VARCHAR(250) NOT NULL UNIQUE, txnType VARCHAR(250) DEFAULT 'PAY', amount decimal(20,2) DEFAULT 0.0 NOT NULL, status int, createdAt timestamp DEFAULT CURRENT_TIMESTAMP, updatedAt timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(userId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=INNODB;";        
            // $stmt = $conn->prepare($txnDetail);
            // $stmt->execute();

            $preTransaction = "CREATE TABLE IF NOT EXISTS pretransaction (id int PRIMARY KEY AUTO_INCREMENT, txnId VARCHAR(250)  NOT NULL UNIQUE, userId VARCHAR(250) NOT NULL, amount decimal(20,2) DEFAULT 0.0 NOT NULL, quantity int DEFAULT 0, status VARCHAR(250) DEFAULT 'PROCESS', product VARCHAR(250), locality VARCHAR(250), landmark VARCHAR(250), city VARCHAR(250), state VARCHAR(250), zipcode VARCHAR(250), country VARCHAR(250) DEFAULT 'India', courier VARCHAR(250), lrno VARCHAR(250), createdAt timestamp DEFAULT CURRENT_TIMESTAMP, updatedAt timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(userId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=INNODB;";        
            $stmt = $conn->prepare($preTransaction);
            $stmt->execute();

            $snapShot = "CREATE TABLE IF NOT EXISTS _snapshot (id int PRIMARY KEY AUTO_INCREMENT, urn VARCHAR(250) NOT NULL UNIQUE, userId VARCHAR(250) NOT NULL, amount decimal(5,2) DEFAULT 0.0, status VARCHAR(250) DEFAULT 'INITIATED', jsonResponse TEXT, createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(userId) REFERENCES user(userId) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=INNODB;";        
            $stmt = $conn->prepare($snapShot);
            $stmt->execute();

            $cronLog = "CREATE TABLE IF NOT EXISTS cron_log (id int PRIMARY KEY AUTO_INCREMENT, cron_type VARCHAR(250) NOT NULL, logData TEXT, createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=INNODB;";
            $stmt = $conn->prepare($cronLog);
            $stmt->execute();


            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $ex){
            echo "getConnection :".$ex->getMessage();
        }
        return $conn;
    }
}
 ?>


