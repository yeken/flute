<?
class Contact extends ADMIN_Controller
{
	public function __construct()
	{
		parent::__construct();
		if($this->bitauth->group_id == 2)
		{
			redirect('admin/home#player/show_list');	
		}	
		
	}	
	public function show_list()
	{
		parent::show_list();	
	}
	
	public function make_client($contact_id)
	{
		$this->main_model->make_client($contact_id);
		$this->show_list();	
	}

}
?>