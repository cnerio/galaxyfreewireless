<?php
  class Pages extends Controller {
    public function __construct(){
     
    }
    
    public function index($agent=NULL){
      // if(isLoggedIn()){
      //   redirect('posts');
      // }
      $data = [
        'agent' => isset($agent) ? strtolower(trim($agent)) : '',
        'csrf_token' => csrf_token('save_lead_form')
      ];
     
      $this->view('pages/index', $data);
    }

    public function indexcheck(){
      
          $this->view('pages/indexcheck');
    }

    public function about(){
      $data = [
        'title' => 'About Us',
        'description' => 'App to share posts with other users'
      ];

      $this->view('pages/about', $data);
    }
    
  public function privacy()
    {
        $data = ['title' => 'Privacy Policy'];
        $this->view('pages/privacy', $data);
    }

    public function terms()
    {
        $data = ['title' => 'Terms of Service'];
        $this->view('pages/terms', $data);
    }

    public function contact(){
      $data = [
          'title' => 'Contact Us',
          'description' => 'You can contact us through this medium',
          'info' => 'You can contact me with the following details below if you like my program and willing to offer me a contract and work on your project',
          'name' => 'Omonzebaguan Emmanuel',
          'location' => 'Nigeria, Edo State',
          'contact' => '+2348147534847',
          'mail' => 'emmizy2015@gmail.com'
      ];

      $this->view('pages/contact', $data);
    }

    public function thankyou($customer_id = null){
      $data = [
        'title' => 'Thank You',
        'customer_id' => $customer_id
      ];

      $this->view('pages/thankyou', $data);
    }

    public function saveLead(){
      header('Content-Type: application/json; charset=utf-8');

      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
        exit;
      }

      $honeypot = trim($_POST['website'] ?? '');
      if ($honeypot !== '') {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Unable to process request.']);
        exit;
      }

      $csrfToken = $_POST['csrf_token'] ?? '';
      if (!verify_csrf_token($csrfToken, 'save_lead_form')) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Session expired. Please refresh the page and try again.']);
        exit;
      }

      // Sanitize inputs
      $zipcode = preg_replace('/\D/', '', trim($_POST['zipcode'] ?? ''));
      $email   = strtolower(filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL));

      // Validate
      if (strlen($zipcode) !== 5) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Please enter a valid 5-digit ZIP code.']);
        exit;
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
      }

      // State/city resolved by client-side ZIP lookup
      $state = strtoupper(preg_replace('/[^A-Za-z]/', '', trim($_POST['state'] ?? '')));
      $state = (strlen($state) === 2) ? $state : '';
      $city  = substr(preg_replace('/[^\p{L}\s\-\.]/u', '', trim($_POST['city'] ?? '')), 0, 100);

      // ── Check coverage via Page model ────────────────────────────────────────
      $pageModel  = $this->model('Page');
      // TX uses ZIP-level GTW check; pass $zipcode for that exception
      $coverage   = $state ? $pageModel->getStateConfig($state, $zipcode) : ['GTW' => 0, 'AMBT' => 0];

      // Determine redirect: GTW first, then AMBT
      $redirectUrl = null;
      if ($coverage['GTW'] === 1) {
        $redirectUrl = 'https://lifeline.truewireless.com/';
      } elseif ($coverage['AMBT'] === 1) {
        $redirectUrl = 'https://americanassistance.us/';
      }

      $leadToken = bin2hex(random_bytes(32));
      $redirectTo = $redirectUrl ? $redirectUrl . '?tk=' . $leadToken : null;

      $data = [
        'zipcode'    => $zipcode,
        'email'      => $email,
        'state'      => $state,
        'city'       => $city,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        'page_url'   => substr($_POST['page_url'] ?? '', 0, 512),
        'created_at' => date('Y-m-d H:i:s'),
        'lead_token' => $leadToken, // 64-character hex token
        'redirect_to' => $redirectUrl
      ];

      try {
        $id = $pageModel->saveLead($data);

        if ($id > 0) {
          echo json_encode([
            'success'      => true,
            'message'      => $redirectUrl
                               ? 'Great news! Lifeline service is available in your area.'
                               : 'Thank you! We will review your information and be in touch soon.',
            'id'           => $id,
            'redirect_url' => $redirectTo,
            'coverage'     => $coverage,
          ]);
        } else {
          http_response_code(500);
          echo json_encode(['success' => false, 'message' => 'Unable to save your information. Please try again.']);
        }
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'A server error occurred. Please try again.']);
      }

      exit;
    }
  }