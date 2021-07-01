<?php
// session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: /index");
  exit();
} 

class WithdrawHelper {
    private $key = "BD250E1D4F";
    private $salt = "6C0B39EA69";
    private $beneficiaryType;
    private $beneficiaryName;
    private $accountNumber;
    private $ifsc;
    private $paymentMode;
    private $uniqueRequestNumber;
    private $amount;

    function __construct()
    { 
    }
    // *SETTERS
    public function setBeneficiaryType($beneficiaryType) {
        $this->beneficiaryType = $beneficiaryType;
    }

    public function setBeneficiaryName($beneficiaryName) {
        $this->beneficiaryName = $beneficiaryName;
    }

    public function setAccountNumber($accountNumber) {
        $this->accountNumber = $accountNumber;
    }

    public function setIfsc($ifsc) {
        $this->ifsc = $ifsc;
    }

    public function setPaymentMode($paymentMode) {
        $this->paymentMode = $paymentMode;
    }

    public function setUniqueRequestNumber($uniqueRequestNumber) {
        $this->uniqueRequestNumber = $uniqueRequestNumber;
    }

    public function setAmount($amount) {
        $this->amount = round($amount, 2);
    }
    // *\ SETTERS

    public function generateHash() {
        $payload = "$this->key"."|"."$this->accountNumber"."|"."$this->ifsc"."||"."$this->uniqueRequestNumber"."|"."$this->amount"."|"."$this->salt";
        return hash("sha512", $payload);
    }

    // * Initiate Quick Transfer 
    public function initiateQuickTransfer() {
        $hash = $this->generateHash();

        $postData = [
            key => $this->key,
            beneficiary_type => $this->beneficiaryType,
            beneficiary_name => $this->beneficiaryName,
            account_number => $this->accountNumber,
            ifsc => $this->ifsc,
            unique_request_number => $this->uniqueRequestNumber,
            payment_mode => $this->paymentMode,
            amount => $this->amount
        ];

        $postData = json_encode($postData);
        $headers = [
            "Authorization: $hash",
            "Content-Type: application/json"
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wire.easebuzz.in/api/v1/quick_transfers/initiate/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        header("Content-Type: application/json");
        return json_decode($response);
    }
    
    // Retrieve
    function retrieve($urn) {
        
        $hash = hash('sha512', "$this->key|$urn|$this->salt");        
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://wire.easebuzz.in/api/v1/transfers/$urn/?key=$this->key",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            "Authorization: $hash"
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
    }
}


//!test



// $withdrawHelper = new WithdrawHelper();
// $withdrawHelper->setBeneficiaryType("bank_account");
// $withdrawHelper->setBeneficiaryName("Shubham");
// $withdrawHelper->setAccountNumber("20262298054");
// $withdrawHelper->setIfsc("SBIN0008283");
// $withdrawHelper->setPaymentMode("IMPS");
// $withdrawHelper->setUniqueRequestNumber("$urn");
// $withdrawHelper->setAmount(10.5);


// $withdrawHelper->initiateQuickTransfer();

// exit();

//!test
?>

