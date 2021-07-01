<?php
require "./../model/Corn.php";

$corn = new Corn();
$res = $corn->updateUserToNextLevelIfAny();

if($res['status'] == 0) {
    $corn = new Corn();
    $res = $corn->createCronLog("_corn", $res['status_message']);    
}else {
    echo "Done<br/>";
    print_r($res);
}
?>