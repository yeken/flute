<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class X extends ADMIN_Controller
{
	public function index()
	{
		if($this->bitauth->logged_in() && ($this->bitauth->is_admin() || $this->bitauth->is_in_group(2)))
		{
			redirect('admin/home#'.$this->data['site_config']['start_page']);
		}else{
			redirect('admin');
		}
	}

	public function show_config()
	{
		$this->load->view('admin/home/inc.config.php');
	}
	
}
?>