<?
class Match extends ADMIN_Controller
{
	public function __construct()
	{
		parent::__construct();
		if($this->bitauth->group_id != 1)
		{
			redirect('admin/home#player/show_list');	
		}	
		
	}
	public function update_matches()
	{
		$sql = "SELECT match_id FROM matches";
		$result = $this->db->query($sql)->result_array();
		
		$this->load->model("admin/match_model");
		foreach($result as $row)
		{
			$this->match_model->get($row['match_id']);
			$this->match_model->save();
			vd("updated: ".$this->match_model->get_id());
		}	
	}
}
?>