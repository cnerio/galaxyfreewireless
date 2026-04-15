<?php
class User {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    //register new user
    public function register($data){
        //By default users are rol 0 (Rol 1 = Admin / Rol 0 = Others)
        $this->db->query('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        

        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function saveUser($data){
        $this->db->insertQuery('users',$data);
        $result = $this->db->lastinsertedId();
        return $result;
    }

    public function updateUser($data){
        return $this->db->updateQuery('users',$data,'id=:id');
    }
    //find user by email
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->singleObj();

        //check the row 
        if($this->db->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function login($email, $password){
        $this->db->query('SELECT * FROM users where email = :email');
        $this->db->bind(':email', $email);
       
        $row = $this->db->singleObj();
        //print_r($row); 
        $hash_password = $row->password;

        if($row && password_verify($password, $hash_password)){
            return $row;
        }else{
            return false;
        }
    }

    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->singleObj();

        return $row;
    }

    public function getStaff(){
		$this->db->query("SELECT id,name,email FROM users WHERE rol=0 AND active=1 order by name ASC");
		$this->db->execute();
		$row = $this->db->resultSet();
		return $row;
	}

    public function getAllUsers(){
		$this->db->query("SELECT id,name,email,rol FROM users WHERE  active=1 order by name ASC");
		$this->db->execute();
		$row = $this->db->resultSet();
		return $row;
	}

    public function countRegisters($search,$firstload){

			if ($firstload=="YES"){
				
					$this->db->query("SELECT count(*) as total FROM users order by id  desc");

			}else{

				if ($search!="") {
					$this->db->query("SELECT count(*) as total FROM users WHERE $search order by id desc");
				}else{
					$this->db->query("SELECT count(*) as total FROM users order by id  desc");
				}


			}

			$this->db->execute();
			$count = $this->db->single();
			return $count['total'];
	}

    public function getData($offset,$per_page,$search,$orderby,$firstload){
		$date_now = date('Y-m-d').'%';
		//echo "select firstname,lastname,email_address,email_status,CAST(email_open_datetime AS DATE) as date_opened,delivered,received,unsubscribe from mailCampaigns.contacts  ORDER BY $orderby limit $offset,$per_page;";
			


				if ($search!="") {
					$this->db->query("select * from users WHERE $search   ORDER BY $orderby  limit $offset,$per_page;");
                    //echo "select * from users WHERE $search   ORDER BY $orderby  limit $offset,$per_page;";
					
				}else{
					$this->db->query("select * from users ORDER BY $orderby limit $offset,$per_page;");
                    //echo "select * from users ORDER BY $orderby limit $offset,$per_page;";
					
				}	
		$this->db->execute('Read');
	$getOrders = $this->db->resultSet();
	return $getOrders;
	}
}