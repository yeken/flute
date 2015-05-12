<?
require_once(dirname(__FILE__)."/front_init.php");
class Home extends Front_init
{
	public function __construct()
	{	
		parent::__construct();
	}
	
	public function index()
	{
		$this->data['section'] = "home";
		redirect("admin");
	}
}