<?
require_once(dirname(__FILE__)."/../simple_data_model.php");

class File_manager_model extends Simple_data_model
{	
	public $file_upload = array();

   	public $files_list = array();
	public $url_list = array();
   	public $db_index = 'file_manager_id|id';
    public $db_table = 'file_managers';

    protected $db_fields = array(
								'file_manager|char',
                                'date_created|datetime');

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model("/admin/file_model","file_model");	
	}
	
	public function get_files()
	{
		$sql = "SELECT ffm . *, f.*
				FROM file_managers_files AS ffm
				LEFT JOIN files AS f ON ( ffm.file_id = f.file_id )
				WHERE ffm.file_manager_id = '".$this->get_id()."' ORDER BY f.date_created DESC";
		$query = $this->db->query($sql);
		
		if(!$query->num_rows())
		{
			return 0;
		}
		$this->files_list = array();
		$this->url_list = array();
		foreach($query->result_array() as $row)
		{
			$file =	new File_model();
			$file->set($row);
			$this->files_list[$row['tag']? $row['tag'] : 1][$row['file_id']] = $file;
			$this->url_list[$row['tag']? $row['tag'] : 1][$row['file_id']] = $file->get_url();		
		}
		
		return 1;
	}
	
	public function delete_file_by_tag($tag)
	{
		$file = $this->files_list[$tag];
		if($file)
		{
			$file->delete();
			$sql = "DELETE FROM file_managers_files WHERE file_manager_id = '".$this->get_id()."' AND tag = '".$tag."'";
			$this->db->query($sql);		
		}	
	}
	
	public function delete_file($file_id)
	{
		$sql = "SELECT * FROM file_managers_files WHERE file_manager_id = '".$this->get_id()."' AND file_id ='".(int)$file_id."'";
		$result = $this->db->query($sql);
		if(!$result->num_rows())
		{
			return 0;	
		}
		$this->file_model->delete((int)$file_id);
		$sql = "DELETE FROM file_managers_files WHERE file_id = '".(int)$file_id."'";
		$this->db->query($sql);
		return 1;
	}
	
	public function delete($id = NULL)
	{
		$this->delete_files($id);
		return parent::delete($id);
	}
	
	public function delete_files($id = NULL)
	{
		$id = $id ? $id : $this->get_id();
		$sql = "SELECT * FROM file_managers_files WHERE file_manager_id = '".$id."'";
		$result = $this->db->query($sql);
		
		if(!$result->num_rows())
		{
			return 0;	
		}	
		foreach($result->result_array() as $row)
		{
			$file =	new File_model();
			$file->delete($row['file_id']);
		}
		return 1;	
	}


	public function upload($file_fields)
	{
 		$file_config['upload_path'] = base_dir().'uploads/files/';
		$file_config['allowed_types'] = 'gif|jpg|jpeg|png|docx|pdf|xls|csv|txt';
		$this->load->library('upload');
		$this->load->library('image_lib');
		foreach($file_fields as $field_id => $field_attrs)
		{
			$file_data = array();
			$path_parts = pathinfo($_FILES[$field_id]['name']);
			switch($field_attrs['type'])
			{
				case "image":
							$file_data['group'] = $this->input->post('group');
							$file_data['file'] = $_FILES[$field_id]['name'];
							$file_data['file_name'] = $path_parts['filename']; 
							$file_data['type'] = $field_attrs['type'];
							$file_data['ext'] = $path_parts['extension'];
							break;							
				case "video":
							$file_data['group'] = $this->input->post('group');
							$file_data['file'] = $this->input->post($field_id."_video_name");
							$file_data['file_name'] = $this->input->post($field_id."_video_name");
							$file_data['type'] = $field_attrs['type'];
							$url_vars = array();
							parse_str(parse_url($this->input->post($field_id."_video_code"), PHP_URL_QUERY), $url_vars);
							$file_data['code'] = $url_vars['v'];
							break;
				case "file":
							$file_data['group'] = $this->input->post('group');
							$file_data['file'] = $_FILES[$field_id]['name'];
							$file_data['file_name'] = $path_parts['filename']; 
							$file_data['type'] = $field_attrs['type'];
							$file_data['ext'] = $path_parts['extension'];
							break;
			}
			if($file_data['file'])
			{
				$this->file_model->set($file_data);
				if($this->file_model->create())
				{
					if($file_data['type'] != "video")
					{
						$file_config['file_name'] = $this->file_model->get_system_file_name();
						$this->upload->initialize($file_config);
						$this->upload->do_upload($field_id);
						$upload_data = $this->upload->data();
						if($this->upload->display_errors())
						{
							$this->_error("Error uploading ".$this->file_model->file." <br>".$this->upload->display_errors());
							$this->file_model->delete();
						}
					
						$image_lib_config = array();
						$image_lib_config['image_library'] = 'gd2';
						$image_lib_config['source_image'] = $upload_data['full_path'];
	
						if(($field_attrs['type'] == 'image') && ($field_attrs['width'] || $field_attrs['height']))
						{
							$image_lib_config['maintain_ratio'] = $field_attrs['fit_in_area'] ? true : false;
							$image_lib_config['width'] = $field_attrs['width'];
							$image_lib_config['height'] = $field_attrs['height'];
							
							$this->image_lib->initialize($image_lib_config); 					
							$this->image_lib->resize();						
							
						}
						if(!$field_attrs['no_thumb']) // autothumbing
						{
							$image_lib_config['maintain_ratio'] = true;
							$image_lib_config['create_thumb'] = true;
							$image_lib_config['width'] = 200;
							$image_lib_config['height'] = 200;
							$this->image_lib->initialize($image_lib_config); 					
							$this->image_lib->resize();
						}						
					}
					// resize image if needed.
					
					$this->relate_file_to_manager($field_attrs, $this->file_model->get_id(),$field_id);
				}
				else
				{
					$this->_error("Error while creating file: ".$this->file_model->get_error_messages());
				}
				
			}
		}
		return $this->has_errors();		
	}
	
	
	protected function relate_file_to_manager($field_attrs, $file_id, $field_id)
	{
		if($field_attrs['tag'])
		{
			$sql = "SELECT file_id FROM file_managers_files WHERE file_manager_id = '".$this->get_id()."' AND tag = '".$field_attrs['tag']."' AND file_id < '".$file_id."'";
			$query= $this->db->query($sql);
			if ($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
				  $file_model = new File_model();
				  $file_model->delete($row->file_id);
				}
				$sql = "DELETE FROM file_managers_files WHERE file_manager_id = '".$this->get_id()."' AND tag = '".$field_attrs['tag']."'";
				$this->db->query($sql);
			}
		}
		$sql = "INSERT INTO file_managers_files VALUES ('".$this->get_id()."','".$file_id."','".($field_attrs['tag'] ? $field_attrs['tag'] : $field_id)."',0)";
		$this->db->query($sql);
	}
	
	public function duplicate()
	{
		if(!$this->get_id()) return 0;	
		$this->get_files();
		$prev_list = $this->files_list;	
		
		$this->remove_id();		
		$this->create();
		foreach($prev_list as $file_tag => $file_id)
		{
			foreach($file_id as $file)
			{
				$file->duplicate();	
				$sql = "INSERT INTO file_managers_files VALUES ('".$this->get_id()."','".$file->get_id()."','".$file_tag."',0)";
				$this->db->query($sql);
			}
				
		}
			
	}
	
}

?>