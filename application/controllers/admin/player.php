<?php

class Player extends ADMIN_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		if(!$this->bitauth->is_admin())
		{
			$this->ini_condition = "company_id = '".$this->bitauth->company_id."' AND group_id = 3";	
			
			$this->data['fields']['branch_id']['source_condition'] = "company_id = '".$this->bitauth->company_id."'";
			$this->data['fields']['company_id']['value'] = $this->bitauth->company_id;
			$this->split_fields();
		}
		else
		{
			$this->ini_condition = "group_id = 3";
		}
	}


	public function update_qualys()
	{
		vd("start");
		$sql = "SELECT * FROM prognostics_qualys";
		$qualys = $this->db->query($sql)->result_array();
		$this->load->model("admin/team_model");
		
		foreach($qualys as $qualy)
		{
			$team_info = "";
			for($i = 1; $i <= 16; $i++)
			{
				$team = new Team_model();
				$team->get((int)$qualy['team'.$i."_id"]);
				
				$team_info .= $i.",".$team->name.",".$team->team_flag."|";	
			}
			
			$sql = "UPDATE prognostics_qualys SET teams_info = '".$team_info."' WHERE user_id = '".$qualy['user_id']."'";
			vd($sql);
			$this->db->query($sql);	
		}
		
	}

	public function update_pass()
	{
		$sql = "SELECT * FROM bitauth_users WHERE group_id = 3 AND password != ''";
		
		$result = $this->db->query($sql)->result();
		
		foreach($result as $row)
		{
			if(strlen($row->password) < 20)
			{
				vd($row->password." ".$row->company_id." ".$row->fullname);
				$pass = $this->bitauth->hash_password($row->password);
				$last_set = $this->bitauth->timestamp();
				
				$sql = "UPDATE bitauth_users SET password = '".$pass."', password_last_set = '".$last_set."' WHERE user_id = '".$row->user_id."'";
				$this->db->query($sql);
			}
		}
		return;
	}

	public function delete($id, $parent_id='')
	{
		
		if($this->bitauth->group_id == 2)
		{
			$sql = "SELECT * FROM bitauth_users WHERE user_id = '".(int)$id."'";
			$user = $this->db->query($sql)->row();
			if($user->company_id != $this->bitauth->company_id)
			{
				redirect("admin/home#".$this->class_name."/show_list/".$parent_id);
				return;	
			}	
		}

		if($user->group_id == 2)
		{
			$this->show_list();
		}
		else
		{
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
	}
	

	public function edit($id)
	{
		$this->data['title'] = $this->main_model->email;
		
		if($this->bitauth->group_id == 2)
		{
			$sql = "SELECT * FROM bitauth_users WHERE user_id = '".(int)$id."'";
			$user = $this->db->query($sql)->row();
			if($user->company_id != $this->bitauth->company_id)
			{
				$this->show_list();
				return;	
			}	
		}
		
		parent::edit($id);
	}	

	public function edit_password($id)
	{
		if(!$this->main_model->get($id))
		{
			$this->show_list();
		}
		else
		{
			if($this->bitauth->group_id == 2)
			{
				$sql = "SELECT * FROM bitauth_users WHERE user_id = '".(int)$id."'";
				$user = $this->db->query($sql)->row();
				if($user->company_id != $this->bitauth->company_id)
				{
					$this->show_list();
					return;	
				}	
			}
			
			$this->data['form_action'] = base_url()."admin/".$this->class_name."/validate_save/edit_password";			
			$this->data['form_url_success'] = $this->class_name."/details/".$id;	
			$this->data['current_tab'] = 'edit_password';
			$this->load->view('admin/common/inc.save.php', $this->data);	
		}
	}
}

?>