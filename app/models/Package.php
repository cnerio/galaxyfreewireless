<?php 

class Package {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
}