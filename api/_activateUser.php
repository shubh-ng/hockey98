<?php
require "./../model/Corn.php";
require "./../model/User.php";


$corn = new Corn();
$userInfo = new UserInfo();

$allNonActiveUsers = $userInfo->getAllNonActiveUsers();
// For each user, activate them if not activated.
foreach ($allNonActiveUsers as $nonActiveUser) {
    $userId = $nonActiveUser['userId'];
    $res = $userInfo->activateUserAfterDmatRegistration($userId);
    if($res['status'] == 1) {
        // Done.
    }else {
        $corn->createCronLog("_activateUser", $res['status_message']);
    }
}

?>