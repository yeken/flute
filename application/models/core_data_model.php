<?php
class Core_data_model extends CI_Model
{
    protected $error_msg = "";
	public $db_field_types = array();
	public $db_table_indexes = array();
	public $_db_fields = array();
   	public $db_index = ''; // index_id|id
	public $delete_table = '';
    public $db_table = '';
	public $drop_fields = true;	
	
    public function  __construct() 
	{
        parent::__construct();
        $this->load->database();
        $this->load->library('security');

    
		$this->db_field_types = array(
									'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
									'char' => "varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL",
									'email' => "varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL",
									'varchar' => "varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL",
									'smallchar' => "varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL",
									'bool' => "tinyint(1) unsigned NOT NULL",
									'boolean' => "tinyint(1) unsigned NOT NULL",
									'mediumint' => "mediumint(6) unsigned NOT NULL",
									'int' => "int(11) unsigned NOT NULL",
									'bigint' => "bigint(20) unsigned NOT NULL",
									'text' => "text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL",
									'float' => "float(9,2) NOT NULL",
									'date' => "date NOT NULL",
									'datetime' => "datetime NOT NULL",
									'script' => "text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL",
									'enum' => "enum(*) NOT NULL");
	
		$this->db_fields_types_comparison = array(
									'id' => "int(11) unsigned",
									'char' => "varchar(255)",
									'email' => "varchar(255)",
									'varchar' => "varchar(255)",
									'smallchar' => "varchar(10)",
									'bool' => "tinyint(1) unsigned",
									'mediumint' => "mediumint(6) unsigned",
									'int' => "int(11) unsigned",
									'bigint' => "bigint(20) unsigned",
									'text' => "text",
									'float' => "float(9,2)",
									'date' => "date",
									'datetime' => "datetime",
									'script' => "text",
									'enum' => "enum");
		
		$this->db_table_indexes = array(
									'primary' => "PRIMARY KEY (`*`)",
									'index' => "KEY `*` (`*`)",
									'unique' => "UNIQUE KEY `*` (`*`)",
									'fulltext' => "FULLTEXT (`*`)"
									);
	
	
	
		array_unshift($this->db_fields, $this->db_index);
		$this->db_index = explode("|",$this->db_index);
		$this->db_index = $this->db_index[0];
		$this->_db_fields[$this->db_index]['index'] = 'primary';
		$this->_db_fields = array();
        if(count($this->db_fields))
		{
			foreach($this->db_fields as $field_id => $field)
			{
				$field = explode("|",$field);
				$fieldname = $field[0];
				if($fieldname)
				{
					$this->$fieldname = null;
					$this->_db_fields[$fieldname]['type'] = $field[1];
					$this->_db_fields[$fieldname]['index'] = $field[1] == 'id' ? "primary" : $field[2];
					$this->_db_fields[$fieldname]['comment'] = $this->db_fields_comments[$fieldname];
					$this->db_fields[$field_id] = $fieldname;
				}
			}
		}
		if(!count($this->file_manager_fields))
		{
			if(in_array('file_manager_id',$this->db_fields))
			{
				array_push($this->file_managers_fields,'file_manager_id');	
			}
		}	
	}


	public function sanitize_field($field)
	{
		$str = $this->$field;
		$type = $this->_db_fields[$field]['type'] ? $this->_db_fields[$field]['type'] : "";
		
		return $this->sanitize($str, $type);	
	}
	
    public function sanitize($str, $type = NULL)
    {
	   if(!$type)
            return mysql_real_escape_string($str);

       switch($type)
       {
           	case 'mediumint':
			case 'id':
			case 'bool':
			case 'int':
                        $str = (int)$str;
                        break;
           	case 'float':
                        $str = (float)$str;
                        break;
           	case 'email':
                        $str = $this->security->xss_clean(filter_var($str,FILTER_SANITIZE_EMAIL));
                        break;
			case 'script':
						if(get_magic_quotes_gpc()) $str = stripslashes($str);
						$str = trim($str);
						$str = mysql_real_escape_string($str);
						break;
           	default:
                        $str = mysql_real_escape_string($str);
       					break;
	   }
       return $str;
    }

	public function has_errors()
	{
		return count($this->error_msg);
	}

    public function get_error_messages()
    {
        return $this->error_msg;
    }

    public function _error($msg)
    {
        $this->error_msg[] = $msg;
        return 0;
    }
	
	public function update_tables()
	{
		if(!$this->db_table)
		{
			echo "<h4><b>".get_class($this)."</b>: No table defined </h4>";
			return;	
		}
		echo "<h4><b>".get_class($this)."</b></h4>";
		$sql = "SHOW TABLES like '".$this->db_table."'";
		if($this->db->query($sql)->num_rows())
		{
			if($this->delete_table)
			{
				$sql = "SHOW TABLES like '".$this->delete_table."'";	
			}
			if(!$this->db->query($sql)->num_rows())
			{
				vd("Missing Delete Table. Creating...");
				$this->create_db_table(true);
			}
			
			vd("Altering Table...");
			$this->alter_db_table();
		}
		else
		{
			vd("Creating Table...");
			$this->create_db_table();	
		}
	}
	
	
	public function create_db_table($delete_only = false)
	{		
		$fields = "";
		$indexes = array();
		$error_fields = array();
		foreach($this->_db_fields as $field_id => $data)
		{
			if(strstr($field['type'],'enum'))
			{
				$type = $field['type']." NOT NULL";	
			}
			else
			{
				$type = $this->db_field_types[$data['type']];
			}
			if($type)
			{
				$fields .= "`".$field_id."` ".$type.",";
			}
			else
			{
				$error_fields[]	= $field_id;
			}
			if($data['index'])
			{
				$indexes[] = str_replace("*",$field_id,$this->db_table_indexes[$data['index']]);	
			}
		}
		
		if(count($error_fields))
		{
			vd("Cannot create table. No type defined for fields:". implode(", ",$error_fields));
			vd("<b>- No changes allowed -</b>");
			return;	
		}
		
		$fields[strlen($fields) -1 ] = count($indexes) ? ", ": " ";
		
		if(!$delete_only)
		{
			vd($this->db_table.": Creating Table");
			$sql = "CREATE TABLE IF NOT EXISTS `".$this->db_table."` (
				  ".$fields."
				  ".implode(",",$indexes)."
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
			vd($sql);
			$this->db->query($sql);
		}
		
		if($this->delete_table)
		{
			vd($this->delete_table.": Creating Delete Table");
			$sql = "CREATE TABLE IF NOT EXISTS `".$this->delete_table."` (
			  ".$fields."
			  ".implode(",",$indexes)."
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";			
			$this->db->query($sql);
			vd($sql);
		}
	}
	
	public function alter_db_table()
	{
		$no_change_needed = true;
		$sql = "SHOW FULL COLUMNS FROM ".$this->db_table;
		$result = $this->db->query($sql)->result_array();
		$alter_fields = array();
		$add_fields = array();
		foreach($result as $row)
		{
			if(!$this->_db_fields[$row['Field']])
			{
				$delete_fields[] = "DROP `".$row['Field']."`";
			}
			else
			{				
				if(($row['Type'] == $this->_db_fields[$row['Field']]['type'])||($row['Type'] == $this->db_fields_types_comparison[$this->_db_fields[$row['Field']]['type']]))
				{
					$this->_db_fields[$row['Field']]['no_change'] = true;
				}
				
				$this->_db_fields[$row['Field']]['in_table'] = true;	
				if($row['Key'] && !$this->_db_fields[$row['Field']]['index'])
				{
					switch($row['Key'])
					{
						case 'PRI':
							$this->_db_fields[$row['Field']]['add_index'] = "ADD ".str_replace("*",$row['Field'],$this->db_table_indexes['primary']);
							break;
						case 'UNI':
							$this->_db_fields[$row['Field']]['add_index'] = "ADD ".str_replace("*",$row['Field'],$this->db_table_indexes['unique']);
							break;
						case 'MUL':
							if($this->_db_fields[$row['Field']]['type'] == 'text')
							{
								$this->_db_fields[$row['Field']]['add_index'] = "ADD ".str_replace("*",$row['Field'],$this->db_table_indexes['fulltext']);				
							}
							else
							{
								$this->_db_fields[$row['Field']]['add_index'] = "ADD ".str_replace("*",$row['Field'],$this->db_table_indexes['index']);				
							}
							break;
					} 
				}
			}
		}
		$last_field = "";
		
		$error_fields = array();
		foreach($this->_db_fields as $fieldname => $field)
		{
			if($this->db_field_types[$field['type']])
			{
				if(strstr($field['type'],'enum'))
				{
					$type = $field['type']." NOT NULL";	
				}
				else
				{
					$type = $this->db_field_types[$field['type']];
				}
				
				if($field['in_table'])
				{
					$last_field = $fieldname;
					if(!$field['no_change'])
					{
						$alter_fields[] = "CHANGE `".$fieldname."` `".$fieldname."` ".$type;
					}
				}
				else
				{
					$add_fields[] = "ADD `".$fieldname."` ".$type.($last_field ? " AFTER ".$last_field : "FIRST");		
				}
			}
			else
			{
				$error_fields[] = $fieldname;
			}
			$last_field = $fieldname;
		}
		
		if(count($error_fields))
		{
			vd("Cannot alter table. No type defined for fields:". implode(", ",$error_fields));
			vd("<b>- No changes allowed -</b>");
			return;
		}
		
		if($this->drop_fields && count($delete_fields))
		{
			$no_change_needed = false;
			$sql = "ALTER TABLE `".$this->db_table."` ".implode(" ,",$delete_fields);
			$this->db->query($sql);
			vd($sql);
			if($this->delete_table)
			{
				$sql = "ALTER TABLE `".$this->delete_table."` ".implode(" ,",$delete_fields);
				$this->db->query($sql);				
			}
		}
		
		if(count($alter_fields))
		{
			$no_change_needed = false;
			$sql = "ALTER TABLE `".$this->db_table."` ".implode(" ,",$alter_fields);
			$this->db->query($sql);
			vd($sql);
			if($this->delete_table)
			{
				$sql = "ALTER TABLE `".$this->delete_table."` ".implode(" ,",$alter_fields);
				$this->db->query($sql);
			}
		}
		
		if(count($add_fields))
		{
			$no_change_needed = false;
			$sql = "ALTER TABLE `".$this->db_table."` ".implode(" ,",$add_fields);
			$this->db->query($sql);
			vd($sql);
			if($this->delete_table)
			{
				$sql = "ALTER TABLE `".$this->delete_table."` ".implode(" ,",$alter_fields);
				$this->db->query($sql);
			}	
		}
		
		if($no_change_needed)
		{
			vd("... No changes needed.");	
		}
	}
	
}
?>
