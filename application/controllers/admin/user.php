<?php

class User extends ADMIN_Controller
{
	public function __construct()
	{
		parent::__construct();
		if($this->bitauth->group_id == 2)
		{
			
		}	
		
	}
	
	public function index()
	{		
		if (!$this->bitauth->logged_in())
		{
			redirect($this->data['controller_name'].'/login', 'refresh'); //redirect them to the login page
		}
		else
		{
			redirect($this->data['site_config']['home_controller'],'refresh');
		}
	}
	
	public function login()
	{	
		$this->data['title'] = "Login";
		if($this->input->post())
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('remember_me','Remember Me','');
			$this->form_validation->set_message('required', 'Falta completar el campo %s');
			if($this->form_validation->run() == TRUE)
			{
				// Login
				if($this->bitauth->login($this->input->post('username'), $this->input->post('password'), $this->input->post('remember_me')))
				{
					if($this->bitauth->is_admin() || $this->bitauth->is_in_group(2))
					{
						
						if($this->bitauth->is_in_group(2))
						{
							$this->session->set_userdata('config_id', $this->bitauth->user_id);	
						}
						
						$this->session->set_userdata('username', $this->bitauth->username);	
						
						redirect($this->data['site_config']['home_controller'],'refresh');
					}
					else
					{
						$errors[] = "No existe el usuario";
					}
				}else{
						$errors[] = $this->bitauth->get_error();
				}
			}
			else
			{
				$errors[] = validation_errors();	
			}
		}

		$this->data['messages'] = $errors;

		$this->data['username'] = array('name' => 'username',
										'id' => 'username',
										'type' => 'text',
										'style' => 'width: 97%;',
										'value' => $this->form_validation->set_value('username'));
		$this->data['password'] = array('name' => 'password',
										'id' => 'password',
										'style' => 'width: 97%;',
										'type' => 'password');


		$this->load->view($this->data['views_folder'].'/login', $this->data);
	}


	public function logout()
	{
		$this->bitauth->logout();
		redirect("/admin/");
	}

	public function show_list()
	{
		$this->data['title'] = "Users list";

		parent::show_list();
	}

	public function delete($id, $parent_id='')
	{
		if($this->bitauth->group_id == 2)
		{
			$this->details($this->bitauth->user_id);
		}
		if(!$this->main_model->get($id)  || !$this->has_permission('delete'))
		{
			$this->show_list($parent_id);
		}
		else
		{
			$this->main_model->delete();
			$this->show_list($parent_id);
		}
	}
	
	public function edit($id)
	{
		$this->data['title'] = $this->main_model->email;
		
		if(($this->bitauth->group_id == 2) && ($id !=  $this->bitauth->user_id))
		{
			$id = $this->bitauth->user_id;	
		}
		
		parent::edit($id);
	}	

	public function details($id = NULL)
	{									
		$this->data['title'] = $this->main_model->email;
		if(($this->bitauth->group_id == 2) && ($id !=  $this->bitauth->user_id))
		{
			$id = $this->bitauth->user_id;	
		}	
		parent::details($id);
	}

	public function edit_password($id)
	{
		if(($this->bitauth->group_id == 2) && ($id !=  $this->bitauth->user_id))
		{
			$id = $this->bitauth->user_id;	
		}		
		
		if(!$this->main_model->get($id))
		{
			$this->show_list();
		}
		else
		{
			$this->data['form_action'] = base_url()."admin/".$this->class_name."/validate_save/edit_password";			
			$this->data['form_url_success'] = $this->class_name."/details/".$id;	
			$this->data['current_tab'] = 'edit_password';
			$this->load->view('admin/common/inc.save.php', $this->data);	
		}
	}			

	public function forgot_password()
	{
		if($this->input->post())
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_message('required', 'Falta completar el campo %s');
			if($this->form_validation->run() == TRUE)
			{
				
				if(!($user = $this->bitauth->get_user_by_username($this->input->post('username'))))
				{
					$user = $this->bitauth->get_user_by_email($this->input->post('username'));
					
				}
				if(!$user)
				{
					$data['messages'][] = "No existe una cuenta con ese usuario o email.";
					
				}
				else
				{
					$forgot_code = $this->bitauth->forgot_password($user->user_id);
					$data['url'] = base_url()."admin/generame-la-clave/".$forgot_code;
					$this->bitauth->send_forgot_password_email($data['url'], $user->user_id);	
					$data['success'] = 1;
					$data['email'] = $user->email;
				}
			}
		}
		$this->load->view("admin/auth/forgot_password", $data);	
	}
	
	public function recover_password($forgot_code)
	{
		if(!$forgot_code || !($user = $this->bitauth->get_user_by_forgot_code($forgot_code)))
		{
			$this->load->view('admin/auth/recover_password_failure');
		}
		else
		{
			$password = substr(base64_encode(date("si H:i:s")."..=+=..".$user->user_id),0,6);
			if($this->bitauth->set_password($user->user_id, $password))
			{
				$data['messages'][] = "Completá el login con los datos <br>Usuario: ".$user->username."<br>Contrase&ntilde;a: ".$password;
				$this->bitauth->send_new_password_email($user, $password);
				$this->bitauth->activate_by_id($user->user_id);
				$data['username'] = array('name' => 'username',
										'id' => 'username',
										'type' => 'text',
										'style' => 'width: 97%;',
										'value' => $this->form_validation->set_value('username'));
				$data['password'] = array('name' => 'password',
										'id' => 'password',
										'style' => 'width: 97%;',
										'type' => 'password');


				$this->load->view('admin/auth/login',$data);	
			}
			else
			{
				$this->load->view('usuario/recover_password_failure');
			}
		}
	}

}

?>