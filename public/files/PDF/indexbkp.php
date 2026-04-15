<?php 
use setasign\Fpdi\Fpdi; 
error_reporting(E_ALL);
ini_set('display_errors', '1');
require('fpdf.php'); 
require_once('src/autoload.php');
$code = $_GET['c'];
$decode=base64_decode($code);
$porciones = explode("-", $decode);
$invoiceNum=$porciones[1];
$account=$porciones[0];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://cdr.surgepays.com/api/ebbinvoice?subscriberid='.$account.'&apikey=HJBTk4hFag3Ld9pSkupfz5D9bJtEf725zQ287JEG',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_POSTFIELDS =>'
',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$str1 = substr($response, 1, -1);
$str2 =  preg_replace('/\\\\\"/',"\"", $str1);
$data=  json_decode($str2,JSON_UNESCAPED_SLASHES);

foreach($data as $invoice){
	if($invoice['INVOICE_NUMBER']==$invoiceNum){
		//print_r($invoice);
		$type=$invoice['INVOICETYPEID'];
		$name= $invoice['FirstName']." ".$invoice['LastName'];
		$invoiceDate=date( "Y-m-d",strtotime($invoice['INVOICE_DATE']));
		$invoiceDueDate=($invoice['PAYMENT_DUE_DATE'])?date( "Y-m-d",strtotime($invoice['PAYMENT_DUE_DATE'])):"-";
		$address = $invoice['Address'].",".$invoice['city'].",".$invoice['statetype']." ".$invoice['ZipCode'];
		$qty=1;
		$description= $invoice['CustomerPackage'];
		$price="$".number_format($invoice['INVOICE_TOTAL_AMOUNT'], 2, '.', '');
		$amount="$".number_format($invoice['INVOICE_TOTAL_AMOUNT'], 2, '.', '');
		$total="$".number_format($invoice['INVOICE_TOTAL_AMOUNT'], 2, '.', '');
	}
}

$timestamp = strtotime($invoiceDate);
$invoiceYear = date("Y", $timestamp);
if($invoiceYear=="2022"){
	$planType = "ACP";
	$value="30.00";
}else{
	$planType="EBB";
	$value="50.00";
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
$pdf->Cell(60,43,$account);
if($type=="100004"){
	$pdf->Ln(10);
	$pdf->Cell(25,100);
	$pdf->Cell(15,100,$qty);
	$pdf->Cell(80,100,'T-Mobile '.$planType.' 10GB Plan');
	$pdf->Cell(35,100);
	$pdf->Cell(40,100,"$".$value);
	$pdf->Ln(10);
	$pdf->Cell(25,100);
	$pdf->Cell(15,100);
	$pdf->Cell(80,100,$planType.' Discount');
	$pdf->Cell(35,100);
	$pdf->Cell(40,100,"$-".$value);
	$pdf->Ln(20);
	$pdf->Cell(25,123);
	$pdf->Cell(20,123);
	$pdf->Cell(75,123);
	$pdf->Cell(35,123);
	$pdf->Cell(40,123,$total);
}else{
	$pdf->Ln(15);
	$pdf->Cell(25,90);
	$pdf->Cell(12,90,$qty);
	$pdf->Cell(80,90,"Connected Device 3");
	$pdf->Cell(35,90);
	$pdf->Cell(40,90,"$110.99");
	
	$pdf->Ln(10);
	$pdf->Cell(25,90);
	$pdf->Cell(12,90);
	$pdf->Cell(80,90,'T-Mobile '.$planType.' 10GB Plan');
	$pdf->Cell(35,90);
	$pdf->Cell(40,90,"$".$value);
	
	$pdf->Ln(10);
	$pdf->Cell(25,90);
	$pdf->Cell(12,90);
	$pdf->Cell(80,90,"Connected Device 3 ".$planType." Discount");
	$pdf->Cell(35,90);
	$pdf->Cell(40,90,"$-100.00");
	
	$pdf->Ln(10);
	$pdf->Cell(25,90);
	$pdf->Cell(12,90);
	$pdf->Cell(80,90,$planType.' Discount');
	$pdf->Cell(35,90);
	$pdf->Cell(40,90,"$-".$value);
	
	$pdf->Ln(20);
	$pdf->Cell(25,73);
	$pdf->Cell(20,73);
	$pdf->Cell(75,73);
	$pdf->Cell(35,73);
	$pdf->Cell(40,73,"$10.99");
}

$pdf->Output($docName,'D');