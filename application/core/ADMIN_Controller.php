<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__)."/ADMIN_Relations.php");

class ADMIN_Controller extends ADMIN_Relations {

	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('own_content',!$this->bitauth->is_admin());		
		$this->user_data = $this->session->userdata($this->data['site_config']['user_session']);
	
		$this->ini_condition = $this->ini_condition ? $this->ini_condition : 1;	
		
		if($this->user_content_only())
		{
			$this->ini_condition .= " AND creator_id = '".$this->session->userdata('user_id')."'";	
		}
		
		if(($this->bitauth->is_admin()) && $this->bitauth->admin_override)
		{
			$this->ini_condition = "";
		}		
	}
	
	public function autocomplete($field_id)
	{
		$this->data['fields'][$field_id];
			
	}
	
	public function show_list($parent_id = '')
	{

		
		$this->data['parent_id'] = $parent_id;
		switch( $this->data['list_type'] )
		{
			case 'tree':
						$config_tree = array('table' => $this->main_model->db_table, 'id' =>  $this->main_model->db_index);
						$this->load->library('tree_json', $config_tree);

						$this->tree_json->show_list();
						break;

			case 'datatable':
			default:
						$this->load->library('datatables');
						$this->datatables->show_list();
						break;
			
		}
	}

	public function ajax_datatable()
	{
		if($_GET['parent_id'])
		{
			$this->ini_condition .= " AND ".$this->main_model->parent_db_index." = '".$_GET['parent_id']."'";
		}
		$this->load->library('datatables');
		$this->datatables->ajax_datatable();
	}

	public function export_datatable($method)
	{
		$this->load->library('datatables');
		
		$result = $this->datatables->export_query();
		
		$filename = $this->class_name."_table";
		switch($method)
		{
			case 'csv':
						$this->load->model("csv_writer_model",'file_writer_model');										
						$this->file_writer_model->show_headers($filename);
						foreach($result->result_array() as $row)
						{
							$this->file_writer_model->write_array($row);	
						}
						$this->file_writer_model->close_flush();
						break;
			case 'xls':
						$this->load->helper("excel_helper");
						to_excel($result,$filename);
						break;
		}
		
	}

	public function alpha_numeric_space($str)
	{
		if (!preg_match("/^([a-zA-Z0-9 ])+$/i", $str))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	protected function pre_validate_save(){}
	
	public function validate_save($page = "create")
	{
		$this->pre_validate_save();
		$post = $this->input->post();
		
		$this->form_validation->set_message('matches', '%s y %s deben ser iguales');
		$this->form_validation->set_message('required', 'Falta completar el campo %s');
		$this->form_validation->set_message('valid_email', 'El email ingresado no es v&aacute;lido');
		$this->form_validation->set_message('alpha_numeric_space', 'El campo %s solo puede contener letras, n&uacute;meros y espacios');
		
		if( isset($this->data['page_fields'][$page]) )
		{
			foreach($this->data['page_fields'][$page] as $field_id => $field)
			{
				$this->form_validation->set_rules($field_id, $field['label'], $field['validation']);
				if($field['type'] == 'date_time')
				{
					$post[$field_id] = dateFormat(str_replace("/",".",$post[$field_id]), 'Y-m-d H:i:s');
				}
			}	
		}
		
		if(!$this->form_validation->run())
		{
			$output['valid'] = 0;
			$output['errors'] = validation_errors();
		}
		else
		{
			$this->main_model->get($this->input->post($this->main_model->db_index));
			$this->main_model->set($post);
			if(($this->bitauth->group_id == 2) && ($this->main_model->company_id != $this->bitauth->company_id))
			{
				$output['errors'][] = "No se pudo moficar el registro. ";
				 
				$output['errors'][] = "Contáctese con el administrador.";
				$output['valid'] = 0;
				echo json_encode($output);
				return;
			}
			
			if($page == 'edit_password')
			{
				$pass = $this->bitauth->hash_password($post['password']);
				$last_set = $this->bitauth->timestamp();
				$this->main_model->set_field("password",$pass);
				$this->main_model->set_field("password_last_set",$last_set);
				$this->main_model->update();
				$output['valid'] = 1;
				echo json_encode($output);
				return;
			}
			if($this->main_model->save())
			{
				if(is_array($this->multiselect_fields))
				{
					foreach($this->multiselect_fields as $field_id => $field)
					{
						$sql = "DELETE FROM ".$field['source_relation_table']." WHERE  ".$this->main_model->db_index." = ".$this->main_model->get_id();
						$this->db->query($sql);
						if(is_array($post[$field_id]))
						{
							foreach($post[$field_id] as $value)
							{
								$sql = "INSERT INTO ".$field['source_relation_table']." (".$this->main_model->db_index.",".$field['source_index_id'].")
										VALUES (".$this->main_model->get_id().",".$value.")";
								$this->db->query($sql);
							}
							$source_fields = implode(",",$field['source_fields']);
							$sql = "SELECT {$source_fields} as label FROM ".$field['source_table']." WHERE ".$field['source_index_id']." IN ('".implode("','",$post[$field_id])."')";
							$result = $this->db->query($sql)->result_array();
							
							$field = "";
							foreach($result as $row)
							{
								$field .= "<b>".$row['label']."</b> ; ";	
							}
							
							$this->main_model->set_field($field_id,$field);
							$this->main_model->update();
						}
					}
				}

				if(is_array($this->file_fields) && is_array($_FILES))
				{
					$this->get_file_manager();
					if(!$this->file_manager->upload($this->file_fields))
					{
						$output['errors'][] = "Error al subir los archivos".
						$output['valid'] = 0;
					}
					else
					{
						$output['valid'] = 1;	
					}
				}
				else
				{
					$output['valid'] = 1;	
				}
			}
			else
			{
				$output['valid'] = 0;
				$output['errors'] = $this->main_model->get_error_messages();	
			}
		}
		echo json_encode($output);
	}

	public function get_file_manager()
	{
		$this->load->model('admin/file_manager_model', 'file_manager');			
		if($this->main_model->file_manager_id && $this->file_manager->get($this->main_model->file_manager_id))
		{
			return;
		}
		else
		{
			$this->file_manager->create();
			$this->main_model->set_field('file_manager_id',$this->file_manager->get_id());
			$this->main_model->save();	
		}	
	}

	public function details($id = NULL)
	{									
		if(!$this->main_model->get($id) || !$this->has_permission('details'))
		{
			$this->show_list();
		}
		else
		{
			$this->data['current_tab'] = 'details';
			$this->load->view('admin/common/inc.details.php', $this->data);	
		}
	}

	public function edit($id)
	{
		if(!$this->main_model->get($id) || !$this->has_permission('edit'))
		{
			$this->show_list();
		}
		else
		{
			$this->data['form_action'] = base_url()."admin/".$this->class_name."/validate_save/edit";				
			$this->data['form_url_success'] = $this->class_name."/details/".$id;	
			$this->data['form_url_list']  = $this->class_name."/show_list/";
			$this->data['current_tab'] = 'edit';
			$this->load->view('admin/common/inc.save.php', $this->data);	
		}
	}	

	public function edit_in_place($field)
	{
			if($this->data['datatable_details']) // id is inside image tag
			{
				$id = $_POST['id'];
				$doc = new DOMDocument();
				$doc->loadHTML($id);
				$imageTags = $doc->getElementsByTagName('img');
				foreach($imageTags as $tag) {
					$id = $tag->getAttribute('index');
				}	
			}
			else
			{
				$id = $_POST['id'];	
			}
			if($field && $id)
			{
				$sql = "UPDATE ".$this->main_model->db_table." SET ".$field." = '".$_POST['value']."' WHERE ".$this->main_model->db_index." = '".$id."'";
				$this->db->query($sql);
			}
	}

	public function delete($id, $parent_id='')
	{
		
		if(!$this->main_model->get($id)  || !$this->has_permission('delete'))
		{
			redirect("admin/home#".$this->class_name."/show_list/".$parent_id);
		}
		else
		{
			$this->main_model->delete();
			$this->show_list();
		}
	}

	public function media_gallery($type,$id,$rand= NULL)
	{
		
		if(!$this->main_model->get($id) || !$this->has_permission('media_gallery'))
		{
			$this->show_list();
		}
		else
		{
			$this->get_file_manager();
		
			$this->data['form_action'] = base_url()."admin/".$this->class_name."/validate_save/media_gallery";			
			$this->data['form_url_success'] = $this->class_name."/media_gallery/".$type."/".$id."/".rand();
			$this->data['current_tab'] = 'media_gallery';
			$this->data['delete_url'] = base_url()."admin/".$this->class_name."/delete_file/".$this->file_manager->get_id()."/";	
			$this->load->view("admin/common/inc.media.php",$this->data);
		}
	}

	public function delete_file($file_manager_id, $file_id)
	{
		$this->load->model('admin/file_manager_model', 'file_manager');			
		
		if($this->file_manager->get($file_manager_id))
		{
			return $this->file_manager->delete_file($file_id);	
		}

	}

	public function create($parent_id = '')
	{
		if(!$this->has_permission('create')) $this->show_list();

		$this->data['form_action'] = base_url()."admin/".$this->class_name."/validate_save/create";
		$this->data['form_url_success'] = $this->class_name."/show_list/".$parent_id;
		$this->data['form_url_new']  = $this->class_name."/create/";
		$this->data['current_tab'] = 'create';
		$this->data['parent_id'] = $parent_id;
		$this->load->view('admin/common/inc.save.php', $this->data);	
	}
	
	function crop_image($file_id, $width=200, $height=200)
	{
		$this->load->model('admin/file_model');
		if(!$this->file_model->get($file_id))
		{
			echo "Ocurrio un error";
		}
		else
		{
			$this->data['crop_width'] = $width;
			$this->data['crop_height'] = $height;
			$this->load->view('admin/common/inc.imagecrop.php', $this->data);
		}
	}
	
	function create_crop($file_id, $crop_width, $crop_height)
	{
		$this->load->model('admin/file_model');
		if(!$this->file_model->get($file_id))
		{
			echo "Ocurrio un error";
		}
		else
		{
			$src_image = $this->file_model->get_system_file_name();
			$x = $_POST['x1'];
			$y = $_POST['y1'];
			$width = $_POST['w'];
			$height = $_POST['h'];
			
			if($this->file_model->create_crop($src_image, $x, $y, $width, $height, $crop_width, $crop_height))
			{
				$output['valid'] = 1;
			}
			else
			{
				$output['valid'] = 0;
				$output['errors'] = 'Hubo un error al generar la im&aacute;gen.';
			}
		}

		echo json_encode($output);
		return true;
	}
	
}
?>