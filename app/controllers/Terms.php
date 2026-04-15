<?php
  class Terms extends Controller {
    public function __construct(){
     
    }
    

    
  public function index()
    {
        $data = ['title' => 'Terms of Service'];
        $this->view('pages/terms', $data);
    }

  }