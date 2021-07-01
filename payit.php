<?php
session_start();

// include file
include_once __DIR__.'/bppg_helper.php';
require "./model/Transaction.php";
 
// Define environment variable
define('PG_REQUEST_URL', 'https://merchant.bhartipay.com/crm/jsp/paymentrequest');
define('PG_RESPONSE_URL', 'https://hockey98.com/response-json');
define('PG_RESPONSE_MODE', 'SALE');
define('PG_SALT', '92191625c7934b8c');
define('PG_PAY_ID', '4363510114112852');

$pg_transaction = new BPPGModule;
$pg_transaction->setPayId(PG_PAY_ID);
$pg_transaction->setPgRequestUrl(PG_REQUEST_URL);
$pg_transaction->setSalt(PG_SALT);
$pg_transaction->setReturnUrl(PG_RESPONSE_URL);
$pg_transaction->setCurrencyCode(356);
$pg_transaction->setTxnType('SALE');
$pg_transaction->setOrderId($_REQUEST['ORDER_ID']);
@$pg_transaction->setCustEmail($_REQUEST['CUST_EMAIL']);
@$pg_transaction->setCustName($_REQUEST['CUST_NAME']);
@$pg_transaction->setCustStreetAddress1($_REQUEST['CUST_STREET_ADDRESS1']);
@$pg_transaction->setCustCity($_REQUEST['CUST_CITY']);
@$pg_transaction->setCustState($_REQUEST['CUST_STATE']);
@$pg_transaction->setCustCountry($_REQUEST['CUST_COUNTRY']);
@$pg_transaction->setCustZip($_REQUEST['CUST_ZIP']);
@$pg_transaction->setCustPhone($_REQUEST['CUST_PHONE']);
@$pg_transaction->setAmount($_REQUEST['AMOUNT']*100); // convert to Rupee from Paisa
@$pg_transaction->setProductDesc($_REQUEST['PRODUCT_DESC']);
@$pg_transaction->setCustShipStreetAddress1($_REQUEST['CUST_SHIP_STREET_ADDRESS1']);
@$pg_transaction->setCustShipCity($_REQUEST['CUST_SHIP_CITY']);
@$pg_transaction->setCustShipState($_REQUEST['CUST_SHIP_STATE']);
@$pg_transaction->setCustShipCountry($_REQUEST['CUST_SHIP_COUNTRY']);
@$pg_transaction->setCustShipZip($_REQUEST['CUST_SHIP_ZIP']);
@$pg_transaction->setCustShipPhone($_REQUEST['CUST_SHIP_PHONE']);
@$pg_transaction->setCustShipName($_REQUEST['CUST_SHIP_NAME']);
?>

<?php
// if form is submitted
if (isset($_REQUEST['payment_check'])) {
    $transaction = new TransactionInfo();
    $res = $transaction->createPretransaction();

    if($res['status'] == 1) {
        $postdata = $pg_transaction->createTransactionRequest();
        $pg_transaction->redirectForm($postdata);
    }else {
        // print_r($res);
        echo "
            <h1 style='color:red;'>There was some problem in payment processing. Please try again after some time or report to admin. </h1>
        ";
    }
    exit();
} else {
  ?>
<?php
}
?>