<?php
  class Records extends Controller {
    public $recordsModel;
    public $enrollsModel;
    public $userModel;
	public $APIService;
    public function __construct(){
        if(!isLoggedIn()){
            redirect('users/login');
        }

        $this->recordsModel = $this->model('Record');
        $this->enrollsModel = $this->model('Enroll'); 
        $this->userModel = $this->model('User');
    }

    public function getPrograms(){
		$row = $this->recordsModel->getProgram();
		echo json_encode($row);
	}

    public function getProgramNames($idprogram){
		$row = $this->recordsModel->getProgramNames($idprogram);
        //print_r($row);
		echo json_encode($row[0]);
	}

    public function index(){
        $this->view("records/index");
    }

	public function updateRecordInput(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$field = isset($_POST['field']) ? trim($_POST['field']) : '';
			$allowedFields = ['program_before', 'program_benefit', 'source'];

			$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

			if (!in_array($field, $allowedFields, true) || !isset($_POST[$field]) || $id <= 0) {
				echo json_encode(["status" => false, "message" => "Invalid update request"]);
				return;
			}

			$data=[
				$field=>trim($_POST[$field]),
				"id"=>$id
			];
			//print_r($data);
			$result = $this->enrollsModel->updateDataById($data,"lifeline_records");
			$response = ["status"=>$result];
			echo json_encode($response);
		}
	}

	public function notifyDocuments(){
// 		ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);   
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$data=[
				"customer_id"=>$_POST['customer_id'],
				"email"=>$_POST['email'],
				"firstname"=>$_POST['firstname'],
				"lastname"=>$_POST['lastname'],
			];
			if($data['email'] != "" && filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
				$customerName = $data['firstname'] . ' ' . $data['lastname'];
				$applicationId = $data['customer_id'];
				$uploadLink = "https://lifeline.truewireless.com/enrolls/getdocuments/" . $data['customer_id'];
				$supportEmail = "support@truewireless.com";
				//print_r($data);
			 	//$to = "xneriox@gmail.com";
				$subject = "Additional Documents Required to Complete Your Lifeline Application ";  
				//$message = "Customer ID: " . $data['customer_id'] . "\n";
				//$message .= "Customer Name: " . $data['first_name']." ".$data['second_name'] . "\n";
				$message = "<div style='font-family: Arial, Helvetica, sans-serif; font-size:14px; color:#333;'><p>Hello <strong>{$customerName}</strong>,</p><p>Thank you for submitting your Lifeline application.We have successfully received your information.</p><p>To continue processing your application we still need the following documents:</p><ul>	<li>Proof of Identity (ID card, driver's license, passport)</li>	<li>Proof of Benefit (eligibility letter or benefit notice)</li></ul><p>Please upload the required documents using the secure link below: </p><p><a href='{$uploadLink}' style='display:inline-block;padding:12px 20px;background:#0d6efd;color:#ffffff;text-decoration:none;border-radius:4px;'>Upload Documents</a></p><p><strong>Important:</strong><br> Your application cannot be completed until all required documentation has been received.</p><p>If you have already submitted these documents, please disregard this message.</p><p>If you need assistance, contact us at <a href='mailto:{$supportEmail}'>{$supportEmail}</a>.</p><br><p>Kind regards,<br><strong>Lifeline Enrollment Team</strong><br>True Wireless<p></div>";
  
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
				$mail->addAddress($data['email']);
				//$mail->addCC('jparker@galaxydistribution.com'); 
				//$mail->addCC('currutia44@gmail.com');      // Add a recipient
				$notifyBccList = array_filter(array_map('trim', explode(',', MAIL_NOTIFY_DOCS_BCC)));
				foreach ($notifyBccList as $bccEmail) {
					$mail->addBCC($bccEmail);
				}
				$mail->isHTML(true);
				$mail->Subject = $subject;
				$mail->Body = nl2br($message);
				$mail->send();
				$result="OK";
				$docsData=[
					"customer_id"=>$applicationId,
					"order_status"=>"Waiting for Docs"
				];
				$this->recordsModel->updateOrderbycustomerid($docsData);
			}else{
				$result = "Fail";
			}
			$response = ["response"=>$result];
			echo json_encode($response);
		}
	}

	public function updateUnableToProcess(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$data=[
				"acp_status"=>$_POST['acp_status'],
				"order_id"=>$_POST['order_id'],
				"id"=>$_POST['id']
			];
			//print_r($data);
			$result = $this->enrollsModel->updateDataById($data,"lifeline_records");
			$response = ["status"=>$result];
			echo json_encode($response);
		}
	}

    public function read(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
		//if($_POST){
			//die('Submit');
			//$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$page = (isset($_POST['page']) && !empty($_POST['page']))?$_POST['page']:1;
			$data = [
				'action'=>trim($_POST['action']),
				'firstload'=>$_POST['firstload'],
				'arrayCampos'=>(empty($_POST['search']))?[]:$_POST['search'],
				'order_by'=>'created_at desc',
				'length'=>$_POST['length'],
				'page'=>$page,
				'per_page'=>'',
				'adjacents'=>'',
				'offset'=>'',
				'offsetToShow'=>'',
				'numrows'=>'',
				'total_pages'=>'',
				'c'=>'',
				'pagination'=>'',
			];
			//print_r($data);
			//die('Submit');
		}else{
			$data = [
				'action'=>'',
				'firstload'=>'YES',
				'arrayCampos'=>[],
				'order_by'=>'date(created_at) desc',
				'length'=>10,
				'page'=>1,
				'per_page'=>'',
				'adjacents'=>'',
				'offset'=>'',
				'offsetToShow'=>'',
				'numrows'=>'',
				'total_pages'=>'',
				'c'=>'',
				'pagination'=>'',
				'fields'=>'',
			];
				}
			
			
		
			//print_r($data);
			$camposBase=array("customer_id","first_name","second_name","phone_number","email","dob","city","state","zipcode","order_id","order_status","program_benefit","created_at");
			$filters = [];
			for($index=0;$index<count($camposBase);$index++){
				$rawValue = isset($data['arrayCampos'][$index]) ? trim((string)$data['arrayCampos'][$index]) : '';
				if($rawValue !== ''){
					$filters[$camposBase[$index]] = $rawValue;
				}
			}
			//$status  = $this->getOrderStatus();
			
			$consultaBusqueda = "";
			$contarCuantasBusquedas = 0;
            $camposAscDesc="";
			$per_page = $data['length']; //la cantidad de registros que desea mostrar
			$adjacents  = 2; //brecha entre páginas después de varios adyacentes
			$offset = ($data['page'] - 1) * $per_page;
			$offsetnumeroMostrar = ($data['page']-1) * $per_page + 1;
			$numrows = $this->recordsModel->countRegisters($filters,$data['firstload']);
			$total_pages = ceil($numrows/$per_page);
			$reload = 'index.php';
			$data['per_page']=$per_page;
			$data['adjacents']=$adjacents;
			$data['offset']=$offset;
			$data['offsetToShow'] = $offsetnumeroMostrar;
			$data['numrows']=$numrows;
			$data['total_pages']=$total_pages;
			$paginate = $this->paginate($reload,$data['page'] , $total_pages, $adjacents, $data['arrayCampos'],$data['length'],$camposAscDesc);
			$data['pagination']=$paginate;
			//$per_page = 30; //la cantidad de registros que desea mostrar
			
			$getOrders = $this->recordsModel->getData($data['offset'],$data['per_page'],$filters,$data['order_by'], $data['firstload']);

			$data['fields']=$getOrders;
		
			echo json_encode($data);
			//return $getOrders;
			
			//$this->view('dashboard/index',$data);
		//header('Content-type: application/json; charset=utf-8');
			
		}

	
	
	public function paginate($reload, $page, $tpages, $adjacents,$ArrayCampos,$example_length,$camposAscDesc) {

		//$ArrayCampos="";
			$ArrayCampos = json_encode($ArrayCampos);
			$camposAscDesc = json_encode($camposAscDesc);
			//print("<pre>".print_r($ArrayCampos,true)."</pre>");
			//$camposAscDesc="";
		
			$prevlabel = "&lsaquo;";
			$nextlabel = "&rsaquo;";
			$out = '<ul class="pagination">';
			 
			// previous label
		
			if($page==1) {
				$out.= "<li class='page-item disabled'><span><a class='page-link'>$prevlabel</a></span></li>";
			} else if($page==2) {
				$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(1,".$ArrayCampos.",".$example_length.",".$camposAscDesc.")'>$prevlabel</a></li>";
			}else {
				$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(".($page-1).",$ArrayCampos,$example_length,$camposAscDesc)'>$prevlabel</a></li>";
		
			}
			
			// first label
			if($page>($adjacents+1)) {
				$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(1,".$ArrayCampos.",".$example_length.",".$camposAscDesc.")'>1</a></li>";
			}
			// interval
			if($page>($adjacents+2)) {
				$out.= "<li class='page-item'><a class='page-link'>...</a></li>";
			}
		
			// pages
		
			$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
			$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
			for($i=$pmin; $i<=$pmax; $i++) {
				if($i==$page) {
					$out.= "<li class='page-item active'><a class='page-link'>$i</a></li>";
				}else if($i==1) {
					$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(1,".$ArrayCampos.",".$example_length.",".$camposAscDesc.")'>$i</a></li>";
				}else {
					$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(".$i.",$ArrayCampos,$example_length,$camposAscDesc)'>$i</a></li>";
				}
			}
		
			// interval
		
			if($page<($tpages-$adjacents-1)) {
				$out.= "<li class='page-item'><a class='page-link'>...</a></li>";
			}
		
			// last
		
			if($page<($tpages-$adjacents)) {
				$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load($tpages,".$ArrayCampos.",".$example_length.",".$camposAscDesc.")'>$tpages</a></li>";
			}
		
			// next
		
			if($page<$tpages) {
				$out.= "<li class='page-item'><span><a class='page-link' href='javascript:void(0);' onclick='load(".($page+1).",$ArrayCampos,$example_length,$camposAscDesc)'>$nextlabel</a></span></li>";
			}else {
				$out.= "<li class='page-item disabled'><span><a class='page-link'>$nextlabel</a></span></li>";
			}
			
			$out.= "</ul>";
			return $out;
		}

        public function edit($customerId){
			//echo $customerId;
            $data = $this->recordsModel->getCustomerInfo($customerId);
            //print_r($data);
            $this->view("records/edit",$data);
        }

    public function getStaffs(){
		/*if($_SERVER['REQUEST_METHOD']=='POST'){
			$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$source = $_POST['source'];*/
			$row = $this->userModel->getStaff();
			echo json_encode($row);
		/*}*/
	}

	public function getScripts(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$source = isset($_POST['source']) ? trim($_POST['source']) : '';
			if ($source === '') {
				echo json_encode([]);
				return;
			}

			$row = $this->recordsModel->getScript($source);
			echo json_encode($row);
		}
	}

	public function savessn(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$id = isset($_POST['idrecord']) ? (int)$_POST['idrecord'] : 0;
			$ssnRaw = isset($_POST['ssn']) ? (string)$_POST['ssn'] : '';
			$ssn = preg_replace('/\D+/', '', $ssnRaw);

			if ($id <= 0 || strlen($ssn) < 4) {
				echo json_encode(['response' => 'ERROR', 'message' => 'Missing information']);
				return;
			}

			$data = [
				'id' => $id,
				'ssn' => substr($ssn, -4)
			];
			$result = $this->enrollsModel->updateDataById($data, 'lifeline_records');
			echo json_encode(['response' => $result ? 'OK' : 'ERROR']);
		}
	}

	public function sendSMS(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			echo json_encode([
				'response' => 'ERROR',
				'message' => 'SMS endpoint is not configured in this environment'
			]);
		}
	}

	public function assignStaff(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			//$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$data = [
				"id"=>$_POST['id_order'],
				"tookstaff"=>$_POST['tookstaff']
			];
			$row = $this->recordsModel->updateOrder($data);
			if($row){
				$response = array (
				 'response' => 'OK', 
			 	);
			}else{
				$response = array (
				 'response' => 'ERROR', 
				);
			 	};
			echo json_encode($response);
		}
	}

    public function getNotes(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			//$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$customer_id = $_POST['customer_id'];
			$row = $this->recordsModel->getNotes($customer_id);
			echo json_encode($row);
		}
	}

    public function getResponses(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			//$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$customer_id = $_POST['customer_id'];
			$row = $this->recordsModel->getResponses($customer_id);
			echo json_encode($row);
		}
	}

	public function updateRecord(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			//$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$data=[
				"first_name"=>trim(ucfirst(strtolower($_POST['firstname_edit']))),
				"second_name"=>trim(ucfirst(strtolower($_POST['lastname_edit']))),
				"email"=>trim(strtolower($_POST['email_edit'])),
				"dob"=>trim($_POST['dob_edit']),
				"phone_number"=>trim($_POST['phone_edit']),
				"ssn"=>trim($_POST['ssn_edit']),
				"address1"=>trim($_POST['address1_edit']),
				"address2"=>trim($_POST['address2_edit']),
				"city"=>trim($_POST['city_edit']),
				"state"=>trim($_POST['state_edit']),
				"zipcode"=>trim($_POST['zipcode_edit']),
				"id"=>trim($_POST['id'])
			];

			$result = $this->enrollsModel->updateDataById($data,"lifeline_records");
			$response = ["status"=>$result];
			echo json_encode($response);
		}
	}

	public function changeStatus(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			//$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$id = $_POST['id_order'];
			$order_status = $_POST['order_status'];
			//$order = $this->recordsModel->getOrder($orderId);
			$orderData=[
				"order_status"=>$order_status,
				"id"=>$id
			];
			if($order_status != ""){
				$this->enrollsModel->updateDataById($orderData,"lifeline_records");
				$response = array (
				 'response' => 'OK', 
			 	);
			}else{
				$response = array (
				 'response' => 'Missing information', 
			 	);
			}
			echo json_encode($response);
		}
	}

	public function saveNote(){
		
		if($_SERVER['REQUEST_METHOD']=='POST'){
			//$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$orderId = $_POST['customer_id'];
			$msg = $_POST['internal'];
			$id_user = $_POST['id_user'];
			$username = $_POST['user_name']; 
			//$order = $this->recordsModel->getOrder($orderId);
			//$today = date('Y-m-d H:i:s');
						
			$notesData=[
				"customer_id"=>$orderId,
				"type_note"=>"Note",
				"message_send"=>$msg,
				"id_user"=>$id_user,
				"user_name"=>$username
				
			];
			if($msg != ""){

				$this->recordsModel->internalNotes($notesData);
				$response = array (
				 'response' => 'OK', 
			 	);
				
			}else{
				$response = array (
				 'response' => "Missing information",
			 	);
			}
						
			echo json_encode($response);
		}
	}

	public function shockwaveProcess($customerId){
      //'G-SN3C0031'
      
    	$this->APIService = new APIprocess();
        $result = $this->APIService->shockwaveProcess($customerId,$this->enrollsModel);
      //print_r($result);
      echo json_encode($result);
    }

	public function checknlad(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$orderId = $_POST['id_order'];
			$order = $this->recordsModel->getOrder($orderId);
			$credentials=$this->enrollsModel->getCredentials();
			$cred = [
				"CLECID"=> $credentials[0]['CLECID'],
				"UserName"=> $credentials[0]['UserName'],
				"TokenPassword"=> $credentials[0]['TokenPassword'],
				"PIN"=> $credentials[0]['PIN']
			];
			$payload = array(
				"Credential"=>$cred,
				"SubscriberOrderId"=>$order['order_id'],
 				"Author"=>$credentials[0]['author'],
 				"RepNotAssisted"=>true
			);
			$mycurl = new Curl();
			//echo json_encode($payload);
			$url2="https://wirelessapi.shockwavecrm.com/PrepaidWireless/NationalVerifierEligibilityCheck";
			$request2 = json_encode($payload);
			$header2 = array('Content-Type: application/json');
			$orderres = $mycurl->postJsonAuth($url2,$request2,$header2);
			//echo $orderres;
			$response2 = json_decode($orderres,true);
			$nlad=array(
				"id"=>$order['id'],
				"status_text"=>$response2['StatusText']
			);
			$this->recordsModel->updateOrder($nlad);
			$this->recordsModel->saveApiLog($order['customer_id'],$url2,$request2,$orderres,'checkNlad');
			echo $orderres;
		}
	}

	public function getDataReport()
	{
		$excelData = $this->recordsModel->getReport();
		//print_r($row);
		//echo json_encode($row);
		//print("<pre>".print_r($excelData,true)."</pre>");
		$date = date('YmdHis');
		$fileName = 'LL_Orders_Report_' . $date . '.csv';
		//$file = fopen($fileName, 'w');

		// Set headers to indicate that this is a CSV file
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $fileName);

		// Open output stream in PHP (instead of writing to a file)
		$output = fopen('php://output', 'w');

		// Write the header row (column names)
		fputcsv($output, array_keys($excelData[0]));

		// Write each row of the data array
		foreach ($excelData as $row) {
			fputcsv($output, $row);
		}

		// Close the output stream
		fclose($output);

		// End the script to prevent further output
		exit;
	}

}