<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__)."/ADMIN_Core_controller.php");

class ADMIN_Categories extends ADMIN_core_controller {

	public function ajax_tree()
	{

		$config_tree = array('table' => $this->main_model->db_table,'id' => $this->main_model->db_index);
		$this->load->library('tree_json', $config_tree);
		//$jstree->_create_default();
		//die();

		if(isset($_GET["reconstruct"])) {
			$this->tree_json->_reconstruct();
			die();
		}
		if(isset($_GET["analyze"])) {
			echo $this->tree_json->_analyze();
			die();
		}

		if($_REQUEST["operation"] && strpos($_REQUEST["operation"], "_") !== 0 )
		{
			header("HTTP/1.0 200 OK");
			header('Content-type: application/json; charset=utf-8');
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Pragma: no-cache");
			echo $this->tree_json->{$_REQUEST["operation"]}($_REQUEST);
			die();
		}
		
	}
	
}
?>