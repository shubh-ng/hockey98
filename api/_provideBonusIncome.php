<?php
require "./../model/BonusIncome.php";
require "./../model/Corn.php";

$bonusIncome = new BonusIncome();
$res = $bonusIncome->assignIncome();

if($res['status'] == 0) {
    $corn = new Corn();
    $res = $corn->createCronLog("_provideBonusIncome", $res['status_message']);
}else {
    echo "Done<br>";
    print_r($res);
}
?>