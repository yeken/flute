<?
class Front_init extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	protected function redirect_login()
	{
		if($this->company_model->users_activated && !$this->bitauth->logged_in())
		{
			redirect($this->data['link_url']."log-in");
		}
	}


	public function clear_session()
	{
		$this->session->sess_destroy();	
	}


	/*
	Splits the fields array into page_fields[$visible_page][field_id][field_attributes]
	*/
	protected function split_fields()
	{
		if(!is_array($this->data['fields'])) return 0;
		
		$file_types = array('image','video','archive');
		$this->data['page_fields'] = array();
		foreach($this->data['fields'] as $field_id => $attrs)
		{
			if($attrs['visibility'])
			{
				$visibilities = explode("|", $attrs['visibility']);
				foreach($visibilities as $visibility)
				{
					$this->data['page_fields'][$visibility][$field_id] = $attrs;	
				}
			}
			// insert file fields;
			if(in_array($attrs['type'],$file_types))
			{
				$this->file_fields[$field_id] = $attrs;
			}
		}
	}

	public function get_file_manager()
	{
		$this->load->model('admin/file_manager_model', 'file_manager');			
		if($this->form_model->file_manager_id && $this->file_manager->get($this->form_model->file_manager_id))
		{
			return;
		}
		else
		{
			$this->file_manager->create();
			$this->form_model->set_field('file_manager_id',$this->file_manager->get_id());
			$this->form_model->save();	
		}	
	}
		
	public function validate_contact_form($page)
	{
		$output['page'] = $page;
		$output['valid'] = false;
		
		foreach($this->data['page_fields'][$page] as $field_id => $field)
		{
			$this->form_validation->set_rules($field_id, $field['label'], $field['validation']);
		}			

		$this->data['post'] = $this->input->post();

		if($this->data['post']['branch_id'])
		{
			$sql = "SELECT branch FROM company_branches WHERE branch_id = '".(int)$this->data['post']['branch_id']."'";
			$row = $this->db->query($sql)->row();
			$this->data['post']['branch'] = $row->branch;
				
		}
		
		if(!$this->form_validation->run())
		{
			$output['valid'] = 0;

			foreach($this->data['page_fields'][$page] as $field_id => $field)
			{
				$output['errors'][$field_id] = form_error($field_id);
			}
		}
		else
		{
			$output['valid'] = true;
			
			switch($page)
			{
				case 'first_login':
									$this->load->model("user_model","form_model");
									if(!(int)$this->session->userdata('prev_user_id'))
									{
										$output['error'] = lang("no-prev-id");
										echo json_encode($output);
										return;
									}
									$this->form_model->get($this->session->userdata('prev_user_id'));
									break;
				case 'register':	
									$this->load->model("user_model","form_model");
									if(!$this->session->userdata('register_email'))
									{
										$output['error'] = lang("no-prev-id");
										echo json_encode($output);
										return;
									}
									$sql = "SELECT * FROM bitauth_users WHERE username = '".addslashes($this->session->userdata('register_email'))."' AND company_id = '".$this->company_model->get_id()."'";
									$row = $this->db->query($sql)->row();
									$this->form_model->get($row->user_id);
									break;
				case 'edit_profile':
									$this->load->model("user_model","form_model");
									$this->form_model->get($this->bitauth->user_id);
									break;				
			
				case 'forgot_pass':
									$this->bitauth->set_password($this->bitauth->user_id, $this->data['post']['password']);	
									$output['message'] = lang('password-changed');
									$output['valid'] = true;
									echo json_encode($output);
									return;
									break;
			}
			
			if(!isset($output['valid']) || $output['valid'])
			{
				
				$this->form_model->set($this->data['post']);
				/*
				if(is_array($_FILES))
				{
					$this->get_file_manager();
					$this->file_manager->upload($this->file_fields);
				}
				*/
				if($this->form_model->save())
				{
					$output['valid'] = 1;
					switch($page)
					{
						case 'edit_profile':
											$sql = "UPDATE scores SET username = '".$this->form_model->fullname."' WHERE user_id = '".$this->form_model->get_id()."'";
											$this->db->query($sql);
											break;
						
						case 'first_login':	if($this->company_model->confirm_email)
											{
												$this->form_model->set_field("active",0);
												$this->form_model->set_field("enabled",0);
											}
											else
											{
												$this->form_model->set_field("active",1);
												$this->form_model->set_field("enabled",1);
											}
											$this->form_model->set_field("group_id",3);
											$this->form_model->set_field("groups_names","Jugador");
											$this->form_model->set_field("company_id",$this->company_model->get_id());
											$this->form_model->set_field("company",$this->company_model->name);
											$pass = $this->bitauth->hash_password($this->data['post']['password']);
											$last_set = $this->bitauth->timestamp();
											$this->form_model->set_field("password",$pass);
											$this->form_model->set_field("password_last_set",$last_set);
											$this->form_model->update();
											if($this->form_model->email)
											{
												$this->send_register_email();
											}
											$this->bitauth->logout();
											$this->bitauth->login($this->form_model->username,$this->data['post']['password']);
											$output['message'] = lang('first-time-message-no-email');
											break;
						case 'register':	
						
											if($this->company_model->confirm_email)
											{
												$this->form_model->set_field("active",0);
												$this->form_model->set_field("enabled",0);
											}
											else
											{
												$this->form_model->set_field("active",1);
												$this->form_model->set_field("enabled",1);
											}
											$this->form_model->set_field("username",$this->session->userdata('register_email'));
											$this->form_model->set_field("group_id",3);
											$this->form_model->set_field("groups_names","Jugador");
											$this->form_model->set_field("company_id",$this->company_model->get_id());
											$this->form_model->set_field("company",$this->company_model->name);
											$pass = $this->bitauth->hash_password($this->data['post']['password']);
											$last_set = $this->bitauth->timestamp();
											$this->form_model->set_field("password",$pass);
											$this->form_model->set_field("password_last_set",$last_set);
											
											$this->form_model->update();
											$this->bitauth->logout();
											$this->bitauth->login($this->form_model->username,$this->data['post']['password']);
											$this->send_register_email();
											$output['message'] = lang("register-message");
											break;	
					}

				}
				else
				{
					$output['valid'] = 0;
					$output['errors'] = $this->form_model->get_error_message();	
				}
			}
		}
		echo json_encode($output);
	}	
	
	protected function send_register_email()
	{
		$this->load->library('email');
		
		$config['protocol'] = 'mail';
		$config['charset'] = 'utf-8';
		$config['mailtype'] = 'html';
		
		$this->email->initialize($config);
		
		$this->email->from('registro@fantasyfutbol2014.com', 'Fantasy Futbol 2014');
		
		$this->email->to($this->form_model->email);

		$this->email->subject(lang("subject-registro"));
		
		$confirm_link = $this->data['link_url']."confirmar-cuenta/".$this->get_confirm_code($this->form_model->get_id());

		$body= var_lang('body-registro',$this->form_model->fullname)."<a href='".$confirm_link."'>".$confirm_link."</a>
				<br><br>".lang('register-login-email')."
				<br><br>".$this->company_model->username_field.": <b>".$this->form_model->username."</b><br>
				<br><br>".lang('Contraseña').": <b>".$this->data['post']['password']."</b><br><br>
				Fantasy Futbol 2014<br>--";
		
		$this->email->message($body);
		
		$this->email->send();
			
	}
	
	protected function get_confirm_code($id)
	{
		return $id."-".substr(base64_encode($id."fantastic 2013 vv ++ ??"),0,10);
	}
	


	protected function check_captcha()
	{
		$captcha_input = $this->input->post('captcha_input', TRUE);
    	$captcha_text = $this->simple_captcha->get_captcha_text('signup');
		return $captcha_input && ($captcha_input == $captcha_text);
	}	
}

?>