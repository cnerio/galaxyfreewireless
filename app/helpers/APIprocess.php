<?php 

Class APIprocess{


    public function shockwaveProcess($customerId,$enrollModel){
      //'G-SN3C0031'
      
        $customerData = $enrollModel->getCustomerData($customerId);
       
        if($customerData && $customerData[0]['customer_id']){

        if($customerData[0]['ETC'] && $customerData[0]['ETC']!=""){
          $company=$customerData[0]['ETC'];
        }
         
        $credentials=$enrollModel->getCredentials($company);
        $processData['customer_id']=$customerId;
        $packages = $enrollModel->getPackages($company);
        file_put_contents("stepLog.txt", json_encode($packages)."\n", FILE_APPEND);
        $createResponse=create_shockwave_account($customerData[0],$credentials[0],$packages);
        $processData['process_status']="addSubscriberOrder API";
        $enrollModel->updateData($processData,'lifeline_records');
        
        $saveCreateLog=[
          "customer_id"=>$customerId,
          "url"=>$createResponse['url'],
          "request"=>$createResponse['request'],
          "response"=>json_encode($createResponse['response']),
          "title"=>$createResponse['title']
        ];
        $enrollModel->saveData($saveCreateLog,'lifeline_apis_log');
        if($createResponse['status']=="success"){
          
          if($createResponse['order_id']>0){
            $folder = "../public/uploads/".$customerId."/";
                if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }
            $dataOrder=[
                "customer_id"=>$customerId,
                "order_id"=>$createResponse['order_id'],
                "account"=>$createResponse['account'],
                "acp_status"=>$createResponse['acp_status'],
                "status_text"=>$createResponse['status_text'],
                "process_status"=>"saving Order ID > 0"
              ];
              $enrollModel->updateData($dataOrder,"lifeline_records");
              $row = $enrollModel->getCustomerData($customerId);

              $consentFile64=$this->getConsentFile($row[0]['order_id']);
            //$consentFile64 = getConsent64($row[0]);
            // print_r($consentFile64);
              $processData['process_status']="generating consent File";
              $enrollModel->updateData($processData,'lifeline_records');
            // exit();
            if($consentFile64['status']=="success"){
              $fileData = [
                 "customer_id"=>$customerData[0]['customer_id'],
                 "filepath"=>$consentFile64['URL'],
                 "type_doc"=>"Consent"
               ];
               $enrollModel->saveData($fileData,'lifeline_documents');
              $ConsentFileResult = $this->sendDocuments($customerId,$row[0]['order_id'],"Consent",$enrollModel,$company);
              $processData['process_status']=$ConsentFileResult['msg'];
              $enrollModel->updateData($processData,'lifeline_records');
                $result=[
                  "status"=>"success",
                  "msg"=>"Order Submitted and Consent file submitted",
                  "order_id"=>$row[0]['order_id']
                ];
              
            }else{
              //echo "base64 error";
              $result=[
                "status"=>"success",
                "msg"=>"We couldn't create a consent file"
              ];
               $processData['process_status']="Couldn't create a consent file";
              $enrollModel->updateData($processData,'lifeline_records');
            }

              $checkId = $this->getSavedfiles($customerId,$enrollModel,'ID');
              if($checkId){
                //echo "GET FILE";
                $IdFileResult = $this->sendDocuments($customerId,$row[0]['order_id'],"ID",$enrollModel,$company);
                $processData['process_status']=$IdFileResult['msg'];
                $enrollModel->updateData($processData,'lifeline_records');
              }

              $checkPOB = $this->getSavedfiles($customerId,$enrollModel,'POB');
              if($checkPOB){
              
              $POBFileResult = $this->sendDocuments($customerId,$row[0]['order_id'],"POB",$enrollModel,$company);
              $processData['process_status']=$POBFileResult['msg'];
              $enrollModel->updateData($processData,'lifeline_records');

              }
              

            
            
            //print_r($result);
          }else{
              //echo "else orderId 0";
            $dataOrder=[
              "customer_id"=>$customerId,
              "order_id"=>$createResponse['order_id'],
              "account"=>$createResponse['account'],
              "acp_status"=>$createResponse['acp_status'],
              "status_text"=>$createResponse['status_text'],
              "process_status"=>"Shockwave process success but Order id O"
            ];
            $enrollModel->updateData($dataOrder,"lifeline_records");
              $result = [
              "status"=>"success",
              "msg"=>"Shockwave process Success but Order id O"
            ];
          }
          //print_r($result);

        }else{
          
          $result = [
          "status"=>"success",
          "msg"=>"Something went wrong submitting your application"
        ];
        $processData['process_status']="Something went wrong submitting your application";
        $enrollModel->updateData($processData,'lifeline_records');
        }
        //print_r($result);
      }else{
        $result = [
          "status"=>"error",
          "msg"=>"Invalid Customer ID"
        ];
      }
      //print_r($result);
      return $result;
    }

    public function getIdfile($customerID,$enrollModel){
      $checkID = $enrollModel->checkIdFile($customerID);
      //print_r($checkID);
      return $checkID;
    }

    public function getSavedfiles($customerID,$enrollModel,$filetype){
      $fileData = $enrollModel->getFiles($customerID,$filetype);
      //print_r($checkID);
      return $fileData ;
    }

    public function getConsentFile($orderId){

        //echo URLROOT.'/public/files/consentPDF/';
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URLROOT.'/public/files/consentPDF/',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "orderId":'.$orderId.'
        }',
          CURLOPT_SSL_VERIFYHOST => IS_LOCALHOST ? 0 : 2,
          CURLOPT_SSL_VERIFYPEER => IS_LOCALHOST ? 0 : 1,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        $curl_error = curl_error($curl);
        $curl_errno = curl_errno($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        // Step 1: Check if cURL itself failed (connection problems, timeouts, DNS, etc.)
        if ($curl_errno) {
            //echo "cURL error: $curl_error"; // This includes many kinds of outages
            $result = [
              "status"=>"error",
              "msg"=>$curl_error
            ];
            // Optionally log or retry here
        } 
        // Step 2: Check if API returned an HTTP error
        elseif ($http_code >= 400) {
            //echo "API HTTP error: $http_code";
            $result = [
              "status"=>"error",
              "msg"=>"HTTP ERROR CODE: ".$http_code
            ];
            // Optional: you might want to parse $response for error details
        }
        // Step 3: All good
        else {
            $result = json_decode($response,true);
        }
        return  $result;

    }

    public function sendDocuments($customerId,$orderId,$fileType,$enrollModel,$company){
    //$this->APIService = new APIprocess();
    //$row = $this->enrollModel->getCustomerData($customerId);
    switch($fileType){
      case "ID":
        $fileID="100001";
        break;
      case "POB":
        $fileID="100000";
        break;
      case "Consent":
        $fileID="100025";
        break;
    }
    //print_r($row);
    $fileData = $this->getSavedfiles($customerId,$enrollModel,$fileType);
    $credentials=$enrollModel->getCredentials($company);
    if($orderId>0){
      if($fileData){
                // Read the image file into a binary string 
                //$imageData = file_get_contents($fileData['filepath']);
                $imageData = $this->curl_get_file_contents($fileData['filepath']);
                $filename = basename($fileData['filepath']);
                //$compressed = gzencode($imageData);               // Compress with gzip
    //$compressedBase64 = base64_encode($compressed);
                // Encode the binary data to base64
                $base64 = base64_encode($imageData);
                $upload=UploadDocument($credentials[0], $orderId, $filename, $base64, $fileID,$company);
                if($upload['status']=="success"){
                  $saveCreateIDLog=[
                    "customer_id"=>$customerId,
                    "url"=>$upload['url'],
                    "request"=>$upload['request'],
                    "response"=>json_encode($upload['response']),
                    "title"=>$upload['title']
                  ];
                  $enrollModel->saveData($saveCreateIDLog,'lifeline_apis_log');
                  $fileupdate = ["id_lifeline_doc"=>$fileData['id_lifeline_doc'],"to_unavo"=>1];
                 // $enrollModel->saveData($fileId,'lifeline_documents');
                 $enrollModel->updateDocStatus($fileupdate ,'lifeline_documents');
                 //echo "ID FILE UPLOADED";
                 $result=["status"=>"success","msg"=>$fileType." FILE  UPLOADED"];
                }else{
                  $saveCreateIDLog=[
                    "customer_id"=>$customerId,
                    "url"=>$upload['url'],
                    "request"=>$upload['request'],
                    "response"=>json_encode($upload['response']),
                    "title"=>$upload['title']
                  ];
                  $enrollModel->saveData($saveCreateIDLog,'lifeline_apis_log');
                //echo "ID FILE COULDN'T BE UPLOAD";
                $result=["status"=>"fail","msg"=>$fileType." FILE COULDN'T BE UPLOAD"];
              }
              }else{
                 $result=["status"=>"fail","msg"=>$fileType." Couldn't be uploaded. File Data not Found"];
              }
    }else{
      $result=["status"=>"fail","msg"=>$fileType." Couldn't be uploaded.Order ID not Found"];
    }

    return $result;
  }

  public function curl_get_file_contents($url) {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $data = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('cURL error: ' . curl_error($ch));
    }

    curl_close($ch);

    return $data;
}
}
