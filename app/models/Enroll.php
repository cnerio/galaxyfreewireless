<?php 

class Enroll {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function getLifelinePrograms(){
            $this->db->query('SELECT * FROM lifeline_programs where active = 1;');
            $result = $this->db->resultSet();

            return $result;
        }

    public function getAgreementsItems($states){
            $this->db->query('SELECT * FROM lifeline_agrements_items WHERE active = 1 and states="all" or states=:states;');
            $this->db->bind(":states",$states);
            $result = $this->db->resultSet();

            return $result;
        }

    public function saveData($data,$table){
        $this->db->insertQuery($table,$data);
        $id=$this->db->lastinsertedId();
        return $id;
    }

    public function getCustomerData($customerId){
        $this->db->query("SELECT * FROM lifeline_records WHERE customer_id=:customer_id;");
        $this->db->bind(":customer_id",$customerId);
        $result = $this->db->resultSet();
        return $result;
    }

    public function getCustomerbyOrderId($orderId){
        $this->db->query("SELECT * FROM lifeline_records WHERE order_id=:orderId;");
        $this->db->bind(":orderId",$orderId);
        $result = $this->db->resultSet();
        return $result;
        //return "hola".$orderId;
    }

    public function updateData($data,$table){
        $this->db->updateQuery($table,$data,"customer_id=:customer_id");
    }

    public function updateDataById($data,$table){
       $result =  $this->db->updateQuery($table,$data,"id=:id");
       return $result;
    }

    public function updateDocStatus($data,$table){
        $result = $this->db->updateQuery($table,$data,"id_lifeline_doc=:id_lifeline_doc");
        return $result;
    }

    public function updateCusId($lastId,$customerId,$table){
        $data=[
            "id"=>$lastId,
            "customer_id"=>$customerId
        ];
        $this->db->updateQuery($table,$data,"id=:id");

    }

    public function getCredentials($company){
         $this->db->query('SELECT * FROM clec_credentials where active = 1 AND company= :company;');
            $this->db->bind(":company",$company);
            $result = $this->db->resultSet();

            return $result;
    }

    public function getPackages($company){
        $this->db->query("SELECT * FROM packages WHERE active=1 and ETC=:company;");
        $this->db->bind(":company",$company);
        $result = $this->db->resultSet();
        return $result;
    }

    public function getTGPackages($state,$company,$isTribal){
        ///echo $company;
        $this->db->query("SELECT * FROM packages WHERE active=1 AND provider=:company AND state=:states AND tribal=:isTribal;");
        $this->db->bind(":company",$company);
        $this->db->bind(":states",$state);
        $this->db->bind(":isTribal",$isTribal);
        $result = $this->db->resultSet();
        //print_r($result);
        return $result;
    }

    public function checkIdFile($customerId){
        $this->db->query("SELECT * FROM lifeline_documents WHERE customer_id=:custId and type_doc='ID';");
        $this->db->bind(":custId",$customerId);
        $result = $this->db->single();
        return $result;
    }

    public function getFiles($customerId,$filetype){
        $this->db->query("SELECT * FROM lifeline_documents WHERE customer_id=:custId and type_doc=:filetype;");
        $this->db->bind(":custId",$customerId);
        $this->db->bind(":filetype",$filetype);
        $result = $this->db->single();
        return $result;
    }

    public function getAllFiles($customerId){
        //$this->db->query("SELECT * FROM lifeline_documents WHERE customer_id=:custId");
        $this->db->query('SELECT distinct(type_doc),to_unavo,filepath,type_doc FROM lifeline_documents WHERE customer_id=:custId AND to_unavo=0 AND type_doc in ("ID","POB")');
        $this->db->bind(":custId",$customerId);
        $result = $this->db->resultSet();
        return $result;
    }

    /**
     * Check if the customer has a saved ID or POB document in lifeline_documents.
     *
     * @param int|string $customerId
     * @return bool
     */
    public function hasIdOrPobDocument($customerId){
        $this->db->query("SELECT COUNT(*) AS total FROM lifeline_documents WHERE customer_id=:custId AND type_doc IN ('ID','POB')");
        $this->db->bind(":custId", $customerId);
        $row = $this->db->single();

        if (!$row) {
            return false;
        }

        return (int)$row->total > 0;
    }

    public function getStates($company){
        switch($company){
            case "AMBT":
                $query = "SELECT abrv FROM lifeline_states WHERE AMBT=1;";
                break;
            case "GTW":
                $query = "SELECT abrv FROM lifeline_states WHERE GTW=1;";
                break;
            case "NAL":
                $query = "SELECT abrv FROM lifeline_states WHERE NAL=1;";
                break;
        }
        $this->db->query($query);
        $response = $this->db->resultSet();
        $result = array_column($response, 'abrv');
        return $result;
    }

    public function getZipcodes($company){
        switch($company){
            case "AMBT":
                $query = "SELECT zipcode FROM lifeline_zipcodes WHERE AMBT=1;";
                break;
            case "GTW":
                $query = "SELECT zipcode FROM lifeline_zipcodes WHERE GTW=1;";
                break;
        }
        $this->db->query($query);
        $response = $this->db->resultSet();
        $result = array_column($response, 'zipcode');
        return $result;
    }

}