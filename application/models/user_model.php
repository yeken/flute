<?
// once resolved the problem of autoload class this should go away.
require_once(dirname(__FILE__)."/simple_data_model.php");

class User_model extends Simple_data_model
{
    public $db_index = 'user_id';
    public $db_table = 'bitauth_users';
	public $db_userdata_table = 'bitauth_userdata';
    protected $_db_user_pass = 'password';
    protected $_db_user_name = 'email';
    
	public $row_label = array('fullname');
	
    protected $session_key = 'user';
    protected $auto_write_session = false;
    
    public $first_login  = false;
    public $update_last_login = true;
    
    public $is_validated = false;

    public $controller = "user"; // useful for creating links depending on the controller

	public $post;
    protected $db_fields = array(
								'username|char',
								'fullname|char',
								'displayname|char|50',
								'email|char',
                                'password|char',
								'password_last_set|datetime',
                                'password_never_expires|boolean',
								'groups_names|char',
								'group_id|int',
								'file_manager_id|int',
                                'active|bool',
								'enabled|bool',
                                'last_login|datetime',
                                'date_created|datetime');

    public function  __construct() {
        parent::__construct();
    }

	public function post_get()
	{
			
		$sql = "SELECT * FROM ".$this->db_userdata_table." WHERE  ".$this->db_index." = '".$this->get_id()."'";
		$result = $this->db->query($sql)->result_array();
		
		if(is_array($result))
		{
			$this->userdata = $result[0];
		}
	}


	protected function post_delete()
	{
		$sql = "DELETE FROM configurations WHERE configuration_id = '".$this->get_id()."'";
		$this->db->query($sql);	
	}
	protected function post_create()
	{
		$this->set_field('active',($this->post['active'] ? $this->post['active'] : $this->active));
		
		$this->set_field('group_id', ($this->group_id ? $this->group_id : '2'));
		
		$this->hash_password();
		$this->update();	
	}
	
	public function hash_password()
	{
		$ci =& get_instance();
		$pass = $ci->bitauth->hash_password($this->password);
		$last_set = $this->bitauth->timestamp();
		$this->set_field("password_last_set",$last_set);
		$this->set_field('password',$pass);
		unset($ci);
	}
	
	protected function pre_save()
	{
		$this->post = $this->input->post();
		$this->set_field('enabled',1);
		$group_id = $this->post['group_id'] ? (int)$this->post['group_id'] : (int)$this->group_id;

		if($group_id)
		{
			$sql = "SELECT g.name FROM bitauth_groups AS g WHERE g.group_id = '".$group_id."'";		
			$result = $this->db->query($sql)->result_array();
			if(is_array($result))
			{
				$this->set_field('groups_names',$result[0]['name']);
				$this->post['groups_names'] = $result[0]['name'];
			}
			$this->post['groups'] = array($this->post['group_id']);
			unset($this->post['group_id']);
		}
		$ci =& get_instance();
		unset($this->post['passconf']);	
		$this->userdata = $this->post['userdata'];
		unset($this->post['userdata']);
		unset($ci);
	}
	
}
?>