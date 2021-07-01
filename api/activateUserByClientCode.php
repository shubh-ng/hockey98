<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['userId']) && isset($_POST['action']) && $_POST['action'] === 'activateUserByClientCode') {
            require './../model/_const.php';
            require './../model/User.php';

            $clientCode = htmlspecialchars($_POST['clientCode']);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://app.aliceblueonline.com/services/partnerekyc.asmx/GetLeadDetails?Remname=WMUM69&Authcode=QVNMUEIzNTI0RA==&mobileNo=$clientCode",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = json_decode(curl_exec($curl));
            curl_close($curl);

            if( $response ) {
                if($response[0]->Result == "Success" && $response[0]->Stage) {
                    $user = new UserInfo();
                    $userId = $_SESSION['userId'];
                    $leadId = $response[0]->LeadID;
                    $res = $user->updateClientCode($userId, $leadId);
                    $res = $user->activateUserAfterDmatRegistration($userId);
                }else if($response[0]->Result == "No details found") {
                    $res = ["status"=> 0, "status_message"=>"It looks like you are not registered at Alice blue. Please register there first."];
                }else {
                    $res = ["status"=> 0, "status_message"=>"You are not active at alice blue, Please register there first."];
                }
            }else {
                $res = ["status"=> 0, "status_message"=>"We are facing some problem while connecting with Aliceblue. Please try again later."];
            }
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>