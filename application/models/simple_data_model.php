<?
require_once(dirname(__FILE__)."/core_data_model.php");

/*
	fields: name|type|index.
	types: int, mediumint, smallint, bool, char, smallchar, text, enum_val1_valn, date, datetime
*/

class Simple_data_model extends Core_data_model
{
	public $file_managers_fields = array();
	public $file_managers;
	public $file_tags;
	public $gallery_tags;
	public $use_delete_table = false;

    protected $db_fields = array();
	protected $db_fields_comments = array();

	protected $setted_fields = array();
	protected $file_manager_loaded = 0;
	public $update_setted = true;
	public $composed_fields = array();
	
	
	// EXAMPLE: public $multiselect_fields = array('categories' => array('table' => 'services_categories_services', 'db_index' => 'category_id'));
	public $multiselect_fields = array();

    public function __construct()
    {
		$sql2 = "SET  @@session.sql_mode='';";
		$this->db->query($sql2);
		parent::__construct();

    }
	
	public function get_field_description($field)
	{
		return $this->_db_fields[$field] ? $this->_db_fields[$field]['comment'] : "";	
	}
	
	public function create_new($fields)
	{
		$this->erase();
		$this->set($fields);
		$this->set_field('date_created',date("Y-m-d H:i:s"));
		return $this->create();
	}
	
	public function erase()
	{	
		foreach($this->db_fields AS $field)
		{
			$this->$field = null;
		}	
	}
	
	public function get_label()
	{
		if(is_array( $this->row_label ))
		{
			$str = "";
			foreach( $this->row_label as $row_name)
			{
				$str.=$this->$row_name." ";
			}

			return $str;
		}
		else
		{
			if($this->row_label)
			{
				$row_name = $this->row_label;
				$str = $this->$row_name;	
			}
		}
		return 0;
	}
	
    public function load_file_manager()
	{
		if($this->file_manager_id && ($this->db_index != 'file_manager_id'))
		{
			$this->load->model('admin/file_manager_model','file_manager_model');
			$this->file_manager_model->get($this->file_manager_id);
			$this->file_manager_model->get_files();
			$this->file_manager_loaded = 1;	
		}
	}

	/*
     * the $opt parameter can be an integer id, a string condition or a condition array
     * $opt = 323, $opt = "[[table_prefix]].email = 'emailaddress@domain.com' AND [[table_prefix]].active = 1"
     * [[table_name]] will be automatically replaced by the function.
     * for a more complex getter you should create your own function.
     */

    public function get($opt)
    {
        if ($this->db_fields==0) return 0;
        if(!$opt) return 0;

		if (is_numeric($opt))
        {
			$id = $opt;
        }
        else
        {
            $condition = str_replace("[[table_prefix]]", "t", $opt);
        }

        $sql  = "SELECT t.". implode(", t.", $this->db_fields) ." FROM ". $this->db_table ." AS t";
        $sql .= $id? " WHERE t.". $this->db_index ."='". $id ."'" : " WHERE ".$condition;
		$result = $this->db->query($sql);

        if ($result->num_rows())
        {
            $this->set($result->row_array());
            $this->get_dependant();
			$this->post_get();
			return 1;
        }

        return 0;

    }

	public function post_get(){}
	
	public function get_dependant()
	{
		if(is_array($this->file_managers_fields) && count($this->file_managers_fields) && ($this->db_index != 'file_manager_id'))
		{
			$this->load->model('admin/file_manager_model');
			$this->get_files();
		}
	}
	
	public function get_files()
	{
		$res = array();
		$this->file_tags = array();
		$this->gallery_tags = array();
		$this->galleries = array();
		if(!$this->file_manager_loaded)
		{
			$this->load->model('admin/file_manager_model','file_manager_model');
		}
		foreach($this->file_managers_fields as $file_manager_tag)
		{
			$file_manager = new File_manager_model();
			if($this->$file_manager_tag && $file_manager->get($this->$file_manager_tag))
			{
				$file_manager->get_files();
				if(is_array($file_manager->url_list))
				{
					foreach($file_manager->url_list as $item_tag => $item_id)
					{		
						foreach($item_id as $item_url)
						{
							if(count($file_manager->url_list[$item_tag]) == 1)
							{
								$this->$item_tag = $item_url;
								$res[$item_tag] = $item_url;
								$this->file_tags[] = $item_tag;
							}
							else
							{
								$this->galleries[$item_tag][] = $item_url;	
								$res['galleries'][$item_tag][] = $item_url;
								if(!in_array($item_tag, $this->gallery_tags))
								{
									$this->gallery_tags[] = $item_tag;
								}
							}
							
						}
					}
				}
				$this->file_managers[$file_manager_tag] = $file_manager;
			}
		}
		return $res;
	}
	
    public function create()
    {
        $this->pre_create();
		if(in_array('date_created',$this->db_fields) && (!$this->setted_fields['date_created']))
		{
			$this->set_field('date_created',date("Y-m-d H:i:s"));
		}

		if(in_array('creator_id',$this->db_fields) && (!$this->setted_fields['creator_id']))
		{
			$this->set_field('creator_id',$this->session->userdata('user_id'));
			$this->set_field('creator_name',$this->session->userdata('username'));
		}

		$db_index = $this->db_index;
        $this->$db_index = '';
        $sql = "INSERT INTO ". $this->db_table ." ( `". implode("`, `", $this->db_fields) ."`) VALUES ( ";

        foreach($this->db_fields AS $field)
        {
            $sql .= "'". $this->sanitize_field($field) ."',";
        }

        $sql[strlen($sql) - 1] = " ";

        $sql .= " )";

		$this->pre_create();

        if($this->db->query($sql))
        {
            $this->set_id($this->db->insert_id());
			$this->update_multidata();
			$this->post_create();
			return 1;
        }
        return 0;
    }
	
	public function update_only_setted($value = true)
	{
		$this->update_setted = $value;	
	}
	
    public function update($id=null)
    {
		$this->pre_update();
        $id = $id? $id : $this->get_id();
        $sql = "UPDATE ". $this->db_table ." SET ";
		$ar_fields = $this->update_setted ? array_keys($this->setted_fields) : $this->db_fields;

		foreach($ar_fields AS $field)
        {
            if(in_array($field,$this->db_fields)) // update db fields only!
			{
				$sql .= "`". $field ."`='". $this->sanitize_field($field) ."', ";
			}

		}

        $sql{strlen($sql) - 2} = " ";
        $sql .= " WHERE ". $this->db_index ."='". $this->get_id() ."'";
		$this->update_multidata($id);
		if($this->db->query($sql))
		{
			$this->post_update();
			return 1;	
		}

    }
	protected function update_multidata($id = null)
	{
        $id = $id? $id : $this->get_id();
		foreach($this->multiselect_fields as $ms_field_id => $field)
		{
			$sql = "SELECT ".$field['db_index']." FROM ".$field['table']." WHERE ".$this->db_index." = ".$id;
			$prev_values = $this->db->query($sql)->result_array();
			$sql = "DELETE FROM ".$field['table']." WHERE ".$this->db_index." = ".$id;
			$this->db->query($sql);
			if(is_array($field['values']))
			{
				$sql = "INSERT INTO ".$field['table']." (".$this->db_index.",".$field['db_index'].") VALUES ";
				foreach($field['values'] as $field_id)
				{
					$sql .= "('".$id."','".$field_id."'),";	
				}
				$sql[strlen($sql) -1] = ";";	
			}
			$this->db->query($sql);
			if($field['ori_table'] && $field['count_field'])
			{
				foreach($prev_values as $value)
				{
					if(!is_array($field['values']) || !in_array($value[$field['db_index']],$field['values']))
					{
						$field['values'][] = $value[$field['db_index']];
					}	

				}
				$this->update_multidata_totals($ms_field_id);
			}
		}
	}
	
	public function update_multidata_totals($field_id)
	{
		$field = $this->multiselect_fields[$field_id];
		$sql = "SELECT * FROM ".$field['table']." WHERE ".$this->db_index." = '".$this->get_id()."'";

		$query = $this->db->query($sql);
		foreach($query->result() as $row)
		{
			$sql = "UPDATE ".$field['ori_table']." SET ".$field['count_field']." = (SELECT COUNT(*) FROM ".$field['table']." WHERE ".$field['db_index']." = '".$row->$field['db_index']."' GROUP BY ".$field['db_index'].") WHERE ".$field['db_index']." = '".$row->$field['db_index']."'";	
			$this->db->query($sql);
		}
	}
	
    public function save($id=null)
	{
      $this->pre_save();
	  $id = $id? $id : $this->get_id();

      if($id)
      {
          $res = $this->update($id);
      }
      else
      {
          $res = $this->create();
      }
	  $this->post_save();
	  return $res;
    }
	
    public function get_id()
    {
        $dbIndex = $this->db_index;
        return $this->$dbIndex;
    }
	
	public function remove_id()
	{
		$dbIndex = $this->db_index;
        $this->$dbIndex = '';
	}
	
    public function set_id($id)
    {
        $dbIndex = $this->db_index;
        $this->$dbIndex = $id;
    }
	
	public function set_field($field, $value)
	{
		$this->$field = $value;
		$this->setted_fields[$field] = 1;
	}
    
	public function set($data, $stripslashes = false)
    {
		$this->convert_address_field($data);
		foreach($this->db_fields as $field)
        {	
			if(strstr($field,'date'))  
			{
				$data[$field] = strlen(trim($data[$field])) == '16' ? trim($data[$field]).":00" : $data[$field]; 
			}
			isset($data[$field])? $this->set_field($field,$data[$field]) : NULL;
        }
		$this->set_multidata($data);
    }
	
	public function set_multidata($data)
	{
		foreach($this->multiselect_fields as $field_id => $field)
        {

			if(is_array($data[$field_id]))
			{
				$values = $data[$field_id];
				$values = array_keys($values);
				$values = str_replace("node_","",$values);
				$this->multiselect_fields[$field_id]['values'] = $values;
			}
		}		

	}
	
	public function convert_address_field($data)
	{
		if(is_array($this->address_fields))
		{
			foreach($this->address_fields as $address_field)
			{
				$chunks = explode(",",$data[$address_field]);
				if(count($chunks) <= 1)
				{
					$this->_error("los campos de direcci&oacute;n deben contener m&aacute;s informaci&oacute;n");
					return 0;
				}
				else
				{
					$field = $address_field."_street";
					$this->$field = $chunks[0];
					$chunks[0] = "";
					$chunks = array_reverse($chunks);
					$dimensions = array("country", "province","locality");
					$field = array();

					foreach($dimensions as $dimension_id => $dimension)
					{
						if($chunks[$dimension_id])
						{
							$field['label'] = $address_field."_".$dimension; 
							$ori_words = array("Province","Autonomous City of","City");
							$replacements = array("Provincia","Ciudad de","Ciudad");
							$field['value'] = str_replace($ori_words,$replacements,$chunks[$dimension_id]);
							$sql = "SELECT * FROM regions WHERE name = '".$this->sanitize(trim($field['value']))."' and dimension ='".$dimension_id."'";
							$query = $this->db->query($sql);

							if($query->num_rows())
							{
								$row = $query->row();	
								$field['id'] = $row->region_id;
							}
							else
							{
								$sql = "INSERT INTO regions VALUES ('','".(int)$field['parent_country_id']."','".(int)$field['parent_province_id']."','".$dimension_id."','".trim($this->sanitize($field['value']))."')";
								$this->db->query($sql);
								$field['id'] = $this->db->insert_id();
							}

							if($dimension_id == 0)
							{
								$field['parent_country_id'] = $field['id'];
							}
							if($dimension_id == 1)
							{
								$field['parent_province_id'] = $field['id'];
							}
							$this->$field['label'] = $field['id'];
						}
					}
				}
			}	
		}		
	}
	
	public function get_field($field)
	{
		return $this->$field ? $this->$field : "Campo incorrecto";	
	}
	
	public function get_parent_id()
	{
		$parent_id_field = $this->parent_db_index;
		return $this->$parent_id_field;	
	}
	
	public function delete($id = NULL)
	{
		$id = $id ? $id : $this->get_id();
		if(!$id)
		{
			$this->_error("No id selected.");
			return 0;	
		}

		if($this->delete_table && $this->use_delete_table) // don't delete file manager if it will be restored.
		{
			$sql = "INSERT INTO ".$this->delete_table." (SELECT * FROM ".$this->db_table." WHERE ". $this->db_index ."='". $id ."')";
			$this->db->query($sql);	
		}
		else
		{
			$this->delete_file_manager($id); 
		}

		$sql = "DELETE FROM ". $this->db_table ." WHERE ". $this->db_index ."='". $id ."'";
		if($this->db->query($sql))
		{
			$this->post_delete();
			return 1;
		}
		return 0;
	}
	
	protected function delete_file_manager($id)
	{
		if(!in_array('file_manager_id',$this->db_fields) || ($this->db_index == 'file_manager_id'))
			return;
		if($this->get($id))
		{
			if($this->file_manager_id)
			{
				$this->load->model('admin/file_manager_model','file_manager_model');
				$this->file_manager_model->delete($this->file_manager_id);	
			}	
		}
	}
	
	public function check_duplicate_value($field)
	{
		$sql = "SELECT * FROM ".$this->db_table." WHERE ".$field." = '".$this->$field."' AND ".$this->db_index." != '".$this->get_id()."'";
		$result = $this->db->query($sql);
		return $result->num_rows();		
	}
	
	public function check_existing_value($field)
	{
		$sql = "SELECT * FROM ".$this->db_table." WHERE ".$field." = '".$this->$field."'";	
		$result = $this->db->query($sql);
		return $result->num_rows();
	}
	
	public function get_by_field($field, $value = NULL)
	{
		return $this->get_from_field($field, $value);	
	}
	
	public function get_from_field($field, $value = NULL)
	{
		$value = $value ? $value : $this->$field;
		$sql = "SELECT * FROM ".$this->db_table." WHERE ".$field." = '".$value."' LIMIT 1";
		$result = $this->db->query($sql);
		$index = $this->db_index;

		if(!$result->num_rows())
		{
			return 0;	
		}
		else
		{
			return $this->get($result->row()->$index);	
		}

	}

	public function db_fields()
	{
		$fields = array();
		
		foreach($this->db_fields as $field)
		{
			$fields[$field] = $this->$field;	
		}
		foreach($this->file_tags as $file_tag)
		{
			$fields[$file_tag] = $this->$file_tag;	
		}
		return $fields;
	}
		
	public function get_id_from_field($field, $value = NULL)
	{
		$value = $value ? $value : $this->$field; 
		$sql = "SELECT * FROM ".$this->db_table." WHERE ".$field." = '".$value."' LIMIT 1";
		$result = $this->db->query($sql);
		$index = $this->db_index;
		if(!$result->num_rows())
		{
			return 0;	
		}
		else
		{
			$this->set_id($result->row()->$index);
			return 1;	
		}
	}
	protected function post_delete(){$this->post_change();}
	protected function post_create(){}
	protected function post_update(){}
	protected function post_change(){}
	protected function post_save(){}
	protected function pre_delete(){$this->pre_change();}
	protected function pre_create(){}
	protected function pre_update(){}
	protected function pre_change(){}
	protected function pre_save(){}	

	/*returns duplicated objet */
	public function duplicate($switch = true)
	{
		if(!$this->get_id()) return 0;	
		
		$this->remove_id();		
		if(is_array($this->file_managers_fields))
		{
			foreach($this->file_managers_fields as $file_manager_tag)
			{

				$file_manager = new File_manager_model();

				if($this->$file_manager_tag && $file_manager->get($this->$file_manager_tag))
				{
					$file_manager->duplicate();
					
					$this->$file_manager_tag = $file_manager->get_id();
					$this->update();	
				}	
			}
		}
		$this->create();
		return 1;
	}
	
	public function has_field($field)
	{
		return in_array($field,$this->db_fields);	
	}

}
?>