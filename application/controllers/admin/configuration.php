<?
class Configuration extends ADMIN_Controller
{
	public function __construct()
	{
		parent::__construct();
		if($this->bitauth->group_id == 2)
		{
			redirect('admin/home#player/show_list');	
		}	
		
	}	
	public function details($id = "")
	{
		if($this->bitauth->is_admin())
		{
			$config_id = $id ? $id : 1;	
		}
		else
		{
			$config_id = $this->session->userdata('config_id') ? $this->session->userdata('config_id') : 1;
		}
		
		if(!$this->main_model->get($config_id))
		{
			$this->main_model->set_id($config_id);
			$this->main_model->create();
		}
		
		return parent::details($config_id);
	}

	public function show_config()
	{
		$this->load->view('admin/home/inc.config.php');
	}
}
?>