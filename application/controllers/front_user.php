<?
require_once(dirname(__FILE__)."/front_init.php");
class Front_user extends Front_init
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function auto_save($match_code,$match_id,$team1_goals, $team2_goals, $result)
	{
		$this->redirect_login();
		$out['valid'] = false;
		$this->load->model("admin/match_model","match_model");
		if(!(int)$match_id || !$this->match_model->get((int)$match_id) || (minDiff($this->match_model->date_played,$this->data['today']) < 30) || $this->match_model->no_complete() || ($match_code != get_match_code($this->match_model->get_id(), $this->match_model->no_complete())))
		{
			echo json_encode($out);
			return;	
		}
		
		$sql = "REPLACE INTO prognostics (tournament_date, company_id, match_id, matchname, user_id, team1_goals, team2_goals, result, username, date_created)
				VALUES ('".$this->match_model->tournament_date."','".$this->company_id."','".$this->match_model->match_id."','".$this->match_model->matchname()."','".(int)$this->user_id."','".(int)$team1_goals."','".(int)$team2_goals."','".(int)$result."','".$this->username."',NOW())";
		$this->db->query($sql);
		$out['valid'] = true;
		echo json_encode($out);
		
	}
	
	public function login()
	{	
		$out['valid'] = false;
	
		if(!$this->company_model->users_activated)
		{
			$out['valid'] = true;
			echo json_encode($out);
			return;
		}
			
		if($this->company_model->first_login)
		{
			$sql = "SELECT * FROM bitauth_users WHERE username = '".addslashes($this->input->post('username'))."' AND company_id = '".$this->company_model->get_id()."'";
			$query = $this->db->query($sql);
			
			if(!$query->num_rows())
			{
				$out['error'] = "No existe el ".$this->company_model->username_field;
				echo json_encode($out);
				return;
			}
			
			$row = $query->row();
			
			if(!$row->active)
			{
				$this->session->set_userdata('prev_user_id', $row->user_id);
					
				$out['valid'] = true;
				$out['first_register'] = true;
				echo json_encode($out);
				return;
			}
		}
		
		if($this->input->post())
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('remember_me','Remember Me','');
			$this->form_validation->set_message('required', 'Falta completar el campo %s');
			if($this->form_validation->run() == TRUE)
			{
				$sql = "SELECT * FROM bitauth_users WHERE username = '".addslashes($this->input->post('username'))."' AND company_id = '".$this->company_model->get_id()."'";
				$query = $this->db->query($sql);
				if(!$query->num_rows())
				{
					$out['error'] = var_lang("no-record-found",$this->company_model->username_field);
					echo json_encode($out);
					return;
				}
				
				// Login
				if($this->bitauth->login($this->input->post('username'), $this->input->post('password'), $this->input->post('remember_me'),array(),NULL, $this->company_model->get_id()))
				{
					$this->session->set_userdata('user_id', $this->bitauth->user_id);	
					$this->session->set_userdata('username', $this->bitauth->username);
					$out['valid'] = true;
				}
				else
				{
						$out['error'] = var_lang("incorrect-login",$this->company_model->username_field);
				}
			}
			else
			{
				$out['error'] = "Debes completar ".$this->company_model->username_field." y contraseña";	
			}
		}
		echo json_encode($out);
	}

	public function forgot_password()
	{
		$this->data['section'] = "forgot_password";
		if($this->input->post())
		{
			$out['valid'] = false;
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			if($this->form_validation->run() == TRUE)
			{
				$sql = "SELECT * FROM bitauth_users WHERE username = '".$this->input->post('username')."' AND company_id = '".$this->company_model->get_id()."'";
				$user = $this->db->query($sql)->row();
				
				if(!$user)
				{
					$out['error'] = lang("no-account")." ".$this->company_model->username_field;
					
				}				
				else
				{

					if($user->email)
					{
						$forgot_code = $this->bitauth->forgot_password($user->user_id);
						$out['url'] = $this->data['link_url']."generame-la-clave/".$forgot_code;
						$this->send_forgot_password_email($out['url'],$user->fullname, $user->email);	
						$out['email'] = $user->email;
						$out['message'] = var_lang("email-sent","<b>".$user->email."</b>");
						$out['valid'] = 1;
					}
					else
					{
						$out['error'] = lang("no-email");
					}
				}
			}
			echo json_encode($out);
			return;
		}
		else
		{
			$this->load->view("front/forgot_password", $this->data);	
		}
	}

	public function send_forgot_password_email($forgot_link,$name, $email)
	{
		$this->load->library('email');
		$data['forgot_password_link'] = $forgot_link;

		$config['protocol'] = 'mail';
		$config['charset'] = 'utf-8';
		$config['mailtype'] = 'html';

		$this->email->initialize($config);
		$this->email->from('registro@fantasyfutbol2014.com','Fantasty Futbol 2014');
		$this->email->to($email);
		
		$this->email->subject('Fantasy Futbol 2014: '.lang('Recuperá tu clave'));
		$this->email->message(var_lang("recover-password-email",$name)."<a href='".$forgot_link."'>".$forgot_link.".</a><br><br>".lang("recover-password-email-2"));
		return $this->email->send();
	}
	
	public function recover_password($forgot_code)
	{
		$this->data['section'] = "recover_password";
		if(!$forgot_code || !($user = $this->bitauth->get_user_by_forgot_code($forgot_code)))
		{
			$this->load->view('front/recover_password_failure.php',$this->data);
		}
		else
		{
			$password = substr(base64_encode(date("si H:i:s")."..=+=..".$user->user_id),0,6);
			if($this->bitauth->set_password($user->user_id, $password))
			{
				$this->bitauth->send_new_password_email($user, $password);
				$this->bitauth->login($user->username, $password);
				$this->load->view('front/recover_password.php',$this->data);	
			}
			else
			{
				$this->load->view('front/recover_password_failure.php',$this->data);
			}
		}
	}	

	
	public function send_comment()
	{
		$this->redirect_login();
		$post = $this->input->post();
		
		$sql = "INSERT INTO wall (comment, user_id, username, company_id, active, date_created)
				VALUES('".addslashes($post['new_wall_post'])."','".$this->user_id."','".$this->fullname."','".$this->company_model->get_id()."',1,NOW())";
		$this->db->query($sql);
		$out['post_id'] = $this->db->insert_id();
		$out['valid'] = true;
		$out['username'] =  $this->fullname;
		$out['hour'] = date("H:i");
		$out['comment'] = $post['new_wall_post'];
		echo json_encode($out);
	}
	
	public function comment_post($post_id)
	{
		$this->redirect_login();
		$out['valid'] = false;
		$sql = "SELECT company_id FROM wall WHERE comment_id = '".(int)$post_id."'";
		
		$row = $this->db->query($sql)->row();
		if(!(int)$row->company_id || ((int)$row->company_id != $this->company_model->get_id()))
		{
			$out['error'] = "No se pudo guardar el comentario. por favor inténtalo denuevo";
			echo json_encode($out);
			return;	
		}
		$post = $this->input->post();
		if(!trim($post['post_comment']))
		{
			$out['error'] = "El comentario está vacío.";
			echo json_encode($out);
			return;	
		}
		$sql = "INSERT INTO wall_comments (post_id, user_id, username, comment, date_created)
				VALUES('".(int)$post_id."','".$this->user_id."','".$this->fullname."','".addslashes($post['post_comment'])."',NOW())";
		$this->db->query($sql);
		$out['valid'] = true;
		$out['comment'] = $post['post_comment'];
		$out['post_id'] = (int)$post_id;
		$out['date_created'] = date("d/m/Y H:i");
		$out['username'] = $this->username;
		echo json_encode($out);
			return;	
	}
	
	public function register()
	{
		$out['valid'] = 0;
		$this->form_validation->set_rules("email", "Email", "required");	
		$this->form_validation->set_message('required', lang("complete-email-field"));
		$register_email = addslashes($this->input->post('email')).($this->company_model->register_domain ? "@".$this->company_model->register_domain : "");
		
		if(!$this->form_validation->run())
		{
			$out['error'] = form_error('email');	
		}
		else
		{
			$sql = "SELECT * FROM bitauth_users WHERE username = '".$register_email."' AND company_id = '".$this->company_model->get_id()."'";
			$row = $this->db->query($sql)->row();

			if($row->active)
			{
				$out['error'] = "Ya existe un usuario con el e-mail ".$this->input->post('email').". Si olvidaste tu clave clickeá <a href='".$this->data['link_url']."olvide-mi-clave'>acá</a>";	
				echo json_encode($out);
				return;
			}
			else
			{
				$this->session->set_userdata('register_email',$register_email);
				$this->session->set_userdata('prev_registered_id',$row->user_id);
				$out['valid'] = true;
			}
		}
		echo json_encode($out);
		return;
	}
	

	
}