<?php 
  class Packages extends Controller {
    public $packageModel;
    public function __construct(){
      $this->packageModel = $this->model('Package');
    }

    public function index(){
        $this->view('packages/index');
    }
}