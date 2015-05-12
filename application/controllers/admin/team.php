<?
class Team extends ADMIN_Controller
{
	public function __construct()
	{
		parent::__construct();
		if($this->bitauth->group_id != 1)
		{
			redirect('admin/home#player/show_list');	
		}	
		
	}	
}
?>