<?php
  /*
   * App Core Class
   * Creates URL & loads core controller
   * URL FORMAT - /controller/method/params
   */
  class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){

      $url = $this->getUrl();
      // Look in controllers for first value
      if(isset($url[0]) && $url[0] !== ''){
        if(file_exists('../app/controllers/' . ucwords($url[0]). '.php')){
          // If exists, set as controller
          $this->currentController = ucwords($url[0]);
          // Unset 0 Index
          unset($url[0]);
        } else {
          // Controller does not exist -> 404
          $this->trigger404();
          return;
        }
      }

      // Require the controller
      require_once '../app/controllers/'. $this->currentController . '.php';

      // Instantiate controller class
      $this->currentController = new $this->currentController;

      // Check for second part of url
      if(isset($url[1])){
        // Check to see if method exists in controller and is callable
        if(method_exists($this->currentController, $url[1]) && is_callable([$this->currentController, $url[1]]) && substr($url[1], 0, 2) !== '__'){
          $this->currentMethod = $url[1];
          // Unset 1 index
          unset($url[1]);
        } else {
          // Method specified but does not exist -> 404
          $this->trigger404();
          return;
        }
      } else {
        // If method is not specified, check if default index exists and is callable
        if(!method_exists($this->currentController, $this->currentMethod) || !is_callable([$this->currentController, $this->currentMethod])){
          $this->trigger404();
          return;
        }
      }

      // Get params
      $this->params = $url ? array_values($url) : [];

      // Call a callback with array of params
      call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    private function trigger404(){
      http_response_code(404);
      $pagesControllerFile = '../app/controllers/Pages.php';
      if(file_exists($pagesControllerFile)){
        require_once $pagesControllerFile;
        $controller = new Pages();
        if(method_exists($controller, 'notFound')){
          $controller->notFound();
          return;
        }
      }
      echo '<h1>404 Page Not Found</h1>';
      echo '<p>The requested page does not exist.</p>';
    }

    public function getUrl(){
      if(isset($_GET['url'])){
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
      }
    }
  } 
  
  