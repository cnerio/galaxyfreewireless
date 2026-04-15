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

$arrayPost['orderId']='WGT5894';
$env = "prod";
if (isset($arrayPost['orderId'])) {
    //file_put_contents("select.txt", json_encode($row));
    $orderId = $arrayPost['orderId'];

        $db = connections();
        $stmt = $db->prepare('SELECT lr.*,lp.name as "program_name" FROM lifeline_records lr LEFT JOIN lifeline_programs lp ON lr.program_benefit = lp.id_program WHERE order_id=?');
        $stmt->execute([$orderId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        file_put_contents("select.txt", json_encode($row));
        if (!empty($row)) {

            $name = $row['first_name'] . " " . $row['second_name'];
            $first_name = $row['first_name'];
            $second_name = $row['second_name'];
            $fname_initial = strtoupper(substr($row['first_name'], 0, 1));
            $sname_initial = strtoupper(substr($row['second_name'], 0, 1));
            $initials = $fname_initial . $sname_initial;
            $signature_text = $row['signature_text'];
            $typeAddress = $row['typeAddress'];
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
            $program_name = ($row['ETC']=="AMBT") ? $row['program_name'] : $row['program_benefit'];
            /**************************/
            $complete_address = $row['address1'] . "," . $row['city'] . "," . $row['state'] . " " . $row['zipcode'];
            $docName = "Consent_".$row['customer_id'].".pdf";

            //$pdfPath = "../../uploads/".$row['customer_id']."/". $docName;
            if ($env=="local"){
                $pdfPath = "../../uploads/".$row['customer_id']."/". $docName;
            }else{
                $pdfPath = $_SERVER['DOCUMENT_ROOT']."/public/uploads/".$row['customer_id']."/". $docName;
            }
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
             if($state == "CA"){
                 $pdf = new Fpdi();

            $pdf->AddPage();

            

                    $consentTemplate = "CONSENT_LL_AMBT_CA.pdf";
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(1);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(21, 76.2, "x");

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(35, 110, $first_name);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(45, 124, $middle_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(148, 110, $second_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 118, $dob);

                    $pdf->SetFont('Arial','',12);

                    if($typeAddress=="Permanent"){
                        $pdf->SetFont('Arial', '', 12);
                        $pdf->Text(20.8, 136.2, "X");
                    }else{
                        $pdf->SetFont('Arial', '', 12);
                        $pdf->Text(20.8, 144.8, "X");
                    }

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(73, 140, $day);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(95, 140, $year);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(148, 118, (($ssn != "") ? $ssn : $tribal_id));

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(58, 153, $address);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(25, 161, $city);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(180, 153, $address2);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(115, 161, $state);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(139, 161, $zipcode);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 177, $shipping_address1);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(140, 177, $shipping_address2);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(35, 185, $shipping_city);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(115, 185, $shipping_state);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(142, 185, $shipping_zipcode);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 192, $email);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(167, 192, $phone_number);

                    $pdf->AddPage();
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(2);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 29.8, 'X');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 48.5,'X');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 193.8, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 214.8, 'X');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 243.2, 'X');

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 226, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 238, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 244, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 256, $initials);


                    $pdf->AddPage();
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(3);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 18, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 24, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 33, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 39, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 45, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 58, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 121, $initials);

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Text(58, 86, $program_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 106.2, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 110.5, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 129.5, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 138.8, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 143.5, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 152.8, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 157.5, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 167, 'x');

                     $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(21, 225, 'x');

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(20.8, 255.5, 'X');

                    $pdf->AddPage();
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(4);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 45.5, 'X');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 67.5, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 113, 'x');

                    $pdf->Text(55, 168, $signature_text);
                    $pdf->SetFont('Arial', '', 12);

                    $pdf->Text(160, 168,  $date_created);
             }else{
                 $pdf = new Fpdi();

            $pdf->AddPage();

            

                    $consentTemplate = "CONSENT_LL_AMBT_925.pdf";
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(1);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.5, 71.8, "X");

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(35, 118, $first_name);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(45, 124, $middle_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(148, 118, $second_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 126, $dob);

                    $pdf->SetFont('Arial','',12);

                    if($typeAddress=="Permanent"){
                        $pdf->SetFont('Arial', '', 12);
                        $pdf->Text(20.8, 144.2, "X");
                    }else{
                        $pdf->SetFont('Arial', '', 12);
                        $pdf->Text(20.8, 152.8, "X");
                    }

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(73, 140, $day);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(95, 140, $year);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(148, 126, (($ssn != "") ? $ssn : $tribal_id));

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(58, 161, $address);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(25, 169, $city);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(180, 161, $address2);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(115, 169, $state);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(139, 169, $zipcode);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 185, $shipping_address1);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(140, 185, $shipping_address2);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(35, 193, $shipping_city);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(115, 193, $shipping_state);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(142, 193, $shipping_zipcode);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 200, $email);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(167, 200, $phone_number);

                    $pdf->AddPage();
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(2);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 29.8, 'X');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 48.5,'X');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.5, 181, 'X');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 202.5, 'X');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 231, 'X');

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 226, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 238, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 244, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 256, $initials);


                    $pdf->AddPage();
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(3);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 18, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 24, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 33, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 39, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 45, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 58, $initials);

                    // $pdf->SetFont('Arial', '', 12);
                    // $pdf->Text(17, 121, $initials);

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Text(58, 73, $program_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 93.2, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 98, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 116.5, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 126, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 130.5, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 140, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 144.5, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(27.2, 154, 'x');

                     $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(21, 212, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 255.5, 'X');

                    $pdf->AddPage();
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(4);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 35.5, 'x');

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(20.8, 81, 'x');

                    $pdf->Text(55, 142, $signature_text);
                    $pdf->SetFont('Arial', '', 12);

                    $pdf->Text(160, 142,  $date_created);
             }

           

                    
                 $dir = dirname($pdfPath); // $outputPath is your full PDF path
                    if (!is_dir($dir)) {
                        mkdir($dir, 0775, true); // Create directory recursively with permissions
                    }

                    $pdf->Output('F', $pdfPath);
                    //$pdf->Output('F', $pdfPath);
                    //exit();
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


                    //$pdf->Output('F', $pdfPath);
                  

                $pdfContent = file_get_contents($pdfPath);

                

                // Convierte a Base64

                $pdfBase64 = base64_encode($pdfContent);
                    
                

                if ($env=="local"){
                $url = "../../uploads/".$row['customer_id']."/". $docName;
            }else{
                $url = URLROOT."/uploads/".$row['customer_id']."/". $docName;
            }

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

        $dbh = new PDO('mysql:host='.DB_HOST.';port=3308;dbname='.DB_NAME, DB_USER, DB_PASS);

    } catch (PDOException $e) {

        file_put_contents("cnnerror22.txt", $e);

    }



    return $dbh;

}
