<?php 
use setasign\Fpdi\Fpdi; 
error_reporting(E_ALL);
ini_set('display_errors', '1');
require('fpdf.php'); 
require_once('src/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/surgephone/customerPortal/app/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/surgephone/customerPortal/app/libraries/Database.php');
$db = new Database();
$code = $_GET['c'];
$decode=base64_decode($code);
$porciones = explode("-", $decode);
$invoiceNum=$porciones[1];
$orderId=$porciones[0];
//echo $_SESSION['user_id'];



//$invoiceNum="2813938";
//$orderId="367196";

$db->query('SELECT * FROM c1_surgephone.portalusers WHERE subscriber_id=:orderId');
$db->bind(":orderId",$orderId);
$row =$db->Single();
//print_r($row);

$curl = curl_init();

$url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/QuerySubscriberInvoices";
		$request = '{
					  "Credential": 
					  {
						 "CLECID":"78",
						 "UserName":"SurgePhone",
						 "TokenPassword":"569180C3D-D6B4-4B9A-B4BB-BA0976910757",
						 "PIN": "56449834"
					  },
					  "MDN":"",
					"OrderId":"'.$orderId.'"
					}';

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$request,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo "<pre>";
//print_r($response);
$data=json_decode($response,true);

foreach($data['Invoices'] as $invoice){
	if($invoice['InvoiceNumber']==$invoiceNum){
		
		$type=$invoice['InvoiceType'];
		
		$invoiceDate=date( "m-d-Y",strtotime($invoice['InvoiceDate']));
		$invoiceYear=date( "Y",strtotime($invoice['InvoiceDate']));
		$invoiceDueDate=($invoice['PaymentDueDate'])?date( "m-d-Y",strtotime($invoice['PaymentDueDate'])):"-";
		
		$qty=1;
		$description= $invoice['CustomerPackage'];
		$price="$".number_format($invoice['TotalAmount'], 2, '.', '');
		$amount="$".number_format($invoice['TotalAmount'], 2, '.', '');
		$total="$".number_format($invoice['TotalAmount'], 2, '.', '');
		$details = $invoice['Details'];
	}
}
$name= $row['firstname']." ".$row['lastname'];
$address = $row['address'].",".$row['city'].",".$row['state']." ".$row['zipcode'];


//echo $timestamp = strtotime($invoiceDate);
//$invoiceYear = date("Y", strtotime($invoiceDate));
if($invoiceYear=="2022"){
	$planType = "ACP";
	//$value="30.00";
}else{
	$planType="EBB";
	//$value="50.00";
}

$docName=$invoiceNum."-".rand(0,9999).".pdf";
$pdf = new Fpdi();
$pdf->SetTitle('SurgePhone Invoice '.$invoiceNum);

$pdf->AddPage();
$pdf->setSourceFile("templates/invoice_template2.pdf"); 
$tplIdx = $pdf->importPage(1); 
$pdf->useTemplate($tplIdx, 2, 2, 213);  
$pdf->SetFont('Arial','B','12'); 
$pdf->Cell(150,128);
$pdf->Cell(60,128,$invoiceNum);
$pdf->Ln(20);
$pdf->Cell(20,100);
$pdf->Cell(60,100,$name);
$pdf->Cell(70,100);
$pdf->Cell(60,100,$invoiceDate);
$pdf->Ln(20);
$pdf->Cell(20,71);
$pdf->Cell(60,71,$address);
$pdf->Cell(70,71);
$pdf->Cell(60,71,$invoiceDueDate);
$pdf->Ln(20);
$pdf->Cell(20,43);
$pdf->Cell(60,43);
$pdf->Cell(70,43);
$pdf->Cell(60,43,$orderId);
$pdf->Ln(5);
if($type=="New Order"){
	rsort($details);
	//print_r($details);
	foreach($details as $items){
		$pdf->Ln(10);
		$pdf->Cell(25,90);
		$pdf->Cell(12,90,$qty);
		$pdf->Cell(80,90,$items['Description']);
		$pdf->Cell(35,90);
		$pdf->Cell(40,90,"$".number_format($items['Amount'], 2, '.', ''));
	}
	
	$pdf->Ln(20);
	$pdf->Cell(25,73);
	$pdf->Cell(20,73);
	$pdf->Cell(75,73);
	$pdf->Cell(35,73);
	$pdf->Cell(40,73,$total);
	
//	$pdf->Ln(15);
//	$pdf->Cell(25,90);
//	$pdf->Cell(12,90,$qty);
//	$pdf->Cell(80,90,"Connected Device 3");
//	$pdf->Cell(35,90);
//	$pdf->Cell(40,90,"$110.99");
	
//	$pdf->Ln(10);
//	$pdf->Cell(25,90);
//	$pdf->Cell(12,90);
//	$pdf->Cell(80,90,'T-Mobile '.$planType.' 10GB Plan');
//	$pdf->Cell(35,90);
//	$pdf->Cell(40,90,"$".$value);
	
//	$pdf->Ln(10);
//	$pdf->Cell(25,90);
//	$pdf->Cell(12,90);
//	$pdf->Cell(80,90,"Connected Device 3 ".$planType." Discount");
//	$pdf->Cell(35,90);
//	$pdf->Cell(40,90,"$-100.00");
//	
//	$pdf->Ln(10);
//	$pdf->Cell(25,90);
//	$pdf->Cell(12,90);
//	$pdf->Cell(80,90,$planType.' Discount');
//	$pdf->Cell(35,90);
//	$pdf->Cell(40,90,"$-".$value);
//	
//	$pdf->Ln(20);
//	$pdf->Cell(25,73);
//	$pdf->Cell(20,73);
//	$pdf->Cell(75,73);
//	$pdf->Cell(35,73);
//	$pdf->Cell(40,73,"$10.99");
	

}else{
	sort($details);
	//print_r($details);
	foreach($details as $items){
		
		if($planType=="ACP"){
		$description = str_replace('EBBP', 'ACP',$items['Description']);
		}else{
		$description = $items['Description'];
		}
		
		$pdf->Ln(10);
		$pdf->Cell(25,90);
		$pdf->Cell(12,90,$qty);
		$pdf->Cell(80,90,$description);
		$pdf->Cell(35,90);
		$pdf->Cell(40,90,"$".number_format($items['Amount'], 2, '.', ''));
	}
	$pdf->Ln(15);
	$pdf->Cell(25,123);
	$pdf->Cell(20,123);
	$pdf->Cell(75,123);
	$pdf->Cell(35,123);
	$pdf->Cell(40,123,$total);
}

$pdf->Output($docName,'D');