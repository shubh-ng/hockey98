<?php
define('SALT', "0ER3A0W7E1");
function httpPost($url, $data, $headers){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);

    if(!empty($headers)) 
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);    
    
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function httpGet($url, $headers) {
    $curl = curl_init($url);

    print_r($headers);

    if(!empty($headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);    
    }
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// $data = [
//     "key"=>"DPN6KIX55Z",
//     "name"=>"Shubham",
//     "email"=>"shubh.singh.it@gmail.com",
//     "phone"=>"9111883489"
// ];


$key = "DPN6KIX55Z";
$salt = SALT;
$name = "Rajesh";
$email = "rajesh@gmail.com";
$phone = "8545465825";
$hash = hash('sha512', "$key|$name|$email|$phone|$salt");
$response = httpPost("https://testdashboard.easebuzz.in/api/v1/contacts/", [
    "key"=> $key,
    "name"=> $name,
    "email"=> $email,
    "phone"=> $phone,
    "productinfo"=>"hockey",
    "amount"=>100.0,
    "firstname" => "Shubham",
    "surl"=>"http://localhost/hockey98/response",
    "furl"=>"http://localhost/hockey98/response",
    "txnid"=>"TXN5454",
    "hash"=>$hash
], [
    "Authorization: $hash"
] );

echo $response;


// echo httpGet("https://testdashboard.easebuzz.in/api/v1/contacts/", [
//     "Authorization: $hash"
// ]);

?>