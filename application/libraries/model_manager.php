<?

class model_manager{
	
	protected $model_name;
	protected $result;
	protected $model_list = array();
	protected $id;
	public $order_field;
	public $order_type;
	public $use_inactive = false;
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}

	public function set_model($model)
	{
		$this->ci->load->model($model,'m_model');	
		$this->model_name = $model;
		$this->set_order($this->ci->m_model->db_index,'DESC');
	}
	
	
	public function set_order($field, $order_type)
	{
		$this->order_field = $field;
		$this->order_type = $order_type;	
	}
	
	public function use_inactive()
	{
		$this->use_inactive = true;	
	}
	
	public function get_next($n = 10, $offset = 0)
	{
		if(!$use_inactive && $this->ci->m_model->has_field('active'))
		{
			$where = "WHERE active = 1";
		}

		$sql = "SELECT * FROM ".$this->ci->m_model->db_table." ".$where." ORDER BY ".$this->order_field." ".$this->order_type.($n ? " LIMIT ".$offset.",".$n : "");	
		$this->result = $this->db->query($sql)->result_array();
		
		return $this->get_list();
	
	}
	
	public function get_all()
	{
		if(!$use_inactive && $this->ci->m_model->has_field('active'))
		{
			$where = "WHERE active = 1";
		}

		$sql = "SELECT * FROM ".$this->ci->m_model->db_table." ".$where." ORDER BY ".$this->order_field." ".$this->order_type;	
		$this->result = $this->db->query($sql)->result_array();
		
		return $this->get_list();		
	}
	
	public function get_list()
	{
		$this->model_list = array();
		
		if(!count($this->ci->m_model->file_managers_fields))
		{
			if(!count($this->ci->m_model->composed_fields))
			{
				$this->model_list = $this->result;
				return $this->model_list();	
			}
			else
			{
				foreach($this->result as $row)
				{
					$this->ci->m_model->set($row);
					foreach($this->ci->m_model->composed_fields as $field)
					{
						$row[$field] =  $this->ci->m_model->$field;	
					}
					
					$this->model_list[] = $row;								
				}
			}
		}
		
		foreach($this->result as $row)
		{
			$this->ci->m_model->set($row);
			$this->ci->m_model->get_files();
			foreach($this->ci->m_model->file_tags as $file_tag)
			{
				$row[$file_tag] = $this->ci->m_model->$file_tag;
			}
			$row['galleries'] = $this->ci->m_model->galleries;

			foreach($this->ci->m_model->composed_fields as $field)
			{
				$row[$field] =  $this->ci->m_model->$field;	
			}
			
			$this->model_list[] = $row;	
		}
		
		return $this->model_list[];
	}
	
}


?>