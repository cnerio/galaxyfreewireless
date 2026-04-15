<?php
/*
Telgoo 5 API Services
*/
class LifelineApiHandler {
    private $baseUrl;
    private $vendorId;
    private $username;
    private $password;
    private $pin;
    private $token;
    private $lastResponse;
    public $enrollModel;
    public $customerId;

    public function __construct($baseUrl, $vendorId, $username, $password, $pin,$model, $customerId) {
        $this->baseUrl  = rtrim($baseUrl, '/');
        $this->vendorId = $vendorId;
        $this->username = $username;
        $this->password = $password;
        $this->pin      = $pin;
        $this->enrollModel = $model;
        $this->customerId = $customerId;
    }

    /**
     * Authenticate to API and store token
     */
    public function authenticate() {
        $payload = [
            "vendor_id" => $this->vendorId,
            "username"  => $this->username,
            "password"  => $this->password,
            "pin"       => $this->pin,
        ];

        $response = $this->request("authenticate", $payload, "POST", false);

        if (!empty($response['token'])) { // adjust if API returns 'access_token'
            $this->token = $response['token'];
            return true;
        }

        throw new Exception("Authentication failed: " . json_encode($response));
    }

    /**
     * Generic CURL Request
     */
    private function request($endpoint, $payload = [], $method = "POST", $useToken = true) {
        $url = $this->baseUrl . '/' . $endpoint;
        $data['title'] = $endpoint;
        $data['url'] = $url;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // headers
        $headers = [
            "Content-Type: application/json",
        ];
        if ($useToken && $this->token) {
           // $headers[] = "Authorization: Bearer {$this->token}";
           $headers[] = "token:{$this->token}";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // body
        if (!empty($payload)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            $data['request'] = json_encode($payload);
        }
        // Disable SSL verification only in local development.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, IS_LOCALHOST ? 0 : 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, IS_LOCALHOST ? 0 : 1);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }
        $data['response'] = $response;

        $decoded = json_decode($response, true);
        $this->lastResponse = [
            'http_code' => $httpCode,
            'body'      => $decoded,
            'raw'       => $response,
        ];

        
        $data['customer_id']=$this->customerId;
        $this->enrollModel->saveData($data,'lifeline_apis_log');

        return $decoded;
    }

    public function getLastResponse() {
        return $this->lastResponse;
    }

    // Step 1: Check Service Availability
    public function checkServiceAvailability($data) {
        return $this->request("enrollment", $data);
    }

    // Step 2: Address Validation
    public function addressValidation($data) {
        return $this->request("address", $data);
    }

    // Step 3: Eligibility Check
    public function eligibilityCheck($data) {
        return $this->request("nlad", $data);
    }

    // Step 4: Programs Income List
    public function programsIncomeList($data) {
        return $this->request("programs", $data);
    }


    // Step 5: Plan List
    public function planList($data) {
        return $this->request("plan", $data);
    }

    // Step 6: Create Lifeline Customer
    public function createLifelineCustomer($data) {
        return $this->request("customer", $data);
    }

    // Step 7: UPload Documents
    public function uploadDocuments($data) {
        return $this->request("customer", $data);
    }
    /**
     * Run the full process in sequence
     */
    public function runFullProcess($data) {
        $results = [];

        // Step 0: Authenticate
        $this->authenticate();
        $results['authenticate'] = "OK";

        // Step 1
        $results['check_service_availability'] = $this->checkServiceAvailability($data['availability'] ?? []);

        // Step 2
        $results['address_validation'] = $this->addressValidation($data['address'] ?? []);

        // Step 3
        $results['eligibility_check'] = $this->eligibilityCheck($data['eligibility'] ?? []);

        // Step 4
        $results['programs_income_list'] = $this->programsIncomeList($data['income'] ?? []);

        // Step 5
        $results['plan_list'] = $this->planList($data['plans'] ?? []);

        // Step 6
        $results['create_lifeline_customer'] = $this->createLifelineCustomer($data['customer'] ?? []);

        return $results;
    }
}
