<?php

define("SPONSER_BONUS", 0);
define("DAILY_WITHDRAW_LIMIT", 1);
define("ADMIN_ID", "HOCKEY98");
define("ALICE_BLUE_REFERENCE_LINK", "https://alicebluepartner.com/open-myaccount/?M=WMUM69");
// Alice Blue API
// https://app.aliceblueonline.com/services/partnerekyc.asmx/GetLeadDetails?Remname=WMUM69&Authcode=QVNMUEIzNTI0RA==&mobileNo=9785983736
// https://app.aliceblueonline.com/services/partnerekyc.asmx/GetLeadDetails?Remname=WMUM69&Authcode=QVNMUEIzNTI0RA==&mobileNo=9785983736

define("TOPUP_TYPES", [0,0,0,0,0,0,100, 300,500,1000,1200,2000,2500,3500,4500]);

// [No of downlines, No of directs, Per day amount, Amount till No of days, Level tag]
define("LEVEL_STATS", [
    [0, 0, 0, 0, 0, ""],
    [300, 3, 10, 20, 0, "Star"],
    [700, 3, 15, 20, 0, "Silver"],
    [1500, 3, 25, 30, 0, "Gold"],
    [4500, 3, 50, 30, 0, "Platinum"],
    [9000, 3, 100, 30, 0, "Ruby"],
    [15000, 3, 200, 30, 0, "Pearl"], 
    [18000, 3, 300, 30, 200, "Diamond"],
    [21000, 3, 500, 30, 300, "Sopphire"],
    [21000, 3, 1000, 30, 500, "Crown"],
    [21000, 3, 2000, 30, 1000, "Ambassador"],
    [21000, 3, 3000, 30, 1200, "Super"],
    [21000, 3, 4000, 30, 2000, "Super +"],
    [21000, 3, 5000, 30, 2500, "Gold +"],
    [21000, 3, 8000, 30, 3500, "Diamond +"],
    [21000, 3, 10000, 30, 3500, "Crown Diamond"]
]);

define("LEVEL_STATS2", [
    [300, 3, 10, 20, 0, "Star"], // 1 (If changing no of directs in first level, then change into stats default value also)
    [700, 3, 15, 20, 0, "Silver"],
    [1500, 3, 25, 30, 0, "Gold"],
    [4500, 3, 50, 30, 0, "Platinum"],
    [9000, 3, 100, 30, 0, "Ruby"],
    [15000, 3, 200, 30, 0, "Pearl"], 
    [18000, 3, 300, 30, 200, "Diamond"],
    [21000, 3, 500, 30, 300, "Sopphire"],
    [21000, 3, 1000, 30, 500, "Crown"],
    [21000, 3, 2000, 30, 1000, "Ambassador"],
    [21000, 3, 3000, 30, 1200, "Super"],
    [21000, 3, 4000, 30, 2000, "Super +"],
    [21000, 3, 5000, 30, 2500, "Gold +"],
    [21000, 3, 8000, 30, 3500, "Diamond +"],
    [21000, 3, 10000, 30, 3500, "Crown Diamond"] // 15
]);


define("PRODUCTS", [
    ["All in one Liquid", 149.0],
    ["Sanitary Pad", 199.0],
    ["Online E-Pin & Hockey Video", 60.0],    
]);

?>