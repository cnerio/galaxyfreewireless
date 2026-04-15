<?php
function create_shockwave_accountTEST($data, $customer_id, $company, $carrier, $configs)
{

	$credentials = getCredentialCLEC($company, $data['source']);
	//print("<pre>" . print_r($credentials, true) . "</pre>");
	$valid = 1;
	$OrderRequest = null;

	switch ($company) {
		case "TERRACOM":
			$OrderRequest = lifeline_payload($data, $credentials, $carrier, $company, $configs);
		//    CURLOPT_HTTPHEADER => array(
		// 	   'Content-Type: application/json'
		//    ),
		//    CURLOPT_SSL_VERIFYHOST => IS_LOCALHOST ? 0 : 2,
		//    CURLOPT_SSL_VERIFYPEER => IS_LOCALHOST ? 0 : 1
			break;
		case "TORCH":
			//$OrderRequest = torch_payload($data, $credentials, $carrier, $company);
			break;
		default:
			$valid = 0;
			break;
	}
	// Handle invalid cases
	if ($valid === 0) {
		return [
			'message' => 'Error, company or program not found',
			'status' => 'Error',
			'OrderId' => '0'
		];
	}

	// Make the API call
	$url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/AddSubscriberOrderWithEBBData";
	$request = json_encode($OrderRequest);
	//$response = unavoAPICall($url, $OrderRequest);
	$response = '{
    "SubscriberOrderID": 3782732,
    "ETCData": {
        "ETCFlag": true,
        "LOAFlag": false,
        "ETCApprovedFlag": false,
        "TribalFlag": false,
        "InCoverageAreaFlag": true,
        "ProofOfBenefitsUploadedFlag": false,
        "IdentityProofUploadedFlag": false,
        "TribalID": "",
        "LifelineCertificationTypeID": 100000
    },
    "AccountNumber": 3786035,
    "FirstName": "unavo",
    "LastName": "test",
    "MiddleInital": "",
    "SSN": "1256",
    "DOB": "01/11/1990 00:00:00",
    "HouseNumber": null,
    "Street": "3308 Forest Lawn Avenue",
    "City": "Omaha",
    "StateCode": "NE",
    "Zipcode": "68112",
    "Email": "test@gmail.com",
    "PrimaryPhone": "1234567890",
    "WirelessProviderTypeID": 100003,
    "CustomerPackageID": 1575,
    "NLADStatus": "NV_PENDING_RESOLUTION",
    "AcpStatus": null,
    "CoverageServiceArea": "N/A",
    "Status": "Success",
    "StatusText": "Order was created Successfully, Order ID is: 3782732",
    "AddlMessage": "  Additional information/documents must be provided to resolve failures specified in the Failures field for the subscriber. <a href=https://api-stg.universalservice.org/nvca-svc/security/getPage?id=61404057E2BFC26FDEC6E6AF1019EF996BDECCA01BFEE346CDF471F7245F1E4E&token=4e5032aacfab0611795dfa5cce9fb3890dcfff6d50c82703b75da3d139ab367c> <b>click here</b> </a> to access the page for further action. ",
    "RepId": null,
    "ExternalId": "12313515",
    "NladResponse": {
        "NladSubscriberRequestId": 253718,
        "AcpStatus": null,
        "SubscriberId": null,
        "Message": "  Additional information/documents must be provided to resolve failures specified in the Failures field for the subscriber. <a href=https://api-stg.universalservice.org/nvca-svc/security/getPage?id=61404057E2BFC26FDEC6E6AF1019EF996BDECCA01BFEE346CDF471F7245F1E4E&token=4e5032aacfab0611795dfa5cce9fb3890dcfff6d50c82703b75da3d139ab367c> <b>click here</b> </a> to access the page for further action. ",
        "Errors": [],
        "ResolutionId": null,
        "NV_Failures": [
            "TPIV_FAIL_DECEASED",
            "DUPLICATE_ADDRESS",
            "STATE_FED_FAIL"
        ],
        "NV_Url": "https://api-stg.universalservice.org/nvca-svc/security/getPage?id=61404057E2BFC26FDEC6E6AF1019EF996BDECCA01BFEE346CDF471F7245F1E4E&token=4e5032aacfab0611795dfa5cce9fb3890dcfff6d50c82703b75da3d139ab367c",
        "NV_ApplicationId": "Q82456-46918",
        "NV_EligibilityCheckId": "61404057E2BFC26FDEC6E6AF1019EF996BDECCA01BFEE346CDF471F7245F1E4E",
        "NV_IsActiveSubscriber": true,
        "TribalStatusConfirmed": true,
        "Latitude": null,
        "Longitude": null,
        "StatusType": 110005,
        "ErrorType": 0,
        "Status": null,
        "StatusText": null,
        "AddlText": null,
        "RequestMessageID": null,
        "CheckPoint": 0,
        "ExceptionContext": null
    },
    "Variable": "testing",
    "Language": "English",
    "RequestMessageID": null,
    "CheckPoint": 0,
    "ExceptionContext": null
	}';
	$subscriberOrder = json_decode($response, true);
	$tribalStatus = "false";

	if ($subscriberOrder['SubscriberOrderID'] == 0) {
		$acp_status = $subscriberOrder["StatusText"];
	} else {
		$acp_status = $subscriberOrder['NLADStatus'];
	}
	// Prepare the response data
	$dataApi = [
		"customer_id" => $customer_id,
		"order_id" => $subscriberOrder["SubscriberOrderID"],
		"account" => $subscriberOrder["AccountNumber"],
		"company" => $data["company"],
		"acp_status" => $acp_status,
		"status_text" => $subscriberOrder["StatusText"]		
	];
	$packagesID = [
		"CustomerPackageID" => $configs["packages"]["tribal"]
	];

	if ($subscriberOrder["NladResponse"]["TribalStatusConfirmed"] === true) {

		$tribalStatus = true;
		$tribalrequest = tribalUpdate_payload($dataApi, $packagesID ,$credentials);
		$tribalurl= "https://wirelessapi.shockwavecrm.com/PrepaidWireless/TribalUpdate";
		$tribalresponse = unavoAPICall($tribalurl, $tribalrequest);
		$tribaltitle = "TribalUpdate";
	} else {
		$tribalStatus = false;
		$tribalurl = "";
		$tribalrequest = "";
		$tribalresponse = "";
		$tribaltitle = "";
	}

	return [
		"customer_id" => $customer_id,
		"url" => $url,
		"request" => $OrderRequest,
		"response" => json_encode($subscriberOrder["NladResponse"]),
		"title" => "AddSubscriberOrder",
		"dataAPI" => $dataApi,
		"tribal_status" => $tribalStatus,
		"tribal_url" => $tribalurl,
		"tribal_request" => $tribalrequest,
		"tribal_response" => $tribalresponse,
		"tribal_title" => $tribaltitle
	];
}

function nladEnrollTEST($order_id, $customer_id, $company, $source)
{

	$credentials = getCredentialCLEC($company, $source);
	$url = "https://wirelessapi.shockwavecrm.com/Prepaidwireless/NladEnroll";

	if (isset($credentials['author'])){
		$author = $credentials['author'];
	}else{
		$author = "Terracom Lifeline Orders";
	}
	
	$payload = '{
		"Credential": {
			"CLECID": "' . $credentials['CLECID'] . '",
			"UserName": "' . $credentials['UserName'] . '",
			"TokenPassword": "' . $credentials['TokenPassword'] . '",
			"PIN": "' . $credentials['PIN'] . '"
		},
		"SubscriberOrderId": "' . $order_id . '",
		"Author": "' . $author . '",
		"EnrollLifeLine": "true",
		"EnrollEBB": "false",
			   CURLOPT_SSL_VERIFYHOST => IS_LOCALHOST ? 0 : 2,
			   CURLOPT_SSL_VERIFYPEER => IS_LOCALHOST ? 0 : 1,
		"RepNotAssisted": "true"
	}';
	//$response = unavoAPICall($url, $payload);
	$response = '{
		"SubscriberOrderID": 3782732,
		"Status": "Success",
		"StatusText": "SAC_NOT_SIX_DIGITS: sac SAC number is not 6 digits.",
		"LifelineNladResponse": {
			"NladSubscriberRequestId": 253724,
			"AcpStatus": null,
			"SubscriberId": null,
			"Message": "SAC_NOT_SIX_DIGITS: sac SAC number is not 6 digits.",
			"Errors": [
				{
					"Feild": "sac",
					"ErrorMessage": "SAC number is not 6 digits.",
					"ErrorCode": "SAC_NOT_SIX_DIGITS"
				}
			],
			"ResolutionId": null,
			"NV_Failures": null,
			"NV_Url": null,
			"NV_ApplicationId": null,
			"NV_EligibilityCheckId": null,
			"NV_IsActiveSubscriber": false,
			"TribalStatusConfirmed": null,
			"Latitude": null,
			"Longitude": null,
			"StatusType": 100008,
			"ErrorType": 100003,
			"Status": null,
			"StatusText": null,
			"AddlText": null,
			"RequestMessageID": null,
			"CheckPoint": 0,
			"ExceptionContext": null
		},
		"EBBNladResponse": null,
		"RequestMessageID": null,
		"CheckPoint": 0,
		"ExceptionContext": null
	}';
	return [
		"customer_id" => $customer_id,
		"url" => $url,
		"request" => $payload,
		"response" => $response,
		"title" => "NladEnroll"
	];
}

function create_shockwave_accountTEST2($data,$credentials,$packages){
    
    $url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/AddSubscriberOrderWithEBBData";
    $OrderRequest = lifeline_payload($data, $credentials,$packages);
    $subscriberOrder["SubscriberOrderID"]=rand(10000,99999);;
    $subscriberOrder["AccountNumber"]=rand(10000,99999);
    if ($subscriberOrder['SubscriberOrderID'] == 0) {
		//$acp_status = $subscriberOrder["StatusText"];
        $acp_status="Fail";
	} else {
		//$acp_status = $subscriberOrder['NLADStatus'];
        $acp_status="Success";
	}

    $dataApi = [
		"customer_id" => $data['customer_id'],
		"order_id" => $subscriberOrder["SubscriberOrderID"],
		"account" => $subscriberOrder["AccountNumber"],
		"company" => $data["company"],
		"status" => "success",
        "url" => $url,
		"request" => $OrderRequest,
		"response" => "{'statusNlad':'Test'}",
		"title" => "AddSubscriberOrder",
		"status_text" => "test",
        "acp_status"=>"test"
	];

    return $dataApi;

}

function create_shockwave_account($data,$credentials,$packages){

	
	$OrderRequest = lifeline_payload($data, $credentials,$packages);


	// Make the API call
	if($data['ETC']=="NAL"){
		$url = "http://wirelessapi.mvnocloudsolutions.com/PrepaidWireless/AddSubscriberOrderWithEBBData";
	}else{
		$url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/AddSubscriberOrderWithEBBData";
	}
	//$request = json_encode($OrderRequest);
	$response = unavoAPICall($url, $OrderRequest);	
	//print_r($response);
	//$tribalStatus = "false";
    
	if($response['status']=="success"){
        $subscriberOrder = $response;
            if ($subscriberOrder['SubscriberOrderID'] === 0) {
            $acp_status = $subscriberOrder["StatusText"];
        } else {
            $acp_status = $subscriberOrder['NLADStatus'];
        }
        // Prepare the response data
        $result = [
            "customer_id" => $data['customer_id'],
            "order_id" => $subscriberOrder["SubscriberOrderID"],
            "account" => $subscriberOrder["AccountNumber"],
            "acp_status" => $acp_status,
            "status"=> "success",
            "status_text" => $subscriberOrder["StatusText"],
            "request" =>  $OrderRequest ,
            "response" => $response,
            "title" => "AddSubscriberOrder",
            "url"=>$url
        ];
        $packagesID = [
            "CustomerPackageID" => $credentials['packageId']
        ];
    }else{
        $result = [
            "customer_id" => $data['customer_id'],
            "order_id" => null,
            "account" => null,
            "acp_status" =>null,
            "status"=> "error",
            "status_text" => $response["msg"],
            "request" =>  $OrderRequest ,
            "response" => json_encode($response,true),
            "title" => "AddSubscriberOrder",
            "url"=>$url
        ];
    }

	return $result;
}

function testcreate_shockwave_account($data, $customer_id, $company, $carrier, $program ,$configs)
{

	$credentials = getCredentialCLEC($company, $data['source']);
	//print("<pre>" . print_r($configs, true) . "</pre>");
	//print("<pre>" . print_r($configs['packages'], true) . "</pre>");
	if($data['phone_type'] == 'iOS'){
		$packageID =($data['state'] == 'CA') ? '105':'1602';
	}else{
		if (!empty($configs['packages']['nontribal'])) {
			$packageID = $configs['packages']['nontribal'];
		} else {
			$packageID = $configs['packages']['tribal'];
		}
	}
	
	//print("<pre>" . print_r($credentials, true) . "</pre>");
	$valid = 1;
	$OrderRequest = null;
	//echo $program;
	if ($program === "LIFELINE") {
		$OrderRequest = lifeline_payload($data, $credentials, $carrier, $company, $configs);
		//print("<pre>" . print_r($credentials, true) . "</pre>");
	} else {
		switch ($company) {
			case "TERRACOM":
				$OrderRequest = lifeline_payload($data, $credentials, $carrier, $company, $configs);
				break;
			case "SURGE":
				//$OrderRequest = surge_payload($data, $credentials, $carrier, $company, $configs);
				break;
			case "TORCH":
				//$OrderRequest = torch_payload($data, $credentials, $carrier, $company, $configs);
				break;
			default:
				$valid = 0;
				break;
		}
	}

	// Handle invalid cases
	if ($valid === 0) {
		return [
			'message' => 'Error, company or program not found',
			'status' => 'Error',
			'OrderId' => '0'
		];
	}

	// Make the API call
	$url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/AddSubscriberOrderWithEBBData";
	$request = json_encode($OrderRequest);
	$response = unavoAPICall($url, $OrderRequest);
	$subscriberOrder = json_decode($response, true);
	//$tribalStatus = "false";

	if ($subscriberOrder['SubscriberOrderID'] == 0) {
		$acp_status = $subscriberOrder["StatusText"];
	} else {
		$acp_status = $subscriberOrder['NLADStatus'];
	}
	// Prepare the response data
	$dataApi = [
		"customer_id" => $customer_id,
		"order_id" => $subscriberOrder["SubscriberOrderID"],
		"account" => $subscriberOrder["AccountNumber"],
		"company" => $data["company"],
		"acp_status" => $acp_status,
		"status_text" => $subscriberOrder["StatusText"]
	];
	$packagesID = [
		"CustomerPackageID" => $configs["packages"]["tribal"]
	];

	return [
		"customer_id" => $customer_id,
		"url" => $url,
		"request" => $OrderRequest,
		"response" => json_encode($subscriberOrder),
		"title" => "AddSubscriberOrder",
		"dataAPI" => $dataApi
	];
}

function nladEnroll($order_id, $customer_id, $company, $source, $configs){
	
	$credentials = getCredentialCLEC($company, $source);
	$url = "https://wirelessapi.shockwavecrm.com/Prepaidwireless/NladEnroll";

	if (isset($credentials['author'])){
		$author = $credentials['author'];
	}else{
		$author = "Terracom Lifeline Orders";
	}
	
	if (!empty($configs['packages']['nontribal'])) {
		$packageID = $configs['packages']['nontribal'];
	} else {
		$packageID = $configs['packages']['tribal'];
	}
	$payload = '{
		"Credential": {
			"CLECID": "' . $credentials['CLECID'] . '",
			"UserName": "' . $credentials['UserName'] . '",
			"TokenPassword": "' . $credentials['TokenPassword'] . '",
			"PIN": "' . $credentials['PIN'] . '"
		},
		"SubscriberOrderId": "' . $order_id . '",
		"Author": "' . $author . '",
		"EnrollLifeLine": "true",
		"EnrollEBB": "false",
		"RepNotAssisted": "true"
	}';
	$response = unavoAPICall($url, $payload);
	$subscriberOrder = json_decode($response, true);

	if ($subscriberOrder["NladResponse"]["TribalStatusConfirmed"] === true) {
		$packageID = $configs['packages']['tribal'];
		$tribalStatus = true;
		$tribalrequest = tribalUpdate_payload($order_id, $packageID, $credentials);
		$tribalurl = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/TribalUpdate";
		$tribalresponse = unavoAPICall($tribalurl, $tribalrequest);
		$tribaltitle = "TribalUpdate";
	} else {
		$tribalStatus = false;
		$tribalurl = "";
		$tribalrequest = "";
		$tribalresponse = "";
		$tribaltitle = "";
	}

	return [
		"customer_id" => $customer_id,
		"url" => $url,
		"request" => $payload,
		"response" => $response,
		"title" => "NladEnroll",
		"tribal_status" => $tribalStatus,
		"tribal_url" => $tribalurl,
		"tribal_request" => $tribalrequest,
		"tribal_response" => $tribalresponse,
		"tribal_title" => $tribaltitle		
	];

}

function eligibilityCheck($order_id, $customer_id, $company, $source, $configs)
{

	$credentials = getCredentialCLEC($company, $source);
	$url = "https://wirelessapi.shockwavecrm.com/Prepaidwireless/NationalVerifierEligibilityCheck";

	if (isset($credentials['author'])){
		$author = $credentials['author'];
	}else{
		$author = "Terracom Lifeline Orders";
	}
	
	if (!empty($configs['packages']['nontribal'])) {
		$packageID = $configs['packages']['nontribal'];
	} else {
		$packageID = $configs['packages']['tribal'];
	}
	$payload = '{
		"Credential": {
			"CLECID": "' . $credentials['CLECID'] . '",
			"UserName": "' . $credentials['UserName'] . '",
			"TokenPassword": "' . $credentials['TokenPassword'] . '",
			"PIN": "' . $credentials['PIN'] . '"
		},
		"SubscriberOrderId": "' . $order_id . '",
		"Author": "' . $author . '",		
		"RepNotAssisted": "true"
	}';
	$response = unavoAPICall($url, $payload);
	$subscriberOrder = json_decode($response, true);

	if ($subscriberOrder["NladResponse"]["TribalStatusConfirmed"] === true) {
		$packageID = $configs['packages']['tribal'];
		$tribalStatus = true;
		$tribalrequest = tribalUpdate_payload($order_id, $packageID, $credentials);
		$tribalurl = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/TribalUpdate";
		$tribalresponse = unavoAPICall($tribalurl, $tribalrequest);
		$tribaltitle = "TribalUpdate";
	} else {
		$tribalStatus = false;
		$tribalurl = "";
		$tribalrequest = "";
		$tribalresponse = "";
		$tribaltitle = "";
	}

	
	return [
		"customer_id" => $customer_id,
		"url" => $url,
		"request" => $payload,
		"response" => $response,
		"title" => "NationalVerifierEligibilityCheck",
		"tribal_status" => $tribalStatus,
		"tribal_url" => $tribalurl,
		"tribal_request" => $tribalrequest,
		"tribal_response" => $tribalresponse,
		"tribal_title" => $tribaltitle,
		"IEHRequired" => $subscriberOrder["NladResponse"]["IEHRequired"] 
	];
}




function lifeline_payload($data, $credentials,$packages)
{

	$result = [];
	$deviceType = (!empty($data['phone_type']))?$data['phone_type']:"Android";
	foreach ($packages as $item) {
		//if($item['ETC']=="AMBT"){
			if ($item['devicetype'] == $deviceType && $item['state'] == $data['state']) {
			//$result[] = [
				$packageID = $item['packageId'];
				$providerId = $item['providerId'];
				break;
			//];
			}else if ($item['devicetype'] == $deviceType && $item['state'] === 'All'){
					$packageID = $item['packageId'];
					$providerId = $item['providerId'];
					break;
			}
		//}
	}

	$jsonString = $data['utms'];
    $utms = json_decode($jsonString, true);
	$utm_values="";
    // Loop through the array
    foreach ($utms as $item) {
        // Extract values and join them with hyphens
        $utm_values.= $item. '-';
       // echo $result . PHP_EOL;
    }
   
   
    //$utm_values = $data['utm_source'] . '-' . $data['utm_medium'] . '-' . $data['utm_campaign'] . '-' . $data['utm_content'] . '-' . $data['match_type'] . '-' . $data['utm_adgroup'];
   
	$phone = $data['phone_number'];
	$author = $credentials['author'];
	$child = ($data['is_child_dependent'] == "Yes") ? "true" : "false";

	// if (!empty($data['surgepays_id'])){
	// 	$externalId = $data['surgepays_id'];
	// }else{
	// 	$externalId = ($data["accountype"] == "Fintech") ? $data['terminal_id'] : $utm_values;
	// }
    $externalId = $utm_values;
	
	$language = ($data['language'] == "spanish") ? "Spanish" : "";
	$tribalFlag = !empty($configs['packages']['tribal']) ? "true" : "false";
	$medicalSubscriberId = isset($data['medicalSubscriberId']) && !empty($data['medicalSubscriberId'])
	? $data['medicalSubscriberId']
	: "";

	if($data['state'] == 'CA'){
		$payload = '{
			"Credential": {
				"CLECID": "' . $credentials['CLECID'] . '",
				"UserName": "' . $credentials['UserName'] . '",
				"TokenPassword": "' . $credentials['TokenPassword'] . '",
				"PIN": "' . $credentials['PIN'] . '"
			},
			"ETCData": {
				"ETCFlag": "true",
				"LifelineCertificationTypeID": "' . $data['program_benefit'] . '",
				"TribalFlag": "false",
				"InCoverageAreaFlag": "true",
				"ProofOfBenefitsUploadedFlag": "false",
				"IdentityProofUploadedFlag": "false",
				"TribalID": "' . $data['tribal_id'] . '",
				"MedicalSubscriberId": "' . $medicalSubscriberId . '"
			},		
			"FirstName": "' . $data['first_name'] . '",
			"LastName": "' . $data['second_name'] . '",
			"MiddleInital": "' . $data['middle_initial'] . '",
			"SSN": "' . $data['ssn'] . '",
			"DOB": "' . $data['dob'] . '",
			"HouseNumber": "' . $data['address2'] . '",
			"Street": "' . $data['address1'] . '",
			"City": "' . $data['city'] . '",
			"StateCode": "' . $data['state'] . '",
			"Zipcode": "' . $data['zipcode'] . '",
			"email": "' . $data['email'] . '",
			"PrimaryPhone": "' . $phone . '",
			"WirelessProviderTypeID": "' . $providerId . '",
			"CustomerPackageID": "' . $packageID . '",
			"Author": "' . $author . '",
			"ValidateZipCodeOnly": "true",
			"HasAlternateId":"false",
			"IsChildOrDependent":"' . $child . '",
			"BQPFirstName":"' . $data['bqp_firstname'] . '",
			"BQPLastName":"' . $data['bqp_lastname'] . '",
			"BQPMiddleName":"' . $data['bqp_middlename'] . '",
			"BQPSSN":"' . $data['bqp_ssn'] . '",
			"BQPTribalId":"' . $data['bqp_tribal_id'] . '",
			"BQPDOB":"' . $data['bqp_dob'] . '",
			"HasBQPAlternateId":"false",
			"RepNotAssisted": "true",
			"EnrollConsent":"True",
			"TranferConsent":"True",
			"ExternalId": "' . $externalId . '",
			"Variable":"' . $data['phone_type'] . '",
			"Language":"' . $language . '",
			"ConsentDateTime":"' . $data['datetimeConsent'] . '",
			"ConsentTimeZone":"2"
		}';
	}else{
			$payload = '{
			"Credential": {
				"CLECID": "' . $credentials['CLECID'] . '",
				"UserName": "' . $credentials['UserName'] . '",
				"TokenPassword": "' . $credentials['TokenPassword'] . '",
				"PIN": "' . $credentials['PIN'] . '"
			},
			"ETCData": {
				"ETCFlag": "true",
				"LifelineCertificationTypeID": "' . $data['program_benefit'] . '",
				"TribalFlag": "false",
				"InCoverageAreaFlag": "true",
				"ProofOfBenefitsUploadedFlag": "false",
				"IdentityProofUploadedFlag": "false",
				"TribalID": "' . $data['tribal_id'] . '"
			},		
			"FirstName": "' . $data['first_name'] . '",
			"LastName": "' . $data['second_name'] . '",
			"MiddleInital": "' . $data['middle_initial'] . '",
			"SSN": "' . $data['ssn'] . '",
			"DOB": "' . $data['dob'] . '",
			"HouseNumber": "' . $data['address2'] . '",
			"Street": "' . $data['address1'] . '",
			"City": "' . $data['city'] . '",
			"StateCode": "' . $data['state'] . '",
			"Zipcode": "' . $data['zipcode'] . '",
			"email": "' . $data['email'] . '",
			"PrimaryPhone": "' . $phone . '",
			"WirelessProviderTypeID": "' . $providerId . '",
			"CustomerPackageID": "' . $packageID . '",
			"Author": "' . $author . '",
			"ValidateZipCodeOnly": "true",
			"HasAlternateId":"false",
			"IsChildOrDependent":"' . $child . '",
			"BQPFirstName":"' . $data['bqp_firstname'] . '",
			"BQPLastName":"' . $data['bqp_lastname'] . '",
			"BQPMiddleName":"' . $data['bqp_middlename'] . '",
			"BQPSSN":"' . $data['bqp_ssn'] . '",
			"BQPTribalId":"' . $data['bqp_tribal_id'] . '",
			"BQPDOB":"' . $data['bqp_dob'] . '",
			"HasBQPAlternateId":"false",
			"RepNotAssisted": "true",
			"EnrollConsent":"True",
			"TranferConsent":"True",
			"ExternalId": "' . $externalId . '",
			"Variable":"' . $data['phone_type'] . '",
			"Language":"' . $language . '",
			"ConsentDateTime":"' . $data['datetimeConsent'] . '",
			"ConsentTimeZone":"2"
		}';
	}
       
    return $payload;
}

function tribalUpdate_payload($order_id,$packageID,$credential)
{
	if (isset($credential['author'])){
		$author = $credential['author'];
	}else{
		$author = "Terracom Lifeline Orders";
	}

	$payload = '{
		"Credential": {
			"CLECID": "' . $credential['CLECID'] . '",
			"UserName": "' . $credential['UserName'] . '",
			"TokenPassword": "' . $credential['TokenPassword'] . '",
			"PIN": "' . $credential['PIN'] . '"
		},		
		"SubscriberOrderId": "' . $order_id. '",
		"CustomerPackageID": "' . $packageID . '",		
		"Author": "' . $author . '",		
 	}';

	return $payload;
}

function unavoAPICall($url, $request){
    
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
        CURLOPT_POSTFIELDS => $request,
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
            $result['status']="success";
			$result['msg']="";
        }

    return $result;
}

function getCredentialCLEC($company, $source){
    $payload = array(
        'apikey' => 'U3VyZ2VwYXlzMjQ6VyEybTZASnk4QVFk',
        'company' => $company,
        'source' => $source,
    );
    $url = "https://secure-order-forms.com/surgephone/endpoint/clec_credentials.php";
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL =>$url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($payload),
		CURLOPT_SSL_VERIFYHOST => IS_LOCALHOST ? 0 : 2,
		CURLOPT_SSL_VERIFYPEER => IS_LOCALHOST ? 0 : 1
	));

	$response = curl_exec($curl);
	curl_close($curl);
	return json_decode($response, true);
}

// function getInvoiceShockwavecrm($credentials, $mdn, $orderId = '')
// {
//     $curl = new Curl();
//     $url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/QuerySubscriberInvoices";
//     $request = '{
// 			"Credential": ' . $credentials . ',
// 			"MDN": "' . $mdn . '",
// 			"OrderId": ""
// 		}';
//     $response = $curl->postJsonSimple($url, $request, "");
//     return json_decode($response, true);
// }

function getInvoiceShockwaveByMDN($mdn, $orderId = '')
{
    $curl = new Curl();
    $url = "http://api.surgeholdings.com/api/GetInvoiceData?mdn=" . urlencode($mdn) . "&apikey=adasasd";   
    //$response = $curl->postJsonSimple($url, "GET");
    $response = $curl->simpleGet($url);
    return json_decode($response, true);
}

function UploadDocumentTest($credentials, $order_id, $filename, $fileBase64, $DocumentTypeID){
    $request_1 = '{
		"Credential": {
			"CLECID": "' . $credentials["CLECID"] . '",
			"UserName": "' . $credentials["UserName"] . '",
			"TokenPassword": "' . $credentials["TokenPassword"] . '",
			"PIN": "' . $credentials["PIN"] . '"
	},
	"SubscriberOrderId": "' . $order_id . '",
	"DocumentName": "' . $filename . '",
	"DocumentTypeID": "'. $DocumentTypeID .'",
	"DocumentData": "' .  $fileBase64 . '"
	}';

    $url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/UploadDocument";

    $response_1 ='{
		"Status": "success",
		"StatusText": "Document uploaded",
		"RequestMessageID": null,
		"CheckPoint": 0,
		"ExceptionContext": null
	}';

    return [
        "status"=>"success",
        "msg"=>"Test",
        "url" => $url,
        "request" => $request_1,
        "response" => $response_1,
        "title" => 'upload Doc '.$DocumentTypeID
    ];
}

function UploadDocument($credentials, $order_id, $filename, $fileBase64, $DocumentTypeID,$company)
{

    //$credentials = getCredentialCLEC($company, $source);
    $request_1 = '{
		"Credential": {
			"CLECID": "' . $credentials["CLECID"] . '",
			"UserName": "' . $credentials["UserName"] . '",
			"TokenPassword": "' . $credentials["TokenPassword"] . '",
			"PIN": "' . $credentials["PIN"] . '"
	},
	"SubscriberOrderId": "' . $order_id . '",
	"DocumentName": "' . $filename . '",
	"DocumentTypeID": "'. $DocumentTypeID .'",
	"DocumentData": "' .  $fileBase64 . '"
	}';

    if($company=="NAL"){
		$url = "http://wirelessapi.mvnocloudsolutions.com/PrepaidWireless/UploadDocument";
	}else{
		$url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/UploadDocument";
	}

    $response_1 = unavoAPICall($url, $request_1);
	/*$response = '{
		"Status": "success",
		"StatusText": "Document uploaded",
		"RequestMessageID": null,
		"CheckPoint": 0,
		"ExceptionContext": null
	}';*/
	//$response_1 = json_decode($response_1, true);
     return [
        "status"=>$response_1['status'],
        "msg"=>$response_1['msg'],
        "url" => $url,
        "request" => $request_1,
        "response" => $response_1,
        "title" => 'UploadDocumentAPI_'.$DocumentTypeID
    ];
    //return  $response_1;

}

function UploadDocumentBase64($customer_id, $company, $source, $order_id, $documentName, $fileBase64, $DocumentTypeID)
{

    $credentials = getCredentialCLEC($company, $source);
    $request_1 = '{
		"Credential": {
			"CLECID": "' . $credentials["CLECID"] . '",
			"UserName": "' . $credentials["UserName"] . '",
			"TokenPassword": "' . $credentials["TokenPassword"] . '",
			"PIN": "' . $credentials["PIN"] . '"
	},
	"SubscriberOrderId": "' . $order_id . '",
	"DocumentName": "' . $documentName . '",
	"DocumentTypeID": "'. $DocumentTypeID .'",
	"DocumentData": "' . $fileBase64 . '"
	}';

    $url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/UploadDocument";

    $response_1 = unavoAPICall($url, $request_1);

	$request_1 = '{
		"Credential": {
			"CLECID": "' . $credentials["CLECID"] . '",
			"UserName": "' . $credentials["UserName"] . '",
			"TokenPassword": "' . $credentials["TokenPassword"] . '",
			"PIN": "' . $credentials["PIN"] . '"
	},
	"SubscriberOrderId": "' . $order_id . '",
	"DocumentName": "' . $documentName . '",
	"DocumentTypeID": "'. $DocumentTypeID .'",
	"DocumentData": "' . substr($fileBase64, 0, 100) . '"
	}';

     return [
        "customer_id" => $customer_id,
        "url" => $url,
        "request" => $request_1,
        "response" => $response_1,
        "title" => 'upload Document'
    ];

}

function SubmitIEHForm($data,$initials,$order_id,$customer_id,$company,$source,$configs)
{
	$credential = getCredentialCLEC($company, $source);
	$url = "https://wirelessapi.shockwavecrm.com/PrepaidWireless/SubmitIEHForm";

	$author = $credential['author'];
	$iehpoa = ($data['signingPowerAttorney']== true) ? true : false;
	$IEHQuestion1 = ($data['anotheradult'] == "Yes") ? true : false;
	$IEHQuestion2 = ($data['anotheradultdiscount'] == "Yes") ? true : false;
	$IEHQuestion3 = ($data['anotheradultshareincome'] == "Yes") ? true : false;

	$payload = '{
		"Credential": {
			"CLECID": "' . $credential['CLECID'] . '",
			"UserName": "' . $credential['UserName'] . '",
			"TokenPassword": "' . $credential['TokenPassword'] . '",
			"PIN": "' . $credential['PIN'] . '"
		},
		"SubscriberOrderId": "' . $order_id . '",
		"SubscriberName": "' . $data['first_name'] .' '. $data['second_name'] . '",
		"TypedSignature": "' . $data['signature_text'] .'",	
		"IEHQuestion1": "'. $IEHQuestion1.'",
		"IEHQuestion2": "'. $IEHQuestion2.'",
		"IEHQuestion3":"'. $IEHQuestion3.'",
		"InitialA":"' . $initials['initials10'] . '",
		"InitialB":"' . $initials['initials11'] . '",
		"IEHPOA":"'. $iehpoa .'",		
		"Author": "' . $author . '"
 	}';

	$response = unavoAPICall($url, $payload);
	$IEHFormresponse = json_decode($response, true);
	$IEHStatusText = $IEHFormresponse["StatusText"];
	$IEHFormstatus = ($IEHFormresponse["Status"] === "Success");
	
	return [
		"customer_id" => $customer_id,
		"url" => $url,
		"request" => $payload,
		"response" => $response,
		"title" => "SubmitIEHForm",
		"IEHFormStatus" => $IEHFormstatus,
		"IEHFormStatusText" => $IEHStatusText		
	];
}

function getConsentFile($orderId){

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
        return $result;

    }

