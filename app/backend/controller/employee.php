<?php
/**
 * Created by PhpStorm.
 * User: aurelien
 * Date: 26-07-16
 * Time: 08:28
 */
class backend_controller_employee extends backend_db_employee
{
    protected $message, $template, $header;
    public $lastname_admin,
		$firstname_admin,
		$pseudo_admin,
		$email_admin,
		$passwd_admin,
		$title_admin,
		$phone_admin = NULL,
		$address_admin = NULL,
		$postcode_admin = NULL,
		$city_admin = NULL,
		$country_admin = NULL,
		$id_role,
		$active_admin;
    public $edit, $action, $tabs;
	public $search = array();
	public $employee;
	public $id_admin;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->message = new component_core_message();
        $this->template = new backend_model_template();
        $this->header = new http_header();
        $formClean = new form_inputEscape();
        if (http_request::isPost('lastname_admin')) {
            $this->lastname_admin = $formClean->simpleClean($_POST['lastname_admin']);
        }
        if (http_request::isPost('firstname_admin')) {
            $this->firstname_admin = $formClean->simpleClean($_POST['firstname_admin']);
        }
        if (http_request::isPost('pseudo_admin')) {
            $this->pseudo_admin = $formClean->simpleClean($_POST['pseudo_admin']);
        }
        if (http_request::isPost('email_admin')) {
            $this->email_admin = $formClean->simpleClean($_POST['email_admin']);
        }
        if (http_request::isPost('passwd_admin')) {
            $this->passwd_admin = filter_escapeHtml::clean(filter_rsa::hashEncode('sha1',$_POST['passwd_admin']));
        }
        if (http_request::isPost('title_admin')) {
            $this->title_admin = $formClean->simpleClean($_POST['title_admin']);
        }
        if (http_request::isPost('phone_admin')) {
            $this->phone_admin = $formClean->simpleClean($_POST['phone_admin']);
        }
        if (http_request::isPost('address_admin')) {
            $this->address_admin = $formClean->simpleClean($_POST['address_admin']);
        }
        if (http_request::isPost('postcode_admin')) {
            $this->postcode_admin = $formClean->simpleClean($_POST['postcode_admin']);
        }
        if (http_request::isPost('city_admin')) {
            $this->city_admin = $formClean->simpleClean($_POST['city_admin']);
        }
        if (http_request::isPost('country_admin')) {
            $this->country_admin = $formClean->simpleClean($_POST['country_admin']);
        }
        if (http_request::isPost('id_role')) {
            $this->id_role = $formClean->numeric($_POST['id_role']);
        }
        if (http_request::isPost('id')) {
            $this->id_admin = $formClean->simpleClean($_POST['id']);
        }
        if (http_request::isPost('active_admin')) {
            $this->active_admin = $formClean->numeric($_POST['active_admin']);
        }
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
			$this->action = $formClean->simpleClean($_POST['action']);
		}
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }

        // --- Search
		if (http_request::isGet('search')) {
			$this->search = $formClean->arrayClean($_GET['search']);
		}

		// --- Recursive Actions
		if (http_request::isGet('employee')) {
			$this->employee = $formClean->arrayClean($_GET['employee']);
		}
    }
    /**
     * Construction du tableau pour la sélection des rôles
     * @param null $idadmin
     * @return null|string
     */
    private function role($idadmin = null){
        if($idadmin != null){
            $data = parent::fetchData(
                array(
                    'type'  =>  'currentRole'
                )
                ,array(
                    'id_admin'   =>  $idadmin
                )
            );
        }else{
            if(parent::fetchData(array('type'=>'role')) != null){
                $data =  parent::fetchData(array('type'=>'role'));
            }
        }
        return $data;
    }

    /**
     * @return mixed
     */
    private function setItemsEmployee(){
        $data = parent::fetchData(array('type'=>'employees','search'=>$this->search));
		$employees = array();

		foreach($data as $row) {
			$employees[] = array(
				'id_admin'			=> $row['id_admin'],
				'title_admin'		=> $row['title_admin'],
				'firstname_admin'	=> $row['firstname_admin'],
				'lastname_admin'	=> $row['lastname_admin'],
				'email_admin'		=> $row['email_admin'],
				'role_name'			=> $row['role_name'],
				'active_admin'		=> $row['active_admin']
			);
		}

        return $employees;
    }

    /**
     * Assign setItemsEmployee
     */
    private function getItemsEmployee($id_admin = null){
    	if($id_admin) {
			$data = parent::fetchData(array('type'=>'employee'),array('id_admin' => $id_admin));
			$this->template->assign('employee',$data);
		} else {
			$data = $this->setItemsEmployee();
			$this->template->assign('getItemsEmployee',$data);
		}
    }

    /**
     * @return mixed
     */
    private function setItemsJobs(){
        $data = parent::fetchData(array('type'=>'jobs'));
        return $data;
    }

    /**
     * Assign setItemsJobs
     */
    private function getItemsJobs(){
        $data = $this->setItemsJobs();
        $this->template->assign('getItemsJobs',$data);
    }

    /**
     * Insertion de données
     * @param $data
     */
    private function add($data){
        switch($data['type']){
            case 'newEmployee':
				parent::insert(
					array(
						'context'   =>    'employee',
						'type'      =>    $data['type']
					),
					array(
						'keyuniqid_admin'   => filter_rsa::randUI(),
						'title_admin'       => $this->title_admin,
						'lastname_admin'    => $this->lastname_admin,
						'firstname_admin'   => $this->firstname_admin,
						'email_admin'       => $this->email_admin,
						'phone_admin'       => $this->phone_admin,
						'address_admin'     => $this->address_admin,
						'postcode_admin'    => $this->postcode_admin,
						'city_admin'       	=> $this->city_admin,
						'country_admin'     => $this->country_admin,
						'passwd_admin'      => $this->passwd_admin,
						'active_admin'      => $this->active_admin
					)
				);
				$lastInsert = parent::fetchData(array('type' => 'lastEmployee'));
				parent::insert(
					array(
						'context' 	=> 'employee',
						'type'		=> 'employeeRel'
					),
					array(
						'id_admin' 	=> $lastInsert['id_admin'],
						'id_role'	=> $this->id_role
					)
				);
				$this->header->set_json_headers();
				$this->message->json_post_response(true,'add_redirect');
				break;
        }
    }

	/**
	 * Insertion de données
	 * @param $data
	 */
	private function upd($data){
		switch($data['type']){
			case 'employee':
				parent::update(
					array(
						'context'   =>    'employee',
						'type'      =>    $data['type']
					),
					array(
						'id_admin'   		=> $this->id_admin,
						'title_admin'       => $this->title_admin,
						'lastname_admin'    => $this->lastname_admin,
						'firstname_admin'   => $this->firstname_admin,
						'email_admin'       => $this->email_admin,
						'phone_admin'       => $this->phone_admin,
						'address_admin'     => $this->address_admin,
						'postcode_admin'    => $this->postcode_admin,
						'city_admin'       	=> $this->city_admin,
						'country_admin'     => $this->country_admin,
						'active_admin'		=> $this->active_admin
					)
				);
				$this->header->set_json_headers();
				$this->message->json_post_response(true,'update',$this->id_admin);
				break;
			case 'employeePwd':
				parent::update(
					array(
						'context'   =>    'employee',
						'type'      =>    $data['type']
					),
					array(
						'id_admin'   		=> $this->id_admin,
						'passwd_admin'      => $this->passwd_admin
					)
				);
				$this->header->set_json_headers();
				$this->message->json_post_response(true,'update',$this->id_admin);
				break;
			case 'employeeActive':
				parent::update(
					array(
						'context'   =>    'employee',
						'type'      =>    $data['type']
					),
					$data['data']
				);
				break;
		}
	}

	/**
	 * Insertion de données
	 * @param $data
	 */
	private function del($data){
		switch($data['type']){
			case 'delEmployees':
				parent::delete(
					array(
						'context'   =>    'employee',
						'type'      =>    $data['type']
					),
					$data['data']
				);
				$this->header->set_json_headers();
				$this->message->json_post_response(true,'delete',$data['data']);
				break;
		}
	}

    /**
     * Execute controller
     */
    public function run($debug = false){
        if(isset($this->tabs)){
            if($this->tabs === 'access'){
                if(isset($this->action)) {
                    switch($this->action){
                        case 'add':
                            break;
                    }
                }else{

                }
            }elseif($this->tabs === 'jobs'){
                if(isset($this->action)) {
                    switch($this->action){
                        case 'add':
                            break;
                    }
                }else{
                    $this->getItemsJobs();
                    $this->template->display('employee/jobs.tpl');
                }
            }
        }else{
            if(isset($this->action)){
                switch($this->action){
                    case 'add':
                        if(isset($this->email_admin) && isset($this->passwd_admin)){
                            $this->add(
                                array(
                                    'type'=>'newEmployee'
                                )
                            );
                        }else{
							$country = new component_collections_country();
							$this->template->assign('countries',$country->getCountries());
                        	$this->template->assign('roles',$this->role());
                            $this->template->display('employee/add.tpl');
                        }
                        break;
					case 'edit':
						if (isset($this->passwd_admin)) {
							$this->upd(
								array(
									'type' => 'employeePwd'
								)
							);
						}elseif(isset($this->id_admin)) {
							$this->upd(
								array(
									'type' => 'employee'
								)
							);
						} else  {
							$country = new component_collections_country();
							$this->template->assign('countries',$country->getCountries());
							$this->getItemsEmployee($this->edit);
							$this->template->assign('roles', $this->role());
							$this->template->display('employee/edit.tpl');
						}
                        break;
					case 'delete':
						if(isset($this->id_admin)) {
							$this->del(
								array(
									'type'=>'delEmployees',
									'data'=>array(
										'id' => $this->id_admin
									)
								)
							);
						}
						break;
					case 'active-selected':
					case 'unactive-selected':
						if(isset($this->employee) && is_array($this->employee) && !empty($this->employee)) {
							$this->upd(
								array(
									'type'=>'employeeActive',
									'data'=>array(
										'active_admin' => ($this->action == 'active-selected'?1:0),
										'id_admin' => implode($this->employee, ',')
									)
								)
							);
						}
						$this->getItemsEmployee();
						$this->message->getNotify('update',array('method'=>'fetch','assignFetch'=>'message'));
						$this->template->display('employee/index.tpl');
						break;
                }
            }else{
                if($debug == true) {
                    $this->message->getNotify('debug', array(
                            'method' => 'debug',
                            'result' => $this->setItemsEmployee()
                        )
                    );
                }
				$this->getItemsEmployee();
                $this->template->display('employee/index.tpl');
            }
        }
    }
}