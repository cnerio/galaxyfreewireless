<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


use setasign\Fpdi\Fpdi;
require('../PDF/fpdf.php');

require_once('../PDF/src/autoload.php');


date_default_timezone_set('America/New_York');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// header("Access-Control-Allow-Origin: *");
// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header("Access-Control-Allow-Headers: Content-Type, Authorization");
// header("Content-Type: application/json; charset=UTF-8");
// header("Connection: keep-alive");

include('../../../app/config/config.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/surgephone/LifelineProject/app/config/config.php');

// require_once($_SERVER['DOCUMENT_ROOT'] . '/surgephone/LifelineProject/app/libraries/Database.php');

$raw = file_get_contents("php://input");
file_put_contents("receiving.txt", $raw);
$arrayPost = json_decode($raw, true);

//$arrayPost['orderId']=87818;


if (isset($arrayPost['orderId'])) {
    $orderId = $arrayPost['orderId'];

        $db = connections();
        $stmt = $db->prepare('SELECT * FROM lifeline_records WHERE order_id=?');
        $stmt->execute([$orderId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {

            $name = $row['first_name'] . " " . $row['second_name'];
            $first_name = $row['first_name'];
            $second_name = $row['second_name'];
            $fname_initial = strtoupper(substr($row['first_name'], 0, 1));
            $sname_initial = strtoupper(substr($row['second_name'], 0, 1));
            $initials = $fname_initial . $sname_initial;
            $signature_text = $row['signature_text'];
            $date_created = date('mdY', strtotime($row['created_at']));
            $address = $row['address1'];
            $address2 = $row['address2'];
            $city = $row['city'];
            $state = $row['state'];
            $zipcode = $row['zipcode'];
            $email = $row['email'];
            $phone_number = $row['phone_number'];
            $dob = $row['dob'];
            $ssn = $row['ssn'];
            $tribal_id = $row['tribal_id'];
            $dobParts = explode('/', $dob);
            $month = $dobParts[0];
            $day = $dobParts[1];
            $year = $dobParts[2];
            /**************************/
            $shipping_address1 = $row['shipping_address1'];
            $shipping_address2 = $row['shipping_address2'];
            $shipping_city = $row['shipping_city'];
            $shipping_state = $row['shipping_state'];
            $shipping_zipcode = $row['shipping_zipcode'];
            /**************************/
            $complete_address = $row['address1'] . "," . $row['city'] . "," . $row['state'] . " " . $row['zipcode'];
            $docName = "Consent_".$row['customer_id'].".pdf";

            //$pdfPath = "../../uploads/".$row['customer_id']."/". $docName;
            $pdfPath = $_SERVER['DOCUMENT_ROOT']."/public/uploads/".$row['customer_id']."/". $docName;
            // switch ($company) {
            //     case "TERRACOM":
            //         $consentTemplate = "LifelineConsent_Terracom.pdf";
            //         break;
            //     case "AMBT":
            //         $consentTemplate =  "LifelineConsent_AMBTCA.pdf";
            //         break;
            //     case "SURGE_LIFELINE":
            //         $consentTemplate = "";
            //         break;
            //     case "TORCH_LIFELINE":
            //         $consentTemplate = "LifelineConsent_Torch.pdf";
            //         break;
            //     default:
            //         $consentTemplate = "";
            //         break;
            // }
            // if($state == "CA"){

            $pdf = new Fpdi();

            $pdf->AddPage();

            

                    $consentTemplate = "AMBTNewConsentFormJuly25.pdf";
            $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(1);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 120, $first_name);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(45, 124, $middle_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 128, $second_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 140, $month);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(73, 140, $day);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(95, 140, $year);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(165, 140, (($ssn != "") ? $ssn : $tribal_id));

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 158, $address);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(135, 158, $city);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 165, $address2);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(85, 165, $state);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(122, 165, $zipcode);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 185, $shipping_address1);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(130, 185, $shipping_address2);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(35, 192, $shipping_city);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(85, 192, $shipping_state);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(122, 192, $shipping_zipcode);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 198, $email);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(167, 198, $phone_number);

                    $pdf->AddPage();
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(2);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 61, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 67, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 164, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 174, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 204, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 226, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 238, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 244, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 256, $initials);


                    $pdf->AddPage();
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(3);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 18, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 24, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 33, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 39, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 45, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 58, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 121, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 134, $initials);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(17, 143, $initials);

                    $pdf->Text(55, 170, $signature_text);
                    $pdf->SetFont('Arial', '', 12);

                    $pdf->Text(165, 170,  $date_created);

                    $pdf->Output('F', $pdfPath);
                    //$pdf->Output('F', $pdfPath);

                // }else{
                //     $consentTemplate = "LifelineConsent_Torch.pdf";

                //     $pdf->setSourceFile($consentTemplate);
                //     $tplIdx = $pdf->importPage(1);
                //     $pdf->useTemplate($tplIdx, 2, 2, 213);
                //     $pdf->SetFont('Arial', '', 12);
                //     $pdf->Text(81, 17, $orderId);
                //     $pdf->SetFont('Arial', '', 12);
                //     $pdf->Text(81, 25, $first_name);
                //     $pdf->SetFont('Arial', '', 12);
                //     $pdf->Text(81, 35, $second_name);
                //     $pdf->SetFont('Arial', '', 12);
                //     $pdf->Text(25, 263, $signature_text);
                //     $pdf->SetFont('Arial', '', 12);
                //     $pdf->Text(168, 263, $date_created);
                //    // $pdf->Output('F', $pdfPath);
                // }

                 $dir = dirname($pdfPath); // $outputPath is your full PDF path
                    if (!is_dir($dir)) {
                        mkdir($dir, 0775, true); // Create directory recursively with permissions
                    }

                    $pdf->Output('F', $pdfPath);

                $pdfContent = file_get_contents($pdfPath);

                

                // Convierte a Base64

                $pdfBase64 = base64_encode($pdfContent);

                $url = URLROOT."/uploads/".$row['customer_id']."/". $docName;

                if (!empty($pdfBase64)) {

                    if (!empty($pdfBase64)) {
                        msgReturn('success', 'Success', $pdfBase64,$docName,$url);

                    } else {
                        msgReturn('error', 'An error occurred while trying to create the PDF', '');
                    }

                } else {
                    msgReturn('error', 'An error occurred while trying to save the PDF', '');
                }

        } else {
            msgReturn('error', 'Records not found', '');
        }

} else {

    msgReturn('error', 'OrderId not found', '');
}





function msgReturn($status, $msg, $pdfBase64 = null,$docName='',$url=null)

{
    if($status=="success"){
        http_response_code(200);
    }else{
        http_response_code(400);
    }
    $return = array();
    $return['status'] = $status;
    $return['msg'] = $msg;
    $return['docName'] = $docName;
    $return['pdfBase64'] = $pdfBase64;
    $return['URL'] = $url;

    echo json_encode($return);

    exit();

}









function connections()

{

    try {

        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

    } catch (PDOException $e) {

        file_put_contents("cnnerror22.txt", $e);

    }



    return $dbh;

}
