<?php

use setasign\Fpdi\Fpdi;

error_reporting(E_ALL);
ini_set('display_errors', '1');
require('fpdf.php');
require_once('src/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/surgephone/LifelineProject/app/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/surgephone/LifelineProject/app/libraries/Database.php');
$db = new Database();

$code = $_GET['c'];
$decode = base64_decode($code);
$porciones = explode("-", $decode);
$company = $porciones[0];
$orderId = $porciones[1];
$source = $porciones[2];

if ($company === "TERRACOM") {
    $configs = $GLOBALS["configs"]["TERRACOM"];
} else {   
    $configs = $GLOBALS["configs"][$company] ?? $GLOBALS["configs"]["SURGE"];
}
/**********************************/
$payload = array(
    'apikey' => 'U3VyZ2VwYXlzMjQ6VyEybTZASnk4QVFk',
    'company' => $company,
    'source' => $source,
);
$url = "https://secure-order-forms.com/surgephone/endpoint/clec_credentials.php";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($payload)
));
$response = curl_exec($curl);
curl_close($curl);
$credentials = json_decode($response, true);
/**********************************/

$db->query("SELECT * FROM ".$configs['table']." WHERE order_id=:order_id");
$db->bind(":order_id", $orderId);
$row = $db->Single();
//print_r($row);
//echo "<pre>";
//print_r($response);

$name = $row['first_name'] . " " . $row['second_name'];
$address = $row['address1'] . "," . $row['city'] . "," . $row['state'] . " " . $row['zipcode'];

$docName = $row['customer_id'] . "-" . rand(0, 9999) . ".pdf";
$pdf = new Fpdi();
$pdf->SetTitle('Consent ' . $orderId);

$pdf->AddPage();
$pdf->setSourceFile("templates/LifelineConsent.pdf");
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 2, 2, 213);
$pdf->SetFont('Arial', 'B', '12');
$pdf->Cell(150, 128);
$pdf->Cell(60, 128, $orderId);
$pdf->Ln(20);
$pdf->Cell(20, 100);
$pdf->Cell(60, 100, $row['firstname']);
$pdf->Cell(20, 100);
$pdf->Cell(80, 100, $row['lastname']);
$pdf->Ln(20);
$pdf->Cell(20, 71);
$pdf->Ln(20);
$pdf->Cell(20, 43);
$pdf->Cell(60, 43);
$pdf->Cell(70, 43);
$pdf->Cell(60, 43, $row['signature_text']);
$pdf->Cell(60, 83, $row['date_create']);
$pdf->Ln(5);


$pdf->Output($docName, 'D');


$curl2 = curl_init();

$pdfFile = file_get_contents($configs['consent'] . $docName);
$data64 = base64_encode($pdfFile);

$url2 = "https://wirelessapi.shockwavecrm.com/Prepaidwireless/UploadDocument";
if ($company != "") {
    $valid = 1;
} else {
    $valid = 0;
}
$request_1 = '{
	 "Credential": {
		"CLECID": "' . $credentials["CLECID"] . '",
		"UserName": "' . $credentials["UserName"] . '",
		"TokenPassword": "' . $credentials["TokenPassword"] . '",
		"PIN": "' . $credentials["PIN"] . '"
	  },
	  "SubscriberOrderId" : "' . $order_id . '",
	  "DocumentName" : "' . $customerpdf . '",
	  "DocumentTypeID" : "100015",
	  "DocumentData" :"' . $data64 . '"
	}';

curl_setopt_array($curl2, array(
    CURLOPT_URL => $url2,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $request_1,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
));

$response2 = curl_exec($curl2);
curl_close($curl2);
$data = json_decode($response2, true);
