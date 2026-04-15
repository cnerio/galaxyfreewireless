<?php

class AmbtApiHelper
{
  private $endpoint;
  private $uploadDocumentEndpoint;

  public function __construct($endpoint = null)
  {
    $this->endpoint = $endpoint ?: AMBT_ADD_SUBSCRIBER_URL;
    $this->uploadDocumentEndpoint = AMBT_UPLOAD_DOCUMENT_URL;
  }

  public function selectPackageForCustomer($customerData, $packages)
  {
    $deviceType = !empty($customerData['phone_type']) ? $customerData['phone_type'] : 'Android';
    if (strcasecmp($deviceType, 'iPhone') === 0) {
      $deviceType = 'iOS';
    }
    $state = strtoupper(trim($customerData['state'] ?? ''));

    foreach ($packages as $item) {
      if (
        isset($item['devicetype'], $item['state'])
        && strcasecmp($item['devicetype'], $deviceType) === 0
        && strtoupper($item['state']) === $state
      ) {
        return $item;
      }
    }

    foreach ($packages as $item) {
      if (isset($item['devicetype'], $item['state']) && strcasecmp($item['devicetype'], $deviceType) === 0 && strtoupper($item['state']) === 'ALL') {
        return $item;
      }
    }

    return isset($packages[0]) ? $packages[0] : [];
  }

  public function buildAddSubscriberPayload($customerData, $credentialData, $packageData = [])
  {
    $ssn = preg_replace('/\D/', '', (string)($customerData['ssn'] ?? ''));
    $phone = preg_replace('/\D/', '', (string)($customerData['phone_number'] ?? ''));
    $dob = $this->formatDob($customerData['dob'] ?? '');

    $providerId = (string)($packageData['providerId'] ?? $credentialData['providerId'] ?? '100001');
    $customerPackageId = (string)($packageData['packageId'] ?? $credentialData['packageId'] ?? '');
    $programBenefit = (string)($customerData['program_benefit'] ?? '100004');

    $consentDateTime = !empty($customerData['datetimeConsent'])
      ? $customerData['datetimeConsent']
      : date('Y-m-d H:i:s');

    $payload = [
      'Credential' => [
        'CLECID' => (string)($credentialData['CLECID'] ?? ''),
        'UserName' => (string)($credentialData['UserName'] ?? ''),
        'TokenPassword' => (string)($credentialData['TokenPassword'] ?? ''),
        'PIN' => (string)($credentialData['PIN'] ?? '')
      ],
      'ETCData' => [
        'ETCFlag' => 'true',
        'LifelineCertificationTypeID' => $programBenefit,
        'TribalFlag' => $this->toApiBoolean(!empty($customerData['tribal']) || !empty($customerData['is_tribal'])),
        'InCoverageAreaFlag' => 'true',
        'ProofOfBenefitsUploadedFlag' => 'false',
        'IdentityProofUploadedFlag' => 'false',
        'TribalID' => (string)($customerData['tribal_id'] ?? '')
      ],
      'FirstName' => (string)($customerData['first_name'] ?? ''),
      'LastName' => (string)($customerData['second_name'] ?? ''),
      'MiddleInital' => (string)($customerData['middle_initial'] ?? ''),
      'SSN' => substr($ssn, -4),
      'DOB' => $dob,
      'HouseNumber' => (string)($customerData['address2'] ?? ''),
      'Street' => (string)($customerData['address1'] ?? ''),
      'City' => (string)($customerData['city'] ?? ''),
      'StateCode' => (string)($customerData['state'] ?? ''),
      'Zipcode' => (string)($customerData['zipcode'] ?? ''),
      'email' => (string)($customerData['email'] ?? ''),
      'PrimaryPhone' => substr($phone, 0, 10),
      'WirelessProviderTypeID' => $providerId,
      'CustomerPackageID' => $customerPackageId,
      'Author' => (string)($credentialData['author'] ?? 'Online Orders'),
      'ValidateZipCodeOnly' => 'true',
      'HasAlternateId' => 'false',
      'IsChildOrDependent' => $this->toApiBoolean(($customerData['is_child_dependent'] ?? '') === 'Yes'),
      'BQPFirstName' => (string)($customerData['bqp_firstname'] ?? ''),
      'BQPLastName' => (string)($customerData['bqp_lastname'] ?? ''),
      'BQPMiddleName' => (string)($customerData['bqp_middlename'] ?? ''),
      'BQPSSN' => (string)($customerData['bqp_ssn'] ?? ''),
      'BQPTribalId' => (string)($customerData['bqp_tribal_id'] ?? ''),
      'BQPDOB' => (string)($customerData['bqp_dob'] ?? ''),
      'HasBQPAlternateId' => 'false',
      'RepNotAssisted' => 'true',
      'EnrollConsent' => 'True',
      'TranferConsent' => 'True',
      'ExternalId' => (string)($customerData['customer_id'] ?? ''),
      'Variable' => (string)($customerData['phone_type'] ?? 'Android'),
      'Language' => ($customerData['language'] ?? '') === 'spanish' ? 'Spanish' : '',
      'ConsentDateTime' => $consentDateTime,
      'ConsentTimeZone' => '2'
    ];

    return $payload;
  }

  public function submitAddSubscriberOrder($payload)
  {
    $requestBody = json_encode($payload);

    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $this->endpoint,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $requestBody,
      CURLOPT_SSL_VERIFYHOST => IS_LOCALHOST ? 0 : 2,
      CURLOPT_SSL_VERIFYPEER => IS_LOCALHOST ? 0 : 1,
      CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
      ]
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $curlErrno = curl_errno($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($curlErrno) {
      return [
        'status' => 'error',
        'msg' => $curlError,
        'url' => $this->endpoint,
        'request' => $requestBody,
        'response' => null
      ];
    }

    $decoded = json_decode($response, true);

    if ($httpCode >= 400) {
      return [
        'status' => 'error',
        'msg' => 'HTTP ERROR CODE: ' . $httpCode,
        'url' => $this->endpoint,
        'request' => $requestBody,
        'response' => $response
      ];
    }

    return [
      'status' => 'success',
      'url' => $this->endpoint,
      'request' => $requestBody,
      'response' => $decoded ?: $response
    ];
  }

  public function uploadDocument($credentialData, $orderId, $documentName, $base64Input, $documentTypeId)
  {
    $normalizedBase64 = $this->normalizeBase64DocumentInput($base64Input);
    if ($normalizedBase64 === '') {
      return [
        'status' => 'error',
        'msg' => 'Invalid base64 document payload.',
        'url' => $this->uploadDocumentEndpoint,
        'request' => null,
        'response' => null
      ];
    }

    $payload = [
      'Credential' => [
        'CLECID' => (string)($credentialData['CLECID'] ?? ''),
        'UserName' => (string)($credentialData['UserName'] ?? ''),
        'TokenPassword' => (string)($credentialData['TokenPassword'] ?? ''),
        'PIN' => (string)($credentialData['PIN'] ?? '')
      ],
      'SubscriberOrderId' => (string)$orderId,
      'DocumentName' => (string)$documentName,
      'DocumentTypeID' => (string)$documentTypeId,
      'DocumentData' => $normalizedBase64
    ];

    return $this->postJson($this->uploadDocumentEndpoint, $payload);
  }

  private function postJson($url, $payload)
  {
    $requestBody = json_encode($payload);

    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $requestBody,
      CURLOPT_SSL_VERIFYHOST => IS_LOCALHOST ? 0 : 2,
      CURLOPT_SSL_VERIFYPEER => IS_LOCALHOST ? 0 : 1,
      CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
      ]
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $curlErrno = curl_errno($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($curlErrno) {
      return [
        'status' => 'error',
        'msg' => $curlError,
        'url' => $url,
        'request' => $requestBody,
        'response' => null
      ];
    }

    $decoded = json_decode($response, true);

    if ($httpCode >= 400) {
      return [
        'status' => 'error',
        'msg' => 'HTTP ERROR CODE: ' . $httpCode,
        'url' => $url,
        'request' => $requestBody,
        'response' => $response
      ];
    }

    return [
      'status' => 'success',
      'url' => $url,
      'request' => $requestBody,
      'response' => $decoded ?: $response
    ];
  }

  private function normalizeBase64DocumentInput($base64Input)
  {
    $base64Input = trim((string)$base64Input);
    if ($base64Input === '') {
      return '';
    }

    if (strpos($base64Input, 'base64,') !== false) {
      $base64Input = substr($base64Input, strpos($base64Input, 'base64,') + 7);
    }

    return str_replace(' ', '+', $base64Input);
  }

  private function toApiBoolean($value)
  {
    return $value ? 'true' : 'false';
  }

  private function formatDob($dob)
  {
    $dob = trim((string)$dob);
    if ($dob === '') {
      return '';
    }

    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dob) === 1) {
      return $dob;
    }

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob) === 1) {
      $date = DateTime::createFromFormat('Y-m-d', $dob);
      return $date ? $date->format('m/d/Y') : '';
    }

    $timestamp = strtotime($dob);
    if ($timestamp === false) {
      return '';
    }

    return date('m/d/Y', $timestamp);
  }
}
