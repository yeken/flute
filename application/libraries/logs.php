<?

class Logs
{
	public $user_id;
	public $ip_address;
	public $page;
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ip_address = $this->get_ip();
		$this->user_id = $this->ci->bitauth->user_id;
	}

	public function add_log($page,$action = 'view', $index_id = 0)
	{
		$sql = "INSERT INTO logs VALUES ('".$this->ip_address."','".(int)$this->user_id."','".$page."','".str_replace(base_url(),'',current_url())."','".$index_id."','".$action."',NOW())";
		$this->ci->db->query($sql);
	}

	protected function get_ip()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

}


?>