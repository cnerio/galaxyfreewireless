
<?php
class Main extends Controller {
  public function __construct(){
  }

  public function index($agent = NULL){
    require_once '../app/controllers/Pages.php';
    $pages = new Pages();
    $pages->index($agent);
  }
}