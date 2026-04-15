<?php
class Users extends Controller{
    public $userModel;
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function hashed($string){
        return password_hash($string, PASSWORD_DEFAULT);
    }

    public function adduser(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            //$confirmPassword = trim($_POST['addconfirm_password']);
            $data=[
                "name"=>trim(ucfirst(strtolower($_POST['addname']))),
                "email"=>trim(strtolower($_POST['addemail'])),
                "password"=>trim($_POST['addpassword']),
                "rol"=>$_POST['addrol']
            ];
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $userId = $this->userModel->saveUser($data);
            if($userId){
                $result = [
                    "status"=>"success",
                    "user_id"=>$userId,
                    "msg"=>"User added successfully"
                ];
            }else{
                $result = [
                    "status"=>"error",
                    "msg"=>"Somthing Wrong adding user"
                ];
            }

            echo json_encode($result);
        }
    }

       public function updateuser(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            //$confirmPassword = trim($_POST['addconfirm_password']);
            $data=[
                "name"=>trim(ucfirst(strtolower($_POST['editname']))),
                "email"=>trim(strtolower($_POST['editemail'])),
                "rol"=>$_POST['editrol'],
                "id"=>$_POST['id_user']
            ];
            if($_POST['resetPass']==="Y"){
                 $data['password'] = password_hash($_POST['editpass'], PASSWORD_DEFAULT);
            }
           
            $userId = $this->userModel->updateuser($data);
            if($userId){
                $result = [
                    "status"=>"success",
                    "msg"=>"User updated successfully"
                ];
            }else{
                $result = [
                    "status"=>"error",
                    "user_id"=>$userId,
                    "msg"=>"Something wrong updating infor"
                ];
            }

            echo json_encode($result);
        }
    }

    public function removeUser(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            //$confirmPassword = trim($_POST['addconfirm_password']);
            $data=[
                "id"=>$_POST['id'],
                "active"=>0
            ];
           
            $userId = $this->userModel->updateuser($data);
            if($userId){
                $result = [
                    "status"=>"success",
                    "msg"=>"User removed successfully"
                ];
            }else{
                $result = [
                    "status"=>"error",
                    "msg"=>"Something wrong removing user"
                ];
            }

            echo json_encode($result);
        }
    }

    public function register(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
           // process form
           //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '' 
            ];

            //valide name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }

            //validate email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }else{
                //check for email
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email already exist';
                }
            }

            //validate password 
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter your password';
            }elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be atleast six characters';
            }

            //validate confirm password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm password';
            }else{
                if($data['password'] != $data['confirm_password'])
                {
                    $data['confirm_password_err'] = 'Password does not match';
                }
            }

            //make sure error are empty
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['password_confirm_err'])){
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                if($this->userModel->register($data)){
                    flash('register_success', 'you are registerd you can login now');
                    redirect('users/login');
                }
            }else{
                $this->view('users/register', $data);
            }
        }else{
            //init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '' 
            ];
            //load view
            $this->view('users/register', $data);          
        }
    }

    public function login(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
           // process form
           //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
           $data = [
               'email' => trim($_POST['email']),
               'password' => trim($_POST['password']),
               'email_err' => '',
               'password_err' => ''
           ];

            //validate email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }else{
                if($this->userModel->findUserByEmail($data['email'])){
                    //user found
                }else{
                    $data['email_err'] = 'User not found';
                }
            }

            //validate password 
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter your password';
            }elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be atleast six characters';
            }
            
            //make sure error are empty
            if(empty($data['email_err']) && empty($data['password_err'])){
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                if($loggedInUser){
                    //create session
                    $this->createUserSession($loggedInUser);
                }else{
                    $data['password_err'] = 'Password incorrect';
                    $this->view('users/login', $data);
                }
            }else{
                $this->view('users/login', $data);
            }

        }else{
            //init data f f
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            //load view
            $this->view('users/login', $data);          
        }
    }

    //setting user section variable
    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['name'] = $user->name;
        $_SESSION['email'] = $user->email;
        $_SESSION['rol'] = $user->rol;
        redirect('records/index');
    }

    //logout and destroy user session
    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['rol']);
        session_destroy();
        redirect('users/login');
    }

    public function admin(){
        //$data = $this->userModel->getAllUsers();
        $this->view('users/admin');
    }

    public function getUser(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
           // $_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
            $id=$_POST['id'];
            $result = $this->userModel->getUserById($id);
            echo json_encode($result);
        }
    }

    public function read(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
		//if($_POST){
			//die('Submit');
			//$_POST= filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
			$page = (isset($_POST['page']) && !empty($_POST['page']))?$_POST['page']:1;
			$data = [
				'action'=>trim($_POST['action']),
				'firstload'=>$_POST['firstload'],
				'arrayCampos'=>(empty($_POST['search']))?[]:$_POST['search'],
				'order_by'=>'id desc',
				'length'=>$_POST['length'],
				'page'=>$page,
				'per_page'=>'',
				'adjacents'=>'',
				'offset'=>'',
				'offsetToShow'=>'',
				'numrows'=>'',
				'total_pages'=>'',
				'c'=>'',
				'pagination'=>'',
			];
			//print_r($data);
			//die('Submit');
		}else{
			$data = [
				'action'=>'',
				'firstload'=>'YES',
				'arrayCampos'=>[],
				'order_by'=>'id desc',
				'length'=>10,
				'page'=>1,
				'per_page'=>'',
				'adjacents'=>'',
				'offset'=>'',
				'offsetToShow'=>'',
				'numrows'=>'',
				'total_pages'=>'',
				'c'=>'',
				'pagination'=>'',
				'fields'=>'',
			];
				}
			
			
		
			//print_r($data);
			$camposBase=array("name","email","rol","active");
			$addWhere="";
			$count=0;
			for($index=0;$index<count($data['arrayCampos']);$index++){
				$count += ($data['arrayCampos'][$index]!='')?1:0;
				
					if(!empty($data['arrayCampos'][$index])){

                        if($camposBase[$index]=="rol" || $camposBase[$index]=="active"){
                            if($count<=1){
								$addWhere.=" ".$camposBase[$index]." = ".$data['arrayCampos'][$index]."";
							}else{
								$addWhere.=" and ".$camposBase[$index]." = ".$data['arrayCampos'][$index]."";
							}
                        }else{
                            if($count<=1){
								$addWhere.=" ".$camposBase[$index]." LIKE '%".$data['arrayCampos'][$index]."%'";
							}else{
								$addWhere.=" and ".$camposBase[$index]." LIKE '%".$data['arrayCampos'][$index]."%'";
							}
                        }

							


					}
				}
			//$status  = $this->getOrderStatus();
			
			$consultaBusqueda = "";
			$contarCuantasBusquedas = 0;
            $camposAscDesc="";
			$per_page = $data['length']; //la cantidad de registros que desea mostrar
			$adjacents  = 2; //brecha entre páginas después de varios adyacentes
			$offset = ($data['page'] - 1) * $per_page;
			$offsetnumeroMostrar = ($data['page']-1) * $per_page + 1;
			$numrows = $this->userModel->countRegisters($addWhere,$data['firstload']);
			$total_pages = ceil($numrows/$per_page);
			$reload = 'index.php';
			$data['per_page']=$per_page;
			$data['adjacents']=$adjacents;
			$data['offset']=$offset;
			$data['offsetToShow'] = $offsetnumeroMostrar;
			$data['numrows']=$numrows;
			$data['total_pages']=$total_pages;
			$paginate = $this->paginate($reload,$data['page'] , $total_pages, $adjacents, $data['arrayCampos'],$data['length'],$camposAscDesc);
			$data['pagination']=$paginate;
			//$per_page = 30; //la cantidad de registros que desea mostrar
			
			$getOrders = $this->userModel->getData($data['offset'],$data['per_page'],$addWhere,$data['order_by'], $data['firstload']);

			$data['fields']=$getOrders;
		
			echo json_encode($data);
			//return $getOrders;
			
			//$this->view('dashboard/index',$data);
		//header('Content-type: application/json; charset=utf-8');
			
		}

	
	
	public function paginate($reload, $page, $tpages, $adjacents,$ArrayCampos,$example_length,$camposAscDesc) {

		//$ArrayCampos="";
			$ArrayCampos = json_encode($ArrayCampos);
			$camposAscDesc = json_encode($camposAscDesc);
			//print("<pre>".print_r($ArrayCampos,true)."</pre>");
			//$camposAscDesc="";
		
			$prevlabel = "&lsaquo;";
			$nextlabel = "&rsaquo;";
			$out = '<ul class="pagination">';
			 
			// previous label
		
			if($page==1) {
				$out.= "<li class='page-item disabled'><span><a class='page-link'>$prevlabel</a></span></li>";
			} else if($page==2) {
				$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(1,".$ArrayCampos.",".$example_length.",".$camposAscDesc.")'>$prevlabel</a></li>";
			}else {
				$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(".($page-1).",$ArrayCampos,$example_length,$camposAscDesc)'>$prevlabel</a></li>";
		
			}
			
			// first label
			if($page>($adjacents+1)) {
				$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(1,".$ArrayCampos.",".$example_length.",".$camposAscDesc.")'>1</a></li>";
			}
			// interval
			if($page>($adjacents+2)) {
				$out.= "<li class='page-item'><a class='page-link'>...</a></li>";
			}
		
			// pages
		
			$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
			$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
			for($i=$pmin; $i<=$pmax; $i++) {
				if($i==$page) {
					$out.= "<li class='page-item active'><a class='page-link'>$i</a></li>";
				}else if($i==1) {
					$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(1,".$ArrayCampos.",".$example_length.",".$camposAscDesc.")'>$i</a></li>";
				}else {
					$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(".$i.",$ArrayCampos,$example_length,$camposAscDesc)'>$i</a></li>";
				}
			}
		
			// interval
		
			if($page<($tpages-$adjacents-1)) {
				$out.= "<li class='page-item'><a class='page-link'>...</a></li>";
			}
		
			// last
		
			if($page<($tpages-$adjacents)) {
				$out.= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load($tpages,".$ArrayCampos.",".$example_length.",".$camposAscDesc.")'>$tpages</a></li>";
			}
		
			// next
		
			if($page<$tpages) {
				$out.= "<li class='page-item'><span><a class='page-link' href='javascript:void(0);' onclick='load(".($page+1).",$ArrayCampos,$example_length,$camposAscDesc)'>$nextlabel</a></span></li>";
			}else {
				$out.= "<li class='page-item disabled'><span><a class='page-link'>$nextlabel</a></span></li>";
			}
			
			$out.= "</ul>";
			return $out;
		}
}