<?php
use setasign\Fpdi\Fpdi;
require('../PDF/fpdf.php');

require_once('../PDF/src/autoload.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");
header("Connection: keep-alive");

//require_once($_SERVER['DOCUMENT_ROOT'] . '/surgephone/LifelineProject/app/config/config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/surgephone/LifelineProject/app/libraries/Database.php');


$raw = file_get_contents("php://input");
$arrayPost = json_decode($raw, true);
print_r($arrayPost);

if (!empty($arrayPost)) {

    $company = $arrayPost['company'] ?? null;
    $orderId = $arrayPost['orderId'] ?? null;
    $rawsource = $arrayPost['source'] ?? null;
    $source = ($rawsource === "AMBTC") ? "AMBT" : $rawsource;

    if ($company === "TORCH_LIFELINE" || $company === "TERRACOM" || $company === "AMBT" ) {
        $configs = $GLOBALS['configs']["LIFELINE"];
    } else {
        $configs = $GLOBALS['configs'][$company] ?? $GLOBALS['configs']["SURGE"];
    }

    $credentials = getCredencials($company, $source);

    if (!empty($credentials['CLECID'])) {

        $db = connections();
        $stmt = $db->prepare('SELECT * FROM c1_surgephone.lifeline_registration WHERE order_id=?');
        $stmt->execute([$orderId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {

            $name = $row['first_name'] . " " . $row['second_name'];
            $first_name = $row['first_name'];
            $second_name = $row['second_name'];
            $signature_text = $row['signature_text'];
            $date_create = date('m-d-Y', strtotime($row['date_create']));
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
            $docName = $row['customer_id'] .'_'.$row['first_name'] . $row['second_name'] . "_" . rand(0, 9999) . ".pdf";

            $pdfPath = '../../../../../surgephone/lifeline_application/landing_files/LifelineConsent/'. $docName;

            $pdf = new Fpdi();

            $pdf->AddPage();

            $consentTemplate = "";

            switch ($company) {
                case "TERRACOM":
                    $consentTemplate = "LifelineConsent_Terracom.pdf";
                    break;
                case "AMBT":
                    $consentTemplate = ($rawsource === "AMBTC")
                    ? "LifelineConsent_AMBTCA.pdf"
                    : "LifelineConsent_AMBT.pdf";
                    break;
                case "SURGE_LIFELINE":
                    $consentTemplate = "";
                    break;
                case "TORCH_LIFELINE":
                    $consentTemplate = "LifelineConsent_Torch.pdf";
                    break;
                default:
                    $consentTemplate = "";
                    break;
            }

            if (!empty($consentTemplate)) {


                if($rawsource === "AMBTC"){

                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(1);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);
                   
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45,120, $first_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 128, $second_name);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 135, $month);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(73, 135,$day);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(95, 135, $year);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(165, 135, (($ssn != "") ? $ssn : $tribal_id));
                    
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 152, $address);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(135, 152, $address2);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(35, 158, $city);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(85, 158, $state);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(122, 158, $zipcode);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 178, $shipping_address1);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(130, 178, $shipping_address2);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(35, 185, $shipping_city);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(85, 185, $shipping_state);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(122, 185, $shipping_zipcode);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(45, 192, $email);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(167, 192, $phone_number);

                    $pdf->AddPage(); 
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(2);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);
                    

                    $pdf->AddPage(); 
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(3);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);
                    

                    $pdf->AddPage(); 
                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(4);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);
                    
                    $pdf->Text(55, 22, $signature_text);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(165, 22, $date_create);

                    $pdf->Output('F', $pdfPath);

                }else{

                    $pdf->setSourceFile($consentTemplate);
                    $tplIdx = $pdf->importPage(1);
                    $pdf->useTemplate($tplIdx, 2, 2, 213);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(81, 17, $orderId);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(81, 25, $first_name);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(81, 35, $second_name);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(25, 263, $signature_text);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Text(168, 263, $date_create);
                    $pdf->Output('F', $pdfPath);
                }

                $pdfContent = file_get_contents($pdfPath);

                // Convierte a Base64

                $pdfBase64 = base64_encode($pdfContent);



                if (!empty($pdfBase64)) {

                    if (!empty($pdfBase64)) {
                        msgReturn(true, 'Success', $pdfBase64,$docName);

                    } else {
                        msgReturn(false, 'An error occurred while trying to create the PDF', '');
                    }

                } else {
                    msgReturn(false, 'An error occurred while trying to save the PDF', '');
                }

            } else {
                msgReturn(false, 'Template Consent not found', '');
            }

        } else {
            msgReturn(false, 'OrderId not found', '');
        }

    } else {
        msgReturn(false, 'Credentials not found');
    }

} else {
    msgReturn(false, '', '');
}





function msgReturn($status, $msg, $pdfBase64 = null,$docName='')

{

    $return = array();
    $return['status'] = $status;
    $return['msg'] = $msg;
    $return['docName'] = $docName;
    $return['pdfBase64'] = $pdfBase64;
    // $return['ItemsOrder'] = $ItemsOrder;

    echo json_encode($return);

    exit();

}





function getCredencials($company, $source)

{



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

    return $credentials = json_decode($response, true);

}



function connections()

{

    try {

        $dbh = new PDO('mysql:host=localhost;dbname=c1_surgephone', 'c1_surge', 'LicnbzPTWt#K8');

    } catch (PDOException $e) {

        file_put_contents("cnnerror22.txt", $e);

    }



    return $dbh;

}





// function getOrder($config) {



//     $stmt = $pdo->prepare('SELECT * FROM users WHERE id=?');

//     $stmt->execute([id]); 

//     $user = $stmt->fetch();



//     $db->query("SELECT * FROM ".$configs['table']." WHERE order_id=:order_id");

// }

