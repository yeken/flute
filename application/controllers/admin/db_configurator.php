<?
class Db_configurator extends ADMIN_Controller
{
	public $configs;

	public function __construct()
	{
		parent::__construct();
		$this->configs = $this->config->item('modules');
		if($this->bitauth->group_id == 2)
		{
			redirect('admin/home#player/show_list');	
		}	
	}
	public function index()
	{
		$this->load->view('admin/db/index.php',$this->data);
	}
	
	public function update_base()
	{    
		foreach($this->configs as $module => $module_info)
		{
			$this->load->model($module_info['main_model'],$module);
			$this->data['update_models'][] = $this->$module;
		}
		$this->load->view('admin/db/updated.php',$this->data);	
	}

}
?>