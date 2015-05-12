<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->view('admin/home/index');
		//$this->load->view('welcome_message');
	}

	public function show_list($data)
	{
		$this->load->view('admin/common/inc.dashboard.php', $data);
	}
	
	public function analytics()
	{
		
		$this->load->view('admin/home/analytics.php');
	}
	
}
?>