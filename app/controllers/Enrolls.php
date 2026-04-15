<?php
class Enrolls extends Controller
{
  public $enrollModel;
  public $APIService;
  public $TG5Api;
  public function __construct()
  {
    $this->enrollModel = $this->model('Enroll');
  }

  public function index2()
  {
    $data = [
            "first_name" => trim(ucfirst(strtolower($_POST['firstname']))),
            "second_name" => trim(ucfirst(strtolower($_POST['lastname']))),
            // "address1" => trim($_POST['address1']),
            // "address2" => trim($_POST['addess2']),
            // "city" => trim(ucfirst(strtolower($_POST['city']))),
            "email" => trim(strtolower($_POST['email'])),
            "state" => strtoupper(trim($_POST['state'] ?? '')),
            "zipcode" => $_POST['zipcode'],
            "agent" => $_POST['agent'],
            "url" => $full_url,
            "utms"=>$utms?:"",
            "powered"=>"GTW"
            
          ];

          $this->view('enrolls/index2', $data);
  }

  public function index($customer_id = null)
  {
     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $full_url = $_POST['url'];
            parse_str(parse_url($full_url, PHP_URL_QUERY), $params);
            $utms = json_encode($params);
          $data = [
            "first_name" => trim(ucfirst(strtolower($_POST['firstname']))),
            "second_name" => trim(ucfirst(strtolower($_POST['lastname']))),
            // "address1" => trim($_POST['address1']),
            // "address2" => trim($_POST['addess2']),
            // "city" => trim(ucfirst(strtolower($_POST['city']))),
            "email" => trim(strtolower($_POST['email'])),
            "state" => strtoupper(trim($_POST['state'] ?? '')),
            "zipcode" => $_POST['zipcode'],
            "url" => $full_url,
            "utms"=>$utms,
            "powered"=>$_POST['powered'],
            "enrollment_id"=>$_POST['enrollment_id'],
            "is_tribal"=>$_POST['is_tribal'],
            "customer_id"=>(isset($_POST['customer_id']))?$_POST['customer_id']:null,
            "step"=>0
          ];

          $this->view('enrolls/index', $data);
        }else{
          if($customer_id){
            $data=$this->enrollModel->getCustomerData($customer_id);
            //echo $data[0]['order_step'];
            $data[0]['step']=$this->getStep($data[0]['order_step']);
            $this->view('enrolls/index', $data[0]);
          }else{
            //$this->view('enrolls/index');
            redirect('index');
          }
          
        }
  }

  public function submitLanding()
  {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      http_response_code(405);
      echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
      ]);
      return;
    }

    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $ssnLast4 = preg_replace('/\D/', '', $_POST['ssn'] ?? '');
    $email = trim(strtolower($_POST['email'] ?? ''));
    $state = strtoupper(trim($_POST['state'] ?? ''));
    $zipcode = preg_replace('/\D/', '', $_POST['zipcode'] ?? '');
    $phoneNumber = preg_replace('/\D/', '', $_POST['phone'] ?? '');
    $address1 = trim($_POST['address1'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $program = trim($_POST['program'] ?? '');
    $contactMethod = trim($_POST['contact_method'] ?? '');
    $phoneType = trim($_POST['phone_type'] ?? '');
    if ($phoneType === '') {
      $phoneType = 'Android';
    }
    $signatureText = trim($_POST['signature_text'] ?? '');
    $consentDateTime = trim($_POST['consentdatetime'] ?? '');
    $consentInfo = ($_POST['consent_info'] ?? '0') === '1';
    $consentTerms = ($_POST['consent_terms'] ?? '0') === '1';
    $shippingDifferent = ($_POST['shipping_different'] ?? '0') === '1';
    $currentUrl = trim($_POST['current_page_url'] ?? '');
    $shippingAddress1 = trim($_POST['shipping_address1'] ?? '');
    $shippingCity = trim($_POST['shipping_city'] ?? '');
    $shippingState = strtoupper(trim($_POST['shipping_state'] ?? ''));
    $shippingZipcode = preg_replace('/\D/', '', $_POST['shipping_zipcode'] ?? '');
    $identityProof = trim($_POST['identity_proof'] ?? '');
    $benefitProof = trim($_POST['benefit_proof'] ?? '');

    $errors = [];

    if ($firstName === '' || strlen($firstName) < 2) {
      $errors['first_name'] = 'Please enter your first name.';
    }

    if ($lastName === '' || strlen($lastName) < 2) {
      $errors['last_name'] = 'Please enter your last name.';
    }

    if ($dob === '' || strtotime($dob) === false) {
      $errors['dob'] = 'Please select a valid date of birth.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Please enter a valid email address.';
    }

    if (strlen($ssnLast4) !== 4) {
      $errors['ssn'] = 'Please enter the last 4 digits of your SSN.';
    }

    if (strlen($phoneNumber) !== 10) {
      $errors['phone'] = 'Please enter a valid 10-digit phone number.';
    }

    if ($address1 === '') {
      $errors['address1'] = 'Please enter your service address.';
    }

    if ($city === '') {
      $errors['city'] = 'Please enter your city.';
    }

    if (!preg_match('/^[A-Z]{2}$/', $state)) {
      $errors['state'] = 'Please select a valid state.';
    }

    if (strlen($zipcode) !== 5) {
      $errors['zipcode'] = 'ZIP code must be 5 digits.';
    }

    if (!in_array($program, ['100001', '100004', '100002', '100006', '100000', '100014', '100011', '100008', '100010', '100009'], true)) {
      $errors['program'] = 'Please select your program qualification.';
    }

    if (!in_array($contactMethod, ['phone', 'text', 'email'], true)) {
      $errors['contact_method'] = 'Please select your preferred contact method.';
    }

    if (!in_array($phoneType, ['Android', 'iPhone'], true)) {
      $errors['phone_type'] = 'Please select your phone type.';
    }

    if ($signatureText === '' || strlen($signatureText) < 3) {
      $errors['signature_text'] = 'Please type your full name as your electronic signature.';
    }

    if (!$consentInfo) {
      $errors['consent_info'] = 'You must certify that your information is accurate.';
    }

    if (!$consentTerms) {
      $errors['consent_terms'] = 'You must agree to the terms and conditions.';
    }

    if ($consentTerms) {
      if ($consentDateTime === '') {
        $consentDateTime = date('Y-m-d H:i:s');
      } elseif (strtotime($consentDateTime) === false) {
        $errors['consent_terms'] = 'Invalid consent datetime.';
      }
    }

    if ($shippingDifferent) {
      if ($shippingAddress1 === '') {
        $errors['shipping_address1'] = 'Please enter your shipping street address.';
      }

      if ($shippingCity === '') {
        $errors['shipping_city'] = 'Please enter your shipping city.';
      }

      if (!preg_match('/^[A-Z]{2}$/', $shippingState)) {
        $errors['shipping_state'] = 'Please select your shipping state.';
      }

      if (strlen($shippingZipcode) !== 5) {
        $errors['shipping_zipcode'] = 'Shipping ZIP code must be 5 digits.';
      }
    }

    if ($currentUrl !== '' && filter_var($currentUrl, FILTER_VALIDATE_URL) === false) {
      $errors['current_page_url'] = 'Invalid source URL.';
    }

    if (!empty($errors)) {
      http_response_code(422);
      echo json_encode([
        'success' => false,
        'message' => 'Please review the highlighted fields and try again.',
        'errors' => $errors
      ]);
      return;
    }

    parse_str(parse_url($currentUrl, PHP_URL_QUERY) ?? '', $params);
    $utms = json_encode($params);

    $data = [
      'first_name' => ucfirst(strtolower($firstName)),
      'second_name' => ucfirst(strtolower($lastName)),
      'dob' => $dob,
      'ssn' => $ssnLast4,
      'email' => $email,
      'phone_number' => $phoneNumber,
      'address1' => $address1,
      'address2' => trim($_POST['address2'] ?? ''),
      'city' => ucfirst(strtolower($city)),
      'state' => $state,
      'zipcode' => $zipcode,
      'shipping_address1' => $shippingDifferent ? $shippingAddress1 : null,
      'shipping_address2' => $shippingDifferent ? trim($_POST['shipping_address2'] ?? '') : null,
      'shipping_city' => $shippingDifferent ? ucfirst(strtolower($shippingCity)) : null,
      'shipping_state' => $shippingDifferent ? $shippingState : null,
      'shipping_zipcode' => $shippingDifferent ? $shippingZipcode : null,
      'program_benefit' => $program,
      'phone_type' => $phoneType,
      'signature_text' => $signatureText,
      'datetimeConsent' => $consentDateTime,
      'order_step' => 'Landing Form',
      'URL' => $currentUrl,
      'utms' => $utms,
      'company' => 'American Assist',
      'ETC' => 'AMBT'
    ];

    try {
      $lastId = $this->enrollModel->saveData($data, 'lifeline_records');

      if ((int)$lastId > 0) {
        $customerId = $this->genCustomerId($data, $lastId);
        $this->enrollModel->updateCusId($lastId, $customerId, 'lifeline_records');

        $savedDocuments = [];

        if ($identityProof !== '') {
          $idFileStatus = $this->saveFiles($identityProof, $customerId, 'ID');
          if (empty($idFileStatus['status'])) {
            file_put_contents("stepLog.txt", "submitLanding ID document save failed for customer " . $customerId . "\n", FILE_APPEND);
            http_response_code(500);
            echo json_encode([
              'success' => false,
              'message' => 'Enrollment was saved but the Government ID could not be stored. Please try again.'
            ]);
            return;
          }

          $savedDocuments[] = 'ID';
        }

        if ($benefitProof !== '') {
          $pobFileStatus = $this->saveFiles($benefitProof, $customerId, 'POB');
          if (empty($pobFileStatus['status'])) {
            file_put_contents("stepLog.txt", "submitLanding Proof of Benefit save failed for customer " . $customerId . "\n", FILE_APPEND);
            http_response_code(500);
            echo json_encode([
              'success' => false,
              'message' => 'Enrollment was saved but the Proof of Benefit could not be stored. Please try again.'
            ]);
            return;
          }

          $savedDocuments[] = 'POB';
        }

        if (!empty($savedDocuments)) {
          file_put_contents("stepLog.txt", "submitLanding documents saved for customer " . $customerId . ": " . implode(',', $savedDocuments) . "\n", FILE_APPEND);
        }

        echo json_encode([
          'success' => true,
          'message' => 'Enrollment request submitted successfully.',
          'customer_id' => $customerId,
          'redirect_url' => URLROOT . '/pages/thankyou'
        ]);
        return;
      }

      http_response_code(500);
      echo json_encode([
        'success' => false,
        'message' => 'Unable to save your enrollment request at this time.'
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'message' => 'Server error while processing your request.'
      ]);
    }
  }

  public function submitAmbtOrder($customer_id = null)
  {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      http_response_code(405);
      echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
      ]);
      return;
    }

    $customerId = $customer_id ?: ($_POST['customer_id'] ?? null);
    if (!$customerId) {
      http_response_code(422);
      echo json_encode([
        'success' => false,
        'message' => 'customer_id is required.'
      ]);
      return;
    }

    $rows = $this->enrollModel->getCustomerData($customerId);
    if (empty($rows) || empty($rows[0])) {
      http_response_code(404);
      echo json_encode([
        'success' => false,
        'message' => 'Customer not found.'
      ]);
      return;
    }

    $customerData = $rows[0];
    $company = !empty($customerData['ETC']) ? $customerData['ETC'] : 'AMBT';

    $credentials = $this->enrollModel->getCredentials($company);
    if (empty($credentials) || empty($credentials[0])) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'message' => 'Credentials not found for company ' . $company
      ]);
      return;
    }

    $packages = $this->enrollModel->getPackages($company);

    $ambtApi = new AmbtApiHelper();
    $selectedPackage = $ambtApi->selectPackageForCustomer($customerData, $packages);
    $payload = $ambtApi->buildAddSubscriberPayload($customerData, $credentials[0], $selectedPackage);

    if (empty($payload['DOB']) || preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $payload['DOB']) !== 1) {
      http_response_code(422);
      echo json_encode([
        'success' => false,
        'message' => 'Invalid DOB format. Expected mm/dd/yyyy.',
        'customer_id' => $customerId
      ]);
      return;
    }

    $apiResult = $ambtApi->submitAddSubscriberOrder($payload);

    $logData = [
      'customer_id' => $customerId,
      'url' => $apiResult['url'] ?? AMBT_ADD_SUBSCRIBER_URL,
      'request' => $apiResult['request'] ?? json_encode($payload),
      'response' => is_array($apiResult['response'] ?? null) ? json_encode($apiResult['response']) : ($apiResult['response'] ?? ''),
      'title' => 'AddSubscriberOrderWithEBBData'
    ];
    $this->enrollModel->saveData($logData, 'lifeline_apis_log');

    if (($apiResult['status'] ?? 'error') !== 'success') {
      http_response_code(502);
      echo json_encode([
        'success' => false,
        'message' => $apiResult['msg'] ?? 'AMBT API request failed.',
        'payload' => $payload
      ]);
      return;
    }

    $responseBody = is_array($apiResult['response'] ?? null) ? $apiResult['response'] : [];
    $updateData = [
      'customer_id' => $customerId,
      'order_id' => $responseBody['SubscriberOrderID'] ?? null,
      'account' => $responseBody['AccountNumber'] ?? null,
      'acp_status' => $responseBody['NLADStatus'] ?? ($responseBody['Status'] ?? null),
      'status_text' => $responseBody['StatusText'] ?? null,
      'process_status' => 'AddSubscriberOrderWithEBBData API'
    ];
    $this->enrollModel->updateData($updateData, 'lifeline_records');

    $documentUploads = [];
    $orderId = $updateData['order_id'];

    if (!empty($orderId)) {
      $idDoc = $this->enrollModel->getFiles($customerId, 'ID');
      $idToUnavo = is_array($idDoc) ? (int)($idDoc['to_unavo'] ?? 0) : (int)($idDoc->to_unavo ?? 0);
      if (!empty($idDoc) && $idToUnavo !== 1) {
        $documentUploads['ID'] = $this->sendDocuments($customerId, $orderId, 'ID', $company);
      }

      $pobDoc = $this->enrollModel->getFiles($customerId, 'POB');
      $pobToUnavo = is_array($pobDoc) ? (int)($pobDoc['to_unavo'] ?? 0) : (int)($pobDoc->to_unavo ?? 0);
      if (!empty($pobDoc) && $pobToUnavo !== 1) {
        $documentUploads['POB'] = $this->sendDocuments($customerId, $orderId, 'POB', $company);
      }

      if (!empty($documentUploads)) {
        file_put_contents("stepLog.txt", "submitAmbtOrder document upload attempts for customer " . $customerId . ": " . json_encode($documentUploads) . "\n", FILE_APPEND);
      }
    }

    echo json_encode([
      'success' => true,
      'message' => 'AMBT order submitted successfully.',
      'customer_id' => $customerId,
      'order_id' => $updateData['order_id'],
      'documents' => $documentUploads,
      'payload' => $payload,
      'response' => $responseBody
    ]);
  }

  public function getStep($stepName){
    switch($stepName){
      case "Check Coverage":
        $step = 0;
        break;
      case "Demographics":
        $step=1;
        break;
      case "Eligibility & Documents":
        $step=2;
        break;
    }
    return $step;
  }

  public function redirect_(){
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $full_url = $_POST['url'];
      parse_str(parse_url($full_url, PHP_URL_QUERY), $params);
      $utms = json_encode($params);
      $data = [
        "first_name" => trim(ucfirst(strtolower($_POST['firstname']))),
        "second_name" => trim(ucfirst(strtolower($_POST['lastname']))),
        "state" => strtoupper(trim($_POST['state'] ?? '')),
        "zipcode" => $_POST['zipcode'],
        "email" => trim(strtolower($_POST['email'])),
        "url" => $full_url,
        "utms"=>$utms,
        "ETC"=>$_POST['powered'],
        "company"=>"True Wireless"
            
      ];
      $this->enrollModel->saveData($data,'lifeline_records');
      //$this->view('enrolls/redirect',$data);
      //header('location: '. URLROOT . '/'.$page );
      $redirect_url = 'https://gotruewireless.com/Signup.php?email_id='.$data['email'].'&zip_code='.$data['zipcode'];
      header('location:'.$redirect_url);
      //redirect('https://demo-truewireless-web.telgoo5.com/Signup.php?tg_agent_id=&email_id='.$data['email'].'&zip_code='.$data['zipcode']);
    }
  }

  public function ca()
  {
    $data = [
      'title' => 'Contact Us',
      'description' => 'You can contact us through this medium',
      'info' => 'You can contact me with the following details below if you like my program and willing to offer me a contract and work on your project',
      'name' => 'Omonzebaguan Emmanuel',
      'location' => 'Nigeria, Edo State',
      'contact' => '+2348147534847',
      'mail' => 'emmizy2015@gmail.com'
    ];

    $this->view('enrolls/ca', $data);
  }

  public function genCustomerId2($data, $lastId)
  {
    $flfn = ($data['first_name']) ? strtoupper(substr($data['first_name'], 0, 1)) : "X";
    $flsn = ($data['second_name']) ? strtoupper(substr($data['second_name'], 0, 1)) : "X";
    $fdpn = ($data['zipcode']) ? substr($data['phone_number'], 0, 1) : "0";
    $flea = ($data['state']) ? strtoupper(substr($data['email'], 0, 1)) : "X";
    $num = str_pad($lastId, 4, '0', STR_PAD_LEFT);

    $customerId = "G-" . $flfn . $flsn . $fdpn . $flea . $num;

    return $customerId;
  }

  public function genCustomerId($data, $lastId)
  {
    $flfn = ($data['first_name']) ? strtoupper(substr($data['first_name'], 0, 1)) : "X";
    $flsn = ($data['second_name']) ? strtoupper(substr($data['second_name'], 0, 1)) : "X";
    $fdpn = ($data['phone_number']) ? substr($data['phone_number'], 0, 1) : "0";
    $flea = ($data['email']) ? strtoupper(substr($data['email'], 0, 1)) : "X";
    $num = str_pad($lastId, 4, '0', STR_PAD_LEFT);

    $customerId = "TW" . $flfn . $flsn . $fdpn . $flea . $num;

    return $customerId;
  }

public function old_check()
  {
    //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $full_url = $_POST['url'];
      //echo parse_url($full_url, PHP_URL_QUERY);
      parse_str(parse_url($full_url, PHP_URL_QUERY)?? '', $params);
      $utms = json_encode($params);
      $data = [
        "first_name" => trim(ucfirst(strtolower($_POST['firstname']))),
        "second_name" => trim(ucfirst(strtolower($_POST['lastname']))),
        "email" => trim($_POST['email']),
        // "address2" => trim($_POST['addess2']),
        // "city" => trim(ucfirst(strtolower($_POST['city']))),
        "state" => strtoupper(trim($_POST['state'] ?? '')),
        "zipcode" => $_POST['zipcode'],
        "URL" => $full_url,
        "utms"=>$utms,
        "phone_number" => null,
        "order_step" => "Check Coverage",
        "company" => "True Wireless"     
      ];
      //$check = $this->telgooProcessStep($data,'GTW',1);
      $check = $this->enrollModel->getStates('GTW');
      //print_r($check);
      if(in_array($data['state'],$check)){
       // $data['enrollment_id'] = $check['data']['enrollment_id'];
       // $data['is_tribal'] = $check['data']['is_tribal'];
        $data['enrollment_id'] =null;
        $powered="GTW";
      }else{
        //$check = $this->telgooProcessStep($data,'NAL',1);
        $check = $this->enrollModel->getStates('NAL');
        if(in_array($data['state'],$check)){
          //$data['enrollment_id'] = $check['data']['enrollment_id'];
          //$data['is_tribal'] = $check['data']['is_tribal'];
           $data['enrollment_id'] =null;
          $powered="NAL";
        }else{
          $data['enrollment_id'] =null;
          $powered="AMBT";
        }
      }
      $data['ETC'] = $powered;
      $lastId = $this->enrollModel->saveData($data, 'lifeline_records');
          file_put_contents("stepLog.txt", "Checking Prcoess\n", FILE_APPEND);
          if ($lastId > 0) {
            //$data['lastId']=$lastId;
            $customerId = $this->genCustomerId($data, $lastId);
            $data['customer_id'] = $customerId;
            $this->enrollModel->updateCusId($lastId, $customerId, 'lifeline_records');
          }
          
      $data['status'] = "success";
      $data['powered'] = $powered;
      echo json_encode($data);
    }
  }

  public function check()
  {
    //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $full_url = $_POST['url'];
      //echo parse_url($full_url, PHP_URL_QUERY);
      parse_str(parse_url($full_url, PHP_URL_QUERY)?? '', $params);
      $utms = json_encode($params);
      $data = [
        "first_name" => trim(ucfirst(strtolower($_POST['firstname']))),
        "second_name" => trim(ucfirst(strtolower($_POST['lastname']))),
        "email" => trim($_POST['email']),
        "state" => strtoupper(trim($_POST['state'] ?? '')),
        "zipcode" => $_POST['zipcode'],
        "agent" => $_POST['agent'],
        "URL" => $full_url,
        "utms"=>$utms,
        "phone_number" => null,
        "order_step" => "Check Coverage",
        "company" => "True Wireless",   
      ];
      //$check = $this->telgooProcessStep($data,'GTW',1);
      $check = $this->enrollModel->getStates('GTW');
      $zips = $this->enrollModel->getZipcodes('GTW');
      //print_r($check);
      if(in_array($data['state'],$check)){
        $data['enrollment_id'] =null;
        $powered="GTW";
        if($data['state']=="TX"){
          if(in_array($data['zipcode'],$zips)){

            $powered="GTW";
            $zipStatus = "success";
          }else{
            $zipStatus = "fail";
            $powered="NONE";
          }
        }else{
          $zipStatus = "success";
        }
      }else{
        $zipStatus = "fail";
        $powered="NONE";
      }
      $data['ETC'] = $powered;
      $lastId = $this->enrollModel->saveData($data, 'lifeline_records');
          file_put_contents("stepLog.txt", "Checking Prcoess\n", FILE_APPEND);
          if ($lastId > 0) {
            //$data['lastId']=$lastId;
            $customerId = $this->genCustomerId($data, $lastId);
            $data['customer_id'] = $customerId;
            $this->enrollModel->updateCusId($lastId, $customerId, 'lifeline_records');
          }
          
      $data['status'] = $zipStatus;
      $data['powered'] = $powered;
      echo json_encode($data);
    }
  }

  public function savestep1()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      file_put_contents("stepLog.txt", "start first step\n");
      $secret = RECAPTCHA_SECRET;
      $responseKey = $_POST['g-recaptcha-response'];
      $remoteip = $_SERVER['REMOTE_ADDR'];
      
      $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $secret,
            'response' => $responseKey,
            'remoteip' => $remoteip
        ]));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);


      // $context  = stream_context_create($opts);
      // $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
      // $result = json_decode($response);
      //print_r($result);
      if (!$result['success']) {
        $data['status'] = "fail";
        $data['msg']= 'CAPTCHA verification failed';
        file_put_contents("stepLog.txt", "CAPTCHA verification failed\n", FILE_APPEND);
      }else{
        file_put_contents("stepLog.txt", "Captacha succees\n", FILE_APPEND);
        $phonenumber = preg_replace('/[^0-9]/', '', $_POST['phone']);
        $dob = date("m/d/Y", strtotime($_POST['dobM'] . "/" . $_POST['dobD'] . "/" . $_POST['dobY']));
        if(isset($_POST['customer_id'])){
          $customer_id = $_POST['customer_id'];
        }else{
          $customer_id =null;
        };
        $full_url = $_POST['url'];

        //parse_str(parse_url($full_url, PHP_URL_QUERY), $params);
        $params =[];
        $utms = json_encode($params);
        $data = [
          "first_name" => trim(ucfirst(strtolower($_POST['firstname']))),
          "second_name" => trim(ucfirst(strtolower($_POST['lastname']))),
          "ssn" => trim($_POST['ssn']),
          "dob" => $dob,
          "email" => trim(strtolower($_POST['email'])),
          "phone_number" => $phonenumber,
          "address1" => trim($_POST['address1']),
          "address2" => trim($_POST['addess2']),
          "city" => trim(ucfirst(strtolower($_POST['city']))),
          "state" => $_POST['state'],
          "zipcode" => $_POST['zipcode'],
          "typeAddress" => $_POST['typeAddress'],
          "shipping_address1" => (isset($_POST['shipaddress1']) ? $_POST['shipaddress1'] : null),
          "shipping_address2" => (isset($_POST['shipaddess2'])) ? $_POST['shipaddess2'] : null,
          "shipping_city" => (isset($_POST['shipcity'])) ? $_POST['shipcity'] : null,
          "shipping_state" => (isset($_POST['shipstate'])) ? $_POST['shipstate'] : null,
          "shipping_zipcode" => (isset($_POST['shipzipcode'])) ? $_POST['shipzipcode'] : null,
          "order_step" => "Demographics",
          "URL" => $full_url,
          "company" => $_POST['company'],
          "utms"=>$utms,
          "utm_source" => (isset($_POST['utm_source'])) ? $_POST['utm_source'] : null,
          "utm_medium" => (isset($_POST['utm_medium'])) ? $_POST['utm_medium'] : null,
          "utm_campaign" => (isset($_POST['utm_campaign'])) ? $_POST['utm_campaign'] : null,
          "utm_content" => (isset($_POST['utm_content'])) ? $_POST['utm_content'] : null,
          "match_type" => (isset($_POST['match_type'])) ? $_POST['match_type'] : null,
          "utm_adgroup" => (isset($_POST['utm_adgroup'])) ? $_POST['utm_adgroup'] : null,
          "gclid" => (isset($_POST['gclid'])) ? $_POST['gclid'] : null,
          "fbclid" => (isset($_POST['fbclid'])) ? $_POST['fbclid'] : null,
          "ETC"=>$_POST['powered'],
          "order_id"=>$_POST['enrollment_id'],
          "enrollment_id"=>$_POST['enrollment_id'],
          "tribal"=>$_POST['tribal']
        ];
        if($customer_id){
          $data['customer_id']=$customer_id;
          
          $this->enrollModel->updateData($data, 'lifeline_records');
            //$data['customer_id'] = $customerId;
            $data['status'] = "success";
            $data['action']="update";
            file_put_contents("stepLog.txt", "Update LEad\n", FILE_APPEND);
        }else{
          $lastId = $this->enrollModel->saveData($data, 'lifeline_records');
          file_put_contents("stepLog.txt", "New Lead Saved\n", FILE_APPEND);
          if ($lastId > 0) {
            //$data['lastId']=$lastId;
            $customerId = $this->genCustomerId($data, $lastId);
            $data['customer_id'] = $customerId;
            $this->enrollModel->updateCusId($lastId, $customerId, 'lifeline_records');
            if($data['ETC']=="AMBT"){
              $data['action']="insert";
              $data['status'] = "success";
              file_put_contents("stepLog.txt", "Start AMBT Process \n", FILE_APPEND);
            }else{
              //$addressValidation = $this->telgooProcessStep($data,$data['ETC'],2);
              file_put_contents("stepLog.txt", "Telgoo Enrollment\n", FILE_APPEND);
              $addressValidation['msg']="Success";
              if($addressValidation['msg']=="Success"){
                file_put_contents("stepLog.txt", "Telgoo Enrollment Success", FILE_APPEND);
                $data['action']="insert";
                $data['status'] = "success";
              }else{
                $data['status'] = "fail";
              }
            }
            
          } else {
            $data['status'] = "fail";
          }
        }

        
        
      }
      //print_r($data);
      echo json_encode($data);
    }
  }

  public function savestep2()
  {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      //print_r($_POST);
      $data = [
        "program_benefit" => $_POST['eligibility_program'],
        "nverification_number" => trim($_POST['nv_application_id']),
        "customer_id" => $_POST['customer_id'],
        "current_benefits" => $_POST['current_benefits'],
        "phone_type" => $_POST['type_phone'],
        "order_step" => "Eligibility & Documents"

      ];

      $this->enrollModel->updateData($data, 'lifeline_records');
      file_put_contents("stepLog.txt", "Update New Data", FILE_APPEND);
      $fileData = [
        'statusFile' => true
      ];
      $savedDocuments = [];

      if (!empty($_POST['govId'])) {
        $base64_string = $_POST['govId'];
        $customer_id = $data['customer_id'];
        $filepath = saveBase64File($base64_string, $customer_id, "ID");
        file_put_contents("stepLog.txt", "ID File SAved\n", FILE_APPEND);
        $fileData = [
          "customer_id" => $customer_id,
          "filepath" => $filepath,
          "type_doc" => "ID"
        ];
        $idSaved = $this->enrollModel->saveData($fileData, 'lifeline_documents');
        if (!$idSaved) {
          $fileData['statusFile'] = false;
        } else {
          $savedDocuments[] = 'ID';
        }
      }

      if (!empty($_POST['pob'])) {
        $base64_string_pob= $_POST['pob'];
        $customer_id = $data['customer_id'];
        $filepath2 = saveBase64File($base64_string_pob, $customer_id, "POB");
        file_put_contents("stepLog.txt", "POB Faile Saved\n", FILE_APPEND);
        $pobData = [
          "customer_id" => $customer_id,
          "filepath" => $filepath2,
          "type_doc" => "POB"
        ];
        $pobSaved = $this->enrollModel->saveData($pobData, 'lifeline_documents');
        if (!$pobSaved) {
          $fileData['statusFile'] = false;
        } else {
          $savedDocuments[] = 'POB';
        }
      }

      if (!empty($savedDocuments)) {
        file_put_contents("stepLog.txt", "Step 2 complete: optional document log persisted for customer " . $data['customer_id'] . ": " . implode(',', $savedDocuments) . "\n", FILE_APPEND);
      }


      echo json_encode($fileData);
    }
  }

  public function savestep3()
  {
      // Inicia sesi贸n solo si no ha sido iniciada a煤n
  if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
  }

    // Prevent multiple submissions within a short time
    if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted'] === true) {
        echo json_encode(['error' => 'Form already submitted.']);
        exit;
    }

    // Mark the form as submitted
    $_SESSION['form_submitted'] = true;

    try {
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //print_r($_POST);
        // Determine order_step based on POST values
        $terms = isset($_POST['terms']) ? $_POST['terms'] : '';
        $sms = isset($_POST['sms']) ? $_POST['sms'] : '';
        $know = isset($_POST['know']) ? $_POST['know'] : '';
        if ($terms === "Yes" && $sms === "Yes" && $know === "Yes") {
            $order_step = "Agreements & Consent";
        } else {
            $order_step = "Agree & Sign";
        }

        $data = [
          "signature_text" => trim($_POST['signaturename']),
          "datetimeConsent" => $_POST['datetimeconsent'],
          "agree_terms" => $terms,
          "agree_sms" => $sms,
          "agree_pii" => $know,
          "customer_id" => $_POST['customer_id'],
          "order_status" => "New",
          "order_step" => $order_step
        ];

        $this->enrollModel->updateData($data, 'lifeline_records');
        file_put_contents("stepLog.txt", "Step 3 Saved\n", FILE_APPEND);
        $initialData = [
          "initials1" => trim(strtoupper($_POST['initials_1'])),
          "initials2" => trim(strtoupper($_POST['initials_2'])),
          "initials3" => trim(strtoupper($_POST['initials_3'])),
          "initials4" => trim(strtoupper($_POST['initials_4'])),
          "initials5" => trim(strtoupper($_POST['initials_5'])),
          "initials6" => trim(strtoupper($_POST['initials_6'])),
          "initials7" => trim(strtoupper($_POST['initials_7'])),
          "initials8" => trim(strtoupper($_POST['initials_8'])),
          "initials9" => trim(strtoupper($_POST['initials_9'])),
          "customer_id" => $data['customer_id']
        ];

        $initialData['statusfinale'] = ($this->enrollModel->saveData($initialData, 'lifeline_agreement')) ? true : false;
        file_put_contents("stepLog.txt", "Agreement Data Saved\n", FILE_APPEND);
        $row2 = $this->enrollModel->getCustomerData($data['customer_id']);
        //echo json_encode($initialData);
        $etc=$row2[0]['ETC'];
        file_put_contents("stepLog.txt", "ETC:".$etc."\n", FILE_APPEND);
        if($etc=="GTW" or $etc=="NAL"){
          //$nlad = $this->telgooProcessStep($row2[0],$etc,4);
          file_put_contents("stepLog.txt", "Telgoo Step 4 \n".json_encode($nlad), FILE_APPEND);
          $isTribal=$row2[0]['tribal']=="Y"?1:0;
          //$plan=$this->enrollModel->getTGPackages($row2[0]['state'],$etc,$isTribal);
          //$planId=$this->getTgPackages($row2[0]['zipcode'],$etc,$row2[0]['tribal'],$data['customer_id']);
          //$row2[0]['plan_id']=$planId;
          //$customer = $this->telgooProcessStep($row2[0], $etc,6);
          $customer['msg']="Success";
          $customer['data']="Info Saved Successfully";
          file_put_contents("stepLog.txt", "Telgoo Step 6\n", FILE_APPEND);
          //$this->uploadTGDocs($data['customer_id'],$row2[0]['order_id'],$etc);
          file_put_contents("stepLog.txt", "Telgoo Upload Docs\n", FILE_APPEND);
          file_put_contents("stepLog.txt", json_encode($customer)."\n", FILE_APPEND);
          if($customer['msg']=="Success"){
              $acpStatus = json_encode($customer['data']);
              file_put_contents("stepLog.txt", json_encode($customer['data'])."\n", FILE_APPEND);
              
          }else{
            $acpStatus = json_encode($customer['errors']);
             file_put_contents("stepLog.txt", json_encode($customer['errors'])."\n", FILE_APPEND);
          }
         
          $data['acp_status']=$acpStatus;
          $saveResponse = [
            "acp_status" => $acpStatus,
            "customer_id" => $data['customer_id']
          ];
           file_put_contents("stepLog.txt", json_encode($saveResponse)."\n", FILE_APPEND);
           $this->enrollModel->updateData($saveResponse, 'lifeline_records');
        }else{
          // $customerData = $this->enrollModel->getCustomerData($data['customer_id']);
          //$this->APIService = new APIprocess();
          //$result = $this->APIService->shockwaveProcess($data['customer_id'], $this->enrollModel);
          //$row2[0]['order_id']= $result['order_id'];
          $result=[
                  "status"=>"success",
                  "msg"=>"Order Submitted Successfully"
          ];
          $acpStatus="Order Submitted Successfully";
        }
        
        $row2[0]['acp_status'] = $acpStatus;
        
        $this->sendNotification($row2[0]);

        echo json_encode($result);
      }
    } catch (Exception $e) {
    echo json_encode(['status' => 'error','msg' => 'An error occurred: ' . $e->getMessage()]);
    } finally {
        // Reset the form submitted flag after a short period or once the request is done
        $_SESSION['form_submitted'] = false;
    }
  }

  public function tryagain($customer_id){
    $this->APIService = new APIprocess();
        $result = $this->APIService->shockwaveProcess($customer_id, $this->enrollModel);

        //$row2 = $this->enrollModel->getCustomerData($customer_id);
        //$this->sendNotification($row2[0]);

        echo json_encode($result);
  }

  public function getdocuments($customer_id=null){
    //echo $customer_id;
    if(!empty($customer_id)){
      $row2 = $this->enrollModel->getCustomerData($customer_id);
      //print_r($row2);
      if($row2){
          $data = [
          "customer_id"=>$customer_id,
          "first_name"=>$row2[0]['first_name'],
          "last_name"=>$row2[0]['second_name'],
        ];
      }else{
        $data = [
          "customer_id"=>0,
          "msg"=>"Customer ID not found",
        ];
      }
    }else{ 
      $data = [
        "customer_id"=>0,
        "msg"=>"Customer ID is missing",
      ];
    }
    
    $this->view('enrolls/documents',$data);
  }

  public function saveFiles($base64_string,$customer_id,$fileType){
        $filepath = saveBase64File($base64_string, $customer_id,$fileType);
        $fileData = [
          "customer_id" => $customer_id,
          "filepath" => $filepath,
          "type_doc" => $fileType
        ];
      $docId = $this->enrollModel->saveData($fileData, 'lifeline_documents');
      $fileData['id_lifeline_doc'] = $docId;
      $fileData['status'] = ((int)$docId > 0);
        return $fileData;
  }

  public function saveStoredDoc(){
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $data = json_decode(file_get_contents('php://input'), true);

      
      if (!isset($data['base64data']) || !isset($data['filename'])) {
          echo json_encode(['success' => false, 'message' => 'Missing data']);
          exit;
      }

      // Remove the base64 header part
      $base64 = $data['base64data'];
      $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
      $base64 = str_replace(' ', '+', $base64);
      $customer_id=$data['customer_id'];
      $imageData = base64_decode($base64);

      $savePath = '../public/uploads/'.$customer_id.'/' . basename($data['filename']);
      file_put_contents($savePath, $imageData);

      echo json_encode(['success' => true, 'message' => 'Image saved', 'path' => $savePath]);
    }
  }

  public function saveDocuments(){
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $data = json_decode(file_get_contents('php://input'), true);
      //echo $data['order_id'];
      
      //$customerData = $this->enrollModel->getCustomerbyOrderId(trim($data['order_id']));
      //print_r($customerData);
      // $customerId=$data['customer_id'];
      
      // if($customerData){
      //   $idFile=$this->saveFiles($data['identity_proof'],$customerId,"ID");
      //   if($idFile['status']){
      //     $data['idStatusApi']=$this->sendDocuments($customerId,$data['order_id'],"ID");
      //   }
      //   $pobFile=$this->saveFiles($data['benefit_proof'],$customerId,"POB");
      //   if($pobFile['status']){
      //     $data['pobStatusApi']=$this->sendDocuments($customerId,$data['order_id'],"POB");
      //   }
      // }else{
      if($data['identity_proof']){
        $data['idFileStatus']=$this->saveFiles($data['identity_proof'],$data['customer_id'],"ID");
        $data['idFileName']=basename($data['idFileStatus']['filepath']);
      }
        
      if($data['benefit_proof']){
        $data['pobFileStatus']=$this->saveFiles($data['benefit_proof'],$data['customer_id'],"POB");
        $data['pobFileName']=basename($data['pobFileStatus']['filepath']);
      }

      $customerRows = $this->enrollModel->getCustomerData($data['customer_id']);
      if (!empty($customerRows) && !empty($customerRows[0])) {
        $customerData = $customerRows[0];
        $company = !empty($customerData['ETC']) ? $customerData['ETC'] : 'AMBT';
        $orderId = $customerData['order_id'] ?? null;

        if (!empty($orderId)) {
          $credentials = $this->enrollModel->getCredentials($company);

          if (!empty($credentials) && !empty($credentials[0])) {
            $ambtApi = new AmbtApiHelper();

            if (!empty($data['identity_proof']) && !empty($data['idFileStatus']['status'])) {
              $idUploadApi = $ambtApi->uploadDocument(
                $credentials[0],
                $orderId,
                $data['idFileName'] ?? ('ID_' . $data['customer_id']),
                $data['identity_proof'],
                '100001'
              );

              $this->enrollModel->saveData([
                'customer_id' => $data['customer_id'],
                'url' => $idUploadApi['url'] ?? AMBT_UPLOAD_DOCUMENT_URL,
                'request' => $idUploadApi['request'] ?? '',
                'response' => is_array($idUploadApi['response'] ?? null) ? json_encode($idUploadApi['response']) : ($idUploadApi['response'] ?? ''),
                'title' => 'UploadDocumentAPI_100001'
              ], 'lifeline_apis_log');

              if (($idUploadApi['status'] ?? 'error') === 'success' && !empty($data['idFileStatus']['id_lifeline_doc'])) {
                $this->enrollModel->updateDocStatus([
                  'id_lifeline_doc' => $data['idFileStatus']['id_lifeline_doc'],
                  'to_unavo' => 1
                ], 'lifeline_documents');
              }
            }

            if (!empty($data['benefit_proof']) && !empty($data['pobFileStatus']['status'])) {
              $pobUploadApi = $ambtApi->uploadDocument(
                $credentials[0],
                $orderId,
                $data['pobFileName'] ?? ('POB_' . $data['customer_id']),
                $data['benefit_proof'],
                '100000'
              );

              $this->enrollModel->saveData([
                'customer_id' => $data['customer_id'],
                'url' => $pobUploadApi['url'] ?? AMBT_UPLOAD_DOCUMENT_URL,
                'request' => $pobUploadApi['request'] ?? '',
                'response' => is_array($pobUploadApi['response'] ?? null) ? json_encode($pobUploadApi['response']) : ($pobUploadApi['response'] ?? ''),
                'title' => 'UploadDocumentAPI_100000'
              ], 'lifeline_apis_log');

              if (($pobUploadApi['status'] ?? 'error') === 'success' && !empty($data['pobFileStatus']['id_lifeline_doc'])) {
                $this->enrollModel->updateDocStatus([
                  'id_lifeline_doc' => $data['pobFileStatus']['id_lifeline_doc'],
                  'to_unavo' => 1
                ], 'lifeline_documents');
              }
            }
          }
        }
      }

      //}
      $this->sendDocumentsEmail($data);
      $data["message"]="Files Upload Susccesfully";
      $data["success"]=true;
      $data["redirect_url"]=URLROOT.'/pages/thankyou/'.$data['customer_id'];
      $updatedata = [
        "customer_id"=>$data['customer_id'],
        "order_status"=>"Docs Received"
      ];
      $this->enrollModel->updateData($updatedata, 'lifeline_records');
      echo json_encode($data);
    }
  }

  public function sendDocumentsEmail($data){
    //$_SERVER['DOCUMENT_ROOT'].'/public/uploads/'
    $to = "xneriox@gmail.com";
    $subject = "Document Submission for Customer ID: " . $data['customer_id'];  
    $message = "Customer ID: " . $data['customer_id'] . "\n";
    //$message .= "Customer Name: " . $data['first_name']." ".$data['second_name'] . "\n";
    $message .= "Documents have been submitted successfully.\n";  
    $mailer = new PHPMailer_Lib();
    $mail = $mailer->load();
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = SMTP_HOST;            // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = SMTP_USERNAME;                     // SMTP username
    $mail->Password   = SMTP_PASSWORD;                               // SMTP password
    $mail->SMTPSecure = SMTP_ENCRYPTION;                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = SMTP_PORT;  
    $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME_DOCS);
    $mail->addAddress(MAIL_DOCS_TO);
    //$mail->addCC('jparker@galaxydistribution.com'); 
    //$mail->addCC('currutia44@gmail.com');      // Add a recipient
    if (!empty(MAIL_DOCS_BCC)) {
      $mail->addBCC(MAIL_DOCS_BCC);
    }
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = nl2br($message);
    $files = [
        $data['idFileName'],
        $data['pobFileName']
    ];

    foreach ($files as $file) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/'. $data['customer_id'] . '/' . $file;
        file_put_contents("stepLog.txt",  $path."\n", FILE_APPEND);
        if (file_exists($path)) {
            $mail->addAttachment($path);
        }
    }
    $mail->send();
  }

  public function sendDocuments($customerId,$orderId,$fileType,$company){
    $this->APIService = new APIprocess();
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
    $fileData = $this->APIService->getSavedfiles($customerId,$this->enrollModel,$fileType);
    $credentials=$this->enrollModel->getCredentials($company);
    if($orderId>0){
      if($fileData){
                // Convert URL-based filepath to absolute disk path for reliable reading
                $diskFilePath = rtrim(APPROOT, '/\\') . '/' . ltrim(str_replace(URLROOT, '', $fileData['filepath']), '/\\');
                $imageData = file_get_contents($diskFilePath);
                $filename = basename($fileData['filepath']);
                // Encode the binary data to base64
                $base64 = base64_encode($imageData);
                $upload=UploadDocument($credentials[0], $orderId, $filename, $base64, $fileID, $company);
                if($upload['status']=="success"){
                  $saveCreateIDLog=[
                    "customer_id"=>$customerId,
                    "url"=>$upload['url'],
                    "request"=>$upload['request'],
                    "response"=>json_encode($upload['response']),
                    "title"=>$upload['title']
                  ];
                  $this->enrollModel->saveData($saveCreateIDLog,'lifeline_apis_log');
                  $fileupdate = ["id_lifeline_doc"=>$fileData['id_lifeline_doc'],"to_unavo"=>1];
                 // $enrollModel->saveData($fileId,'lifeline_documents');
                 $this->enrollModel->updateDocStatus($fileupdate ,'lifeline_documents');
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
                  $this->enrollModel->saveData($saveCreateIDLog,'lifeline_apis_log');
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

  public function compress($customer_id,$typeFile){
    $this->APIService = new APIprocess();
  $fileData = $this->APIService->getSavedfiles($customer_id,$this->enrollModel,$typeFile);
  //$imageData = file_get_contents($fileData['filepath']);
  $filename = basename($fileData['filepath']);
    $data=[
      "imageFile"=>$fileData['filepath'],
      "filename"=>$filename,
      "customer_id"=>$customer_id
    ];
    //print_r($data);

    $this->view("enrolls/compress",$data);
  }

  public function uploadTGDocs($customer_id,$enrollmentId,$etc){
    $files = $this->enrollModel->getAllFiles($customer_id);
    file_put_contents("stepLog.txt", json_encode($files)."\n", FILE_APPEND);
    //$folder = $_SERVER['DOCUMENT_ROOT'].'/TruewirelessLifeline/public/uploads/';
    $folder = $_SERVER['DOCUMENT_ROOT'].'/public/uploads/';
    file_put_contents("stepLog.txt", $folder."\n", FILE_APPEND);
    //echo __DIR__."/../";
    $priority = [
      'POB' => 1, // GA_PROOF
      'ID'  => 2  // ID_PROOF
    ];
    usort($files, function($a, $b) use ($priority) {
        $aPriority = $priority[$a['type_doc']] ?? 99;
        $bPriority = $priority[$b['type_doc']] ?? 99;

        return $aPriority <=> $bPriority;
    });
    if($files){
      foreach($files as $file){
        if($file['type_doc'] == "POB"){
          $imageData = $this->curl_get_file_contents($file['filepath']);
          $filename = basename($file['filepath']);
          $filePath = $folder.$customer_id.'/'. $filename;
          file_put_contents("stepLog.txt", $filePath."\n", FILE_APPEND);
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $mimeType = finfo_file($finfo, $filePath);
          finfo_close($finfo);
          file_put_contents("stepLog.txt", $mimeType."\n", FILE_APPEND);
          //$filename = basename($fileData['filepath']);
          $base64Img = base64_encode($imageData);
          $saveFiles = [
            "enrollment_id" => $enrollmentId,
            "proof_file" => 'data:' . $mimeType . ';base64,' . $base64Img,
            "proof_category" => "GA_PROOF",
            "customer_id" => $customer_id
          ];
          $customer = $this->telgooProcessStep($saveFiles, $etc,7);
          
        }else if($file['type_doc'] == "ID"){
          $imageData = $this->curl_get_file_contents($file['filepath']);
          $filename = basename($file['filepath']);
          $filePath = $folder.$customer_id.'/'. $filename;
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $mimeType = finfo_file($finfo, $filePath);
          finfo_close($finfo);
          //$filename = basename($fileData['filepath']);
          $base64Img = base64_encode($imageData);
          $saveFiles = [
            "enrollment_id" => $enrollmentId,
            "proof_file" => 'data:' . $mimeType . ';base64,' . $base64Img,
            "proof_category" => "ID_PROOF",
            "customer_id" => $customer_id
          ];
          $customer = $this->telgooProcessStep($saveFiles, $etc,7);
        }
      }
    }
    // echo "<pre>";
    // echo json_encode($saveFiles);
    // echo "<br>";
    // echo "<img src='data:" . $mimeType . ";base64," . $base64Img . "' alt='Compressed Image'>";
    //echo json_encode($customer);
  }

  public function getTgPackages($zipcode,$etc,$is_tribal,$customer_id){
    $data = [
      "zipcode" => $zipcode,
      "tribal" => $is_tribal,
      "customer_id" => $customer_id
    ];
    $plans = $this->telgooProcessStep($data, $etc,5);
    $planId = null;
    $searchCode = ($is_tribal == "Y")? 179:178;

    foreach ($plans['data'] as $item) {
      if ($item['plan_code'] == $searchCode) {
        $planId = $item['plan_id'];
        break;
      }
    }
    return $planId;
  }

  public function test($zipcode,$etc){
    $data = [
      "zipcode"=>$zipcode,
      "tribal"=>"N",
      "customer_id"=>12345
    ];
    $plans = $this->telgooProcessStep($data, $etc,5);
    $planId = null;
    $searchCode = 178;

    foreach ($plans['data'] as $item) {
      if ($item['plan_code'] == $searchCode) {
        $planId = $item['plan_id'];
        break;
      }
    }
    echo $planId;
  }

  public function setApiCredentials($etc,$data){
    $customer_id = isset($data['customer_id']) ? $data['customer_id'] : 0;
    switch($etc){
      case "GTW":
        $this->TG5Api = new LifelineApiHandler(
        "https://www.vcareapi.com:8080",
        "GoTrueWirelesstwlifeline",
        "GoTrueWirelesstwlifelineug24User",
        "GoTrueWi595jst3uf735",
        "819015401243",
        $this->enrollModel,
        $customer_id
        );
        break;
      case "NAL":
        $this->TG5Api = new LifelineApiHandler(
        "https://www.vcareapi.com:8080",
        "NorthAmericanLocalClient",
        "NorthAmericanLocalClienth2s5User",
        "NorthAme6cq298nf2uv7",
        "095361035869",
        $this->enrollModel,
        $customer_id
        );
        break;
      default:
        $this->TG5Api = new LifelineApiHandler(
        "https://www.vcareapi.com:8080",
        "Demo-GoTrueWirelessNRT",
        "Demo-GoTrueWirelessNRTUser",
        "Demo-GoTrueWi674f2b9w87dg",
        "Demo-933516178627",
        $this->enrollModel,
        $customer_id
        );
        break;
    }
  }

  public function telgooProcessStep($data,$etc,$step){
    $this->setApiCredentials($etc,$data);
    $this->TG5Api->authenticate(); // get token first
    //echo "authenticate<br>";
    switch($step){
      case 1:
        //Step 1 Check_service_availability
        $response = $this->TG5Api->checkServiceAvailability([
                "action"=> "check_service_availability",
                "zip_code"=> $data['zipcode'],
                "enrollment_type"=> "LIFELINE",
                "is_enrollment"=> "Y",
                "agent_id"=> "ewebsiteapi",
                "external_transaction_id"=> "",
                "source"=> "WEBSITE"
        ]);
        //echo "Check Service Availability<br>";
        break;
      case 2:
        //Step 2 address_validation
        $response = $this->TG5Api->addressValidation([
        "enrollment_id"=> $data['enrollment_id'],
        "first_name"=> $data['first_name'],
        "middle_name"=> '',
        "last_name"=> $data['second_name'],
        "address_one"=> $data['address1'],
        "address_two"=> $data['address2'],
        "city"=> $data['city'],
        "state"=> $data['state'],
        "zip_code"=> $data['zipcode'],
        "is_temp"=> "N",
        "ssn"=> $data['ssn'],
        "dob"=> date("Y-m-d", strtotime($data['dob'])),
        "mailing_address_one"=> ($data['shipping_address1']) ? $data['shipping_address1'] : "",
        "mailing_address_two"=> ($data['shipping_address2']) ? $data['shipping_address2'] : "",
        "mailing_city"=> ($data['shipping_city']) ? $data['shipping_city'] : "",
        "mailing_state"=> ($data['shipping_state']) ? $data['shipping_state'] : "",
        "mailing_zip"=> ($data['shipping_zipcode']) ? $data['shipping_zipcode'] : "",
        "beneficiary_suffix"=> "",
        "beneficiary_first_name"=> "",
        "beneficiary_middle_name"=> "",
        "beneficiary_last_name"=> " ",
        "beneficiary_dob"=> "",
        "beneficiary_ssn"=> "",
        "action"=> "address_validation",
        "external_transaction_id"=> "",
        "agent_id"=> "ewebsiteapi",
        "source"=> "WEBSITE",
        "lifeline_enrollment_type"=> "LIFELINE",
        "initial_choosen_enrollment_type"=> "LIFELINE"
      ]);
        break;
      case 3:
        //Step 3 Get Program List
        $response = $this->TG5Api->programsIncomeList([
              "zip_code"=> $data['zipcode'],
              "action"=> "programs_income_list",
              "is_tribal"=> $data['tribal'],
              "agent_id"=> "ewebsiteapi",
              "source"=> "WEBSITE",
              "initial_choosen_enrollment_type"=> "LIFELINE ",
              "external_transaction_id"=> "",
              "lifeline_enrollment_type"=> "LIFELINE"
        ]);
        break;
      case 4:
        //nladd
        $response = $this->TG5Api->eligibilityCheck([
              "enrollment_id"=> $data['enrollment_id'],
              "first_name"=> $data['first_name'],
              "middle_name"=> $data['middle_name'],
              "last_name"=> $data['second_name'],
              "address_one"=> $data['address1'],
              "address_two"=> $data['address2'],
              "beneficiary_first_name"=> $data['bqp_firstname']??"",
              "beneficiary_middle_name"=> $data['bqp_middlename']??"",
              "beneficiary_last_name"=> $data['bqp_lastname']??"",
              "beneficiary_dob"=> $data['bqp_dob']??"",
              "beneficiary_ssn"=> $data['bqp_ssn']??"",
              "beneficiary_tribal_id"=> $data['bqp_tribalid']??"",
              "city"=> $data['city'],
              "state"=> $data['state'],
              "zip_code"=> $data['zipcode'],
              "tribal_id"=> $data['tribal_id']??"",
              "ssn"=> $data['ssn'],
              "dob"=> date("Y-m-d", strtotime($data['dob'])),
              "program_code"=> [
                $data['program_benefit']
              ],
              "no_of_household"=> "",
              "action"=> "eligibility_check",
              "agent_id"=> "ewebsiteapi",
              "source"=> "WEBSITE",
              "carrier_url"=> "",
              "public_housing_code"=> "",
              "external_transaction_id"=> ""
        ]);
        break;
      case 5:
        $response = $this->TG5Api->planList([
            "action"=> "plan_list",
            "zip_code"=> $data['zipcode'],
            "enrollment_type"=> "LIFELINE",
            "agent_id"=> "ewebsiteapi",
            "external_transaction_id"=> "",
            "source"=> "WEBSITE",
            "tribal"=> $data['tribal'],
        ]);
        break;
      case 6:
        $response = $this->TG5Api->createLifelineCustomer([
        "enrollment_id"=>  $data['enrollment_id'],
        "customer_suffix"=> "",
        "first_name"=> $data['first_name'],
        "middle_name"=> $data['middle_name'],
        "last_name"=> $data['second_name'],
        "primary_telephone_number"=> $data['phone_number'],
        "service_address_one"=> $data['address1'],
        "service_address_two"=> $data['address2'],
        "service_city"=> $data['city'],
        "service_state"=> $data['state'],
        "service_zip"=> $data['zipcode'],
        "mailing_address_one"=> "",
        "mailing_address_two"=> "",
        "mailing_city"=> "",
        "mailing_state"=> "",
        "mailing_zip"=> "",
        "ssn"=> $data['ssn'],
        "dob"=> date("Y-m-d", strtotime($data['dob'])),
        "beneficiary_suffix"=> "",
        "beneficiary_first_name"=> "",
        "beneficiary_middle_name"=> "",
        "beneficiary_last_name"=> " ",
        "beneficiary_dob"=> "",
        "beneficiary_ssn"=> "",
        "beneficiary_tribal_id"=> "",
        "email"=> $data['email'],
        "plan_id"=> strval($data['plan_id']),
        "best_way_to_reach"=> [
            "email"
        ],
        "program_code"=> [
            $data['program_benefit']
        ],
        "address_type"=> "P",
        "household_adult"=> "Y",
        "household_share"=> "",
        "household_lifeline"=> "N",
        "enrollment_type"=> "SHIPMENT",
        "is_ebb_qualify"=> "Y",
        "consent_check"=> "Y",
        "ebb_verify_type"=> "NV",
        "initial_choosen_enrollment_type"=> "LIFELINE",
        "lifeline_enrollment_type"=> "LIFELINE",
        "action"=> "create_lifeline_customer",
        "agent_id"=> "ewebsiteapi",
        "external_transaction_id"=> "",
        "source"=> "WEBSITE"
        ]);
        break;
      case 7:
        $response = $this->TG5Api->uploadDocuments([
            "enrollment_id"=> $data['enrollment_id'],
            "proof_file"=> $data['proof_file'],
            "action"=> "upload_proof",
            "proof_category"=> $data['proof_category'],
            "category_id"=> "",
            "agent_id"=> "ewebsiteapi",
            "external_transaction_id"=> "",
            "source"=> "WEBSITE"
        ]);
        break;
    }


    return $response;
  }

  public function telgooProcess($data,$etc){
    try {
    
    $this->setApiCredentials($etc,$data);
    $this->TG5Api->authenticate(); // get token first
    echo "authenticate<br>";

    $availability = $this->TG5Api->checkServiceAvailability([
            "action"=> "check_service_availability",
            "zip_code"=> $data['zipcode'],
            "enrollment_type"=> "LIFELINE",
            "is_enrollment"=> "Y",
            "agent_id"=> "ewebsiteapi",
            "external_transaction_id"=> "",
            "source"=> "WEBSITE"
    ]);
    echo "Check Service Availability<br>";
    if($availability['msg']=="Success"){
      echo "Service Available<br>";
      echo $enrollmentId = $availability['data']['enrollment_id'];
      echo "<br>";
      $this->TG5Api->authenticate();
      $addressValidation = $this->TG5Api->addressValidation([
        "enrollment_id"=> $enrollmentId,
        "first_name"=> $data['first_name'],
        "middle_name"=> $data['middle_name'],
        "last_name"=> $data['second_name'],
        "address_one"=> $data['address1'],
        "address_two"=> $data['address2'],
        "city"=> $data['city'],
        "state"=> $data['state'],
        "zip_code"=> $data['zipcode'],
        "is_temp"=> "N",
        "ssn"=> $data['ssn'],
        "dob"=> $data['dob'],
        "mailing_address_one"=> ($data['shipping_address1']) ? $data['shipping_address1'] : "",
        "mailing_address_two"=> ($data['shipping_address2']) ? $data['shipping_address2'] : "",
        "mailing_city"=> ($data['shipping_city']) ? $data['shipping_city'] : "",
        "mailing_state"=> ($data['shipping_state']) ? $data['shipping_state'] : "",
        "mailing_zip"=> ($data['shipping_zipcode']) ? $data['shipping_zipcode'] : "",
        "beneficiary_suffix"=> "",
        "beneficiary_first_name"=> "",
        "beneficiary_middle_name"=> "",
        "beneficiary_last_name"=> " ",
        "beneficiary_dob"=> "",
        "beneficiary_ssn"=> "",
        "action"=> "address_validation",
        "external_transaction_id"=> "",
        "agent_id"=> "ewebsiteapi",
        "source"=> "WEBSITE",
        "lifeline_enrollment_type"=> "LIFELINE",
        "initial_choosen_enrollment_type"=> "LIFELINE"
      ]);
      echo "Address Validation:<br>";
      if($addressValidation['msg']=="Success"){
        $this->TG5Api->authenticate();
        $programlist = $this->TG5Api->programsIncomeList([
              "zip_code"=> $data['zipcode'],
              "action"=> "programs_income_list",
              "is_tribal"=> "N",
              "agent_id"=> "ewebsiteapi",
              "source"=> "WEBSITE",
              "initial_choosen_enrollment_type"=> "LIFELINE ",
              "external_transaction_id"=> "",
              "lifeline_enrollment_type"=> "LIFELINE"
        ]);
        echo "Program List:<br><pre>";
        print_r($programlist);
        echo "<br>";
      }else{
        echo "Address Validation Failed:<br>";
        print_r($addressValidation);
      }
      $createCustomer = $this->TG5Api->createLifelineCustomer([
        "enrollment_id"=> $enrollmentId,
        "customer_suffix"=> "",
        "first_name"=> $data['first_name'],
        "middle_name"=> $data['middle_name'],
        "last_name"=> $data['second_name'],
        "primary_telephone_number"=> $data['phone_number'],
        "service_address_one"=> $data['address1'],
        "service_address_two"=> $data['address2'],
        "service_city"=> $data['city'],
        "service_state"=> $data['state'],
        "service_zip"=> $data['zipcode'],
        "mailing_address_one"=> "",
        "mailing_address_two"=> "",
        "mailing_city"=> "",
        "mailing_state"=> "",
        "mailing_zip"=> "",
        "ssn"=> $data['ssn'],
        "dob"=> $data['dob'],
        "beneficiary_suffix"=> "",
        "beneficiary_first_name"=> "",
        "beneficiary_middle_name"=> "",
        "beneficiary_last_name"=> " ",
        "beneficiary_dob"=> "",
        "beneficiary_ssn"=> "",
        "beneficiary_tribal_id"=> "",
        "email"=> $data['email'],
        "plan_id"=> "1351",
        "best_way_to_reach"=> [
            "email"
        ],
        "program_code"=> [
            "SNAP"
        ],
        "address_type"=> "P",
        "household_adult"=> "Y",
        "household_share"=> "",
        "household_lifeline"=> "N",
        "enrollment_type"=> "SHIPMENT",
        "is_ebb_qualify"=> "Y",
        "consent_check"=> "Y",
        "ebb_verify_type"=> "NV",
        "initial_choosen_enrollment_type"=> "LIFELINE",
        "lifeline_enrollment_type"=> "LIFELINE",
        "action"=> "create_lifeline_customer",
        "agent_id"=> "ewebsiteapi",
        "external_transaction_id"=> "",
        "source"=> "WEBSITE"
        ]);
    // echo "<pre>";
    // print_r($createCustomer);


    //   $addressValidation = $this->TG5Api->addressValidation([
    //     "enrollment_id"=> $enrollmentId,
    //     "first_name"=> "JOHN",
    //     "middle_name"=> "",
    //     "last_name"=> "DOE",
    //     "address_one"=> "test Street",
    //     "address_two"=> "",
    //     "city"=> "Tulsa",
    //     "state"=> "OK",
    //     "zip_code"=> $zipcode,
    //     "is_temp"=> "N",
    //     "ssn"=> "1234",
    //     "dob"=> "2004-08-02",
    //     "mailing_address_one"=> "",
    //     "mailing_address_two"=> "",
    //     "mailing_city"=> "",
    //     "mailing_state"=> "",
    //     "mailing_zip"=> "",
    //     "beneficiary_suffix"=> "",
    //     "beneficiary_first_name"=> "",
    //     "beneficiary_middle_name"=> "",
    //     "beneficiary_last_name"=> " ",
    //     "beneficiary_dob"=> "",
    //     "beneficiary_ssn"=> "",
    //     "action"=> "address_validation",
    //     "external_transaction_id"=> "TWTT8C0018",
    //     "agent_id"=> "ewebsiteapi",
    //     "source"=> "WEBSITE",
    //     "lifeline_enrollment_type"=> "LIFELINE",
    //     "address_validation"=>"Y",
    //     "initial_choosen_enrollment_type"=> "LIFELINE"

    // ]);
    //echo "<pre>";
    //print_r($addressValidation);
    //echo "Address Validation:<br>";
    

    }else{
      echo "Service Not Available<br>";
    }
    // $json = json_encode($movie, JSON_PRETTY_PRINT);
    // echo $json;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

  }

  public function sendDocumentsAPI($orderId,$typeDoc)
  {
    //echo $orderId;
    //echo "<br>";
    $customerData = $this->enrollModel->getCustomerbyOrderId($orderId);
    //print_r($customerData);
    $this->APIService = new APIprocess();
    $response=$this->APIService->sendDocuments($customerData[0]['customer_id'],$customerData[0]['order_id'],$typeDoc,$this->enrollModel);

    //print_r($response);
    echo json_encode($response);
  }

 public function getallfilessaved($customerId){
  $getFiles = $this->enrollModel->getAllFiles($customerId);
  return $getFiles;
 }

  public function savescreen()
  {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $base64_string = $_POST['base64screen'];
      $customer_id = $_POST['customer_id'];
      $filepath = saveBase64File($base64_string, $customer_id, "Screenshot");
      $fileData = [
        "customer_id" => $customer_id,
        "filepath" => $filepath,
        "type_doc" => "Screenshot"
      ];
      $fileData['statusScreen'] = ($this->enrollModel->saveData($fileData, 'lifeline_documents')) ? true : false;

      // Add verification if an ID or POB document exists in lifeline_documents
      //$fileData['hasIdOrPobDocument'] = $this->enrollModel->hasIdOrPobDocument($customer_id);

      $updatestep = [
        "customer_id" => $customer_id,
        "order_status" => "New",
        "order_step" => "Thankyou"
      ];
      $this->enrollModel->updateData($updatestep, 'lifeline_records');
      echo json_encode($fileData);
    }
  }

  public function checkZipcode($zipcode, $state, $city)
  {

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://api.zippopotam.us/us/' . trim($zipcode),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);


    $curl_error = curl_error($curl);
    $curl_errno = curl_errno($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    // Step 1: Check if cURL itself failed (connection problems, timeouts, DNS, etc.)
    if ($curl_errno) {
      //echo "cURL error: $curl_error"; // This includes many kinds of outages
      $row = [
        "status" => "error",
        "msg" => $curl_error
      ];
      // Optionally log or retry here
    }
    // Step 2: Check if API returned an HTTP error
    elseif ($http_code >= 400) {
      //echo "API HTTP error: $http_code";
      $row = [
        "status" => "error",
        "msg" => "HTTP ERROR CODE: " . $http_code
      ];
      // Optional: you might want to parse $response for error details
    }
    // Step 3: All good
    else {
      $result = json_decode($response, true);
      print_r($result);
      echo $state;
      if ($result['places']) {
        if (strtoupper($state) == $result['places'][0]['state abbreviation'] && trim(ucfirst(strtolower($city))) == $result['places'][0]['place name']) {
          $row = [
            "status" => "success",
            "msg" => "state and city match"
          ];
        } else {
          $row = [
            "status" => "error",
            "msg" => "state and city does not match"
          ];
        }
      } else {
        $row = [
          "status" => "error",
          "msg" => "city and state couldn't be validated "
        ];
      }
    }

    print_r($row);
  }

  public function consentProcess($orderId,$customerId){
    $this->APIService = new APIprocess();
    $consentFile64=$this->APIService->getConsentFile($orderId);
            //$consentFile64 = getConsent64($row[0]);
            //print_r($consentFile64);
             $processData['customer_id']=$customerId;
              $processData['process_status']="generating consent File";
              $this->enrollModel->updateData($processData,'lifeline_records');
            // exit();
            if($consentFile64['status']=="success"){
              $fileData = [
                 "customer_id"=>$customerId,
                 "filepath"=>$consentFile64['URL'],
                 "type_doc"=>"Consent"
               ];
               $this->enrollModel->saveData($fileData,'lifeline_documents');
              $ConsentFileResult = $this->APIService->sendDocuments($customerId,$orderId,"Consent",$this->enrollModel);
              $processData['process_status']=$ConsentFileResult['msg'];
              $this->enrollModel->updateData($processData,'lifeline_records');
                $result=[
                  "status"=>"success",
                  "msg"=>"Order Submitted and Consent file submitted"
                ];
              
            }else{
              //echo "base64 error";
              $result=[
                "status"=>"success",
                "msg"=>"We couldn't create a consent file"
              ];
               $processData['process_status']="Couldn't create a consent file";
              $this->enrollModel->updateData($processData,'lifeline_records');
            }

            echo json_encode($result);
  }

  public function getConsentFile($orderId)
  {

    //echo URLROOT.'/public/files/consentPDF/';
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => URLROOT . '/public/files/consentPDF/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
            "orderId":' . $orderId . '
        }',
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
        "status" => "error",
        "msg" => $curl_error
      ];
      // Optionally log or retry here
    }
    // Step 2: Check if API returned an HTTP error
    elseif ($http_code >= 400) {
      //echo "API HTTP error: $http_code";
      $result = [
        "status" => "error",
        "msg" => "HTTP ERROR CODE: " . $http_code
      ];
      // Optional: you might want to parse $response for error details
    }
    // Step 3: All good
    else {
      $result = json_decode($response, true);
    }
    return $result;
  }

  public function thankyou()
  {
    $customerId = $_POST['customer_id'] ?? null;

    $data = [
      'customer_id' => $customerId
    ];

    $this->view('enrolls/thankyou', $data);
  }

    public function noservicearea()
  {
    $this->view('enrolls/noservice');
  }

  

  public function getprograms($etc=null,$zipcode=null,$tribal=null)
  {
    if($etc=="GTW" || $etc=="NAL"){
      $data = [
        "zipcode"=>$zipcode,
        "tribal"=>$tribal
      ];
      $programlist = $this->telgooProcessStep($data,$etc,3);
      $row=[];
      $i=0;
      foreach($programlist['data']['programs_list'] as $key){
        $row[$i]=[
          "id_program"=>$key['program_code'],
          "name"=>$key['program_name']
        ];
        $i++;
      }
      // echo "<pre>";
      // print_r($row);
      echo json_encode($row);

    }else{
      $row = $this->enrollModel->getLifelinePrograms();
      // echo "<pre>";
      // print_r($row);
      echo json_encode($row);
    }
  }

  public function getagreementitems($states)
  {
    $row = $this->enrollModel->getAgreementsItems($states);
    //print_r($row);
    echo json_encode($row);
  }

  public function sendNotification($custmerData)
  {

    //$fullname = ucfirst(strtolower($data['firstname']))." ".ucfirst(strtolower($data['lastname']));
    $message = '<table role="presentation" class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%;" width="100%">
  <!-- START MAIN CONTENT AREA -->
  <tr>
    <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;" valign="top"><table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" width="100%">
        <tr>
          <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top"><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hello!</p>
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">New Lifeline Order Received </p>
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><strong>First Name: </strong>' . $custmerData['first_name'] . '</p>
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><strong>Last Name: </strong>' . $custmerData['second_name'] . '</p>
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><strong>Email: </strong>' . $custmerData['email'] . '</p>
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><strong>City: </strong>' . $custmerData['city'] . '</p>
			<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><strong>State: </strong>' . $custmerData['state'] . '</p>
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><strong>Order/Enrollment ID: </strong>' . $custmerData['order_id'] . '</p>
			
			<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><strong>API Response: </strong>' . $custmerData['acp_status'] . '</p>
			</td>
        </tr>
      </table>
      </td>
  </tr>
  <!-- END MAIN CONTENT AREA -->
</table>';
    //$message = preg_replace($subtr,$sust,$template );

    $mailer = new PHPMailer_Lib();
    $mail = $mailer->load();
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = SMTP_HOST;            // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = SMTP_USERNAME;                     // SMTP username
    $mail->Password   = SMTP_PASSWORD;                               // SMTP password
    $mail->SMTPSecure = SMTP_ENCRYPTION;                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = SMTP_PORT;                                 // TCP port to connect to
    //Recipients
    $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME_ORDERS);
    $mail->addAddress(MAIL_ORDERS_TO);
    //$mail->addAddress('currutia@gotruewireless.com');
    //$mail->addCC('jparker@galaxydistribution.com'); 
    //$mail->addCC('currutia44@gmail.com');      // Add a recipient
    //$mail->addBCC('xneriox@gmail.com');
    $mail->isHTML(true); 
    switch($custmerData['ETC']){
      case "GTW":
        $company = "GoTrueWireless";
        break;
      case "NAL":
        $company = "National Relief Telecom";
        break;
      case "AMBT":
        $company = "American Broadband";
        break;
      default:
        $company = "Demo-GoTrueWirelessNRT";  // Set email format to HTML
        break;
    }                        // Set email format to HTML
    $mail->Subject = 'New Lifeline Order from ' . $company;
    $mail->Body    = $message;
    $mail->CharSet = 'UTF-8';
    $mail->send();
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
