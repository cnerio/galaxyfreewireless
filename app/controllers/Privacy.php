<?php
  class Privacy extends Controller {
    public function __construct(){
     
    }
    

    
  public function index()
    {
        $data = ['title' => 'Privacy Policy'];
        $this->view('pages/privacy', $data);
    }

  }