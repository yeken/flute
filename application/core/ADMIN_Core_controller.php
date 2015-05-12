<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ADMIN_core_controller extends CI_Controller
{
	public $breadcrumb_array = array();

	public $data = array();
	public $class_name = '';
	public $user_data;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->class_name = strtolower(get_class($this));
		$this->data['class_name'] = $this->class_name;
		$config = $this->config->item('modules');
		if(is_array($this->load_config))
		{
			$configs = array();
			foreach($this->load_config as $module)
			{
				$configs = array_merge($configs,$config[$module]);	
			}
			$this->data = array_merge($configs, $config[$this->class_name]);	
		}
		else
		{
			$this->data = $config[$this->class_name];	
		}
		$this->restrictions = explode("|",$this->data['restrictions']);

		$site_config = $this->config->item('general');
		$this->data['site_config'] = $site_config[$this->uri->segment(1)];
		$this->load->model($this->data['main_model'], 'main_model');
		
		if($this->data['parent_main_model'])
		{
			$this->load->model($this->data['parent_main_model'], 'parent_main_model');	
		}
		
		$this->ini_condition = $this->data['list_condition'] ? $this->data['list_condition'] : $this->data['ini_condition'];
		$this->admin_override = $this->data['admin_override'];
		
		$this->main_model_tabs = $config[ $this->class_name ]['main_model_tabs'];
		$this->datatable_actions = $config[ $this->class_name ]['datatable_actions'];
		$this->user_data = $this->session->userdata($this->data['site_config']['user_session']);
		// redirect if not logged in, only if url is different from the user login page.
		
		$this->session->set_userdata('user_id',$this->bitauth->user_id);
		if($this->uri->segment(2) == 'generame-la-clave')
		{
			$uri_string = $this->uri->segment(1)."/".$this->uri->segment(2);
		}
		else
		{
			$uri_string = $this->uri->uri_string();
		}
		$allowed_pages = array('admin/user/login','admin/user/forgot_password','admin/olvide-la-clave','admin/generame-la-clave');		
		
		if(!in_array($uri_string,$allowed_pages))
		{
			if(!$this->bitauth->logged_in() && (!$this->bitauth->is_admin() || $this->bitauth->is_in_group(2)))
			{
				$this->session->set_userdata('user_id',$this->bitauth->user_id);
				$this->session->set_userdata('username',$this->bitauth->username);
				
				redirect($this->data['site_config']['login_page'], 'refresh');	
			}
		}
		$sql2 = "SET  @@session.sql_mode='';";
		$this->db->query($sql2);
		
		$this->split_fields();
		$this->get_parent_methods();
	}

	public function set_section()
	{
		echo "<script>$('#section-".$this->class_name."').addClass('active')</script>";	
		
	}

	public function has_permission($perm)
	{
		if($this->bitauth->is_admin()) return true;	
	
		return !in_array($perm,$this->restrictions);
	}
	
	public function user_content_only()
	{
		if($this->bitauth->is_admin()) return false;	
	
		return in_array('own_content',$this->restrictions);
		
	}

	public function make_breadcrumb($current_method, $id='', $model=NULL)
	{
		if(is_array($this->data['parent_index_route']))
		{
			$this->breadcrumb_array[$this->data['parent_index_route']['label']] = '#'.$this->data['parent_index_route']['url'];
		}
		
		if($id!='undefined') // show list should be the only method without main_model id. therefore parent is taken from main_model
		{
				if(!in_array($current_method,$this->data['parent_methods']))
				{
					$this->main_model->get($id);
					if($this->data['parent_main_model'] && $this->parent_main_model->get($this->main_model->get_parent_id()))
					{
						$this->breadcrumb_array[$this->parent_main_model->get_label()] = '#'.$this->data['parent_description_route'].$this->parent_main_model->get_id();
						$parent_id = $this->parent_main_model->get_id();
					}	
				}
				else
				{
					if($this->data['parent_main_model'] && $this->parent_main_model->get($id))
					{
						$this->breadcrumb_array[$this->parent_main_model->get_label()] = '#'.$this->data['parent_description_route'].$this->parent_main_model->get_id();
						$parent_id = $this->parent_main_model->get_id();
					}
				}
		}
		
		$module_url = $this->data['module_url'] ? $this->data['module_url'] : '#'.$this->class_name.'/show_list/'.$parent_id;
		
		if(!$this->main_model)
		{
			$this->breadcrumb_array = array();	
		}
		else
		{
			switch($current_method)
			{
				case 'create':
					$this->breadcrumb_array[$this->data['module_label']] = $module_url;
					$this->breadcrumb_array["Agregar ".$this->unit_label] = '#'.$this->class_name.'/create/'.$parent_id;
					break;
				case 'relation':
					//$this->main_model->get_relation($model, $id);
					$this->breadcrumb_array[$this->data['module_label']] = $module_url;
					$this->breadcrumb_array[$this->main_model->get_label()] = '#'.$this->class_name.'/details/'.$this->main_model->get_id();
					$this->breadcrumb_array['Relaci&oacute;n'] = '#'.$this->class_name.'/relation/user/'.$this->main_model->get_id();
	
				default: 
					$this->breadcrumb_array[$this->data['module_label']] = $module_url;
					$this->breadcrumb_array[$this->main_model->get_label()] = '#'.$this->class_name.'/details/'.$this->main_model->get_id();
					
				break;
			}
		}
		$this->load_breadcrumb();
	}

	public function load_breadcrumb($breadcrumb=NULL)
	{

		$bc = ($breadcrumb==NULL)?$this->breadcrumb_array:$breadcrumb;

		if(is_array($bc) && count($bc) > 0 )
		{
			$this->breadcrumb->clear();
			$this->breadcrumb->push_as_array($bc);
			echo $this->breadcrumb->get();
		}
		$this->set_section();
	}

	protected function get_parent_methods()
	{
		if(is_array($this->data['top_menu_actions']))
		{
			foreach($this->data['top_menu_actions'] as $action_id => $details)
			{
				if($this->has_permission($action_id))
					$this->data['parent_methods'][] = $details['method'];	
			}
		}
	}
	/*
	Splits the fields array into page_fields[$visible_page][field_id][field_attributes]
	*/
	
	protected function set_field_attrs($field,$attr,$value)
	{
		if($this->data['fields'][$field])
		{
			$this->data['fields'][$field][$attr] = $value;
			if($this->data['fields'][$field]['visibility'])
			{
				$visibilities = explode("|", $this->data['fields'][$field]['visibility']);
				foreach($visibilities as $visibility)
				{
					$displays = explode(":",$visibility);
					
					$attrs['display'] = $displays[1] ? $displays[1] : "left";
					$this->data['page_fields'][$displays[0]][$field][$attr] = $value;
				}
			}
		}
	}
	
	
	protected function split_fields()
	{
		if(!is_array($this->data['fields'])) return 0;
		
		$file_types = array('image','image_gallery','video','archive','file');
		$this->data['page_fields'] = array();
		$this->data['page_displays'] = array();
		foreach($this->data['fields'] as $field_id => $attrs)
		{
			if($attrs['visibility'])
			{
				$visibilities = explode("|", $attrs['visibility']);
				foreach($visibilities as $visibility)
				{
					$displays = explode(":",$visibility);
					
					$attrs['display'] = $displays[1] ? $displays[1] : "left";
					
					if($displays[0] == 'save')
					{
						$this->data['page_fields']['create'][$field_id] = $attrs;
						$this->data['page_displays']['create'][$attrs['display']][] = $field_id;
						
						$this->data['page_fields']['edit'][$field_id] = $attrs;
						$this->data['page_displays']['edit'][$attrs['display']][] = $field_id;						
					}
					else
					{
						$this->data['page_fields'][$displays[0]][$field_id] = $attrs;
						$this->data['page_displays'][$displays[0]][$attrs['display']][] = $field_id;
					}
				}
			}
			if($attrs['list_visibility'] == 'expanded')
			{
				$this->data['datatable_details'] = true;	
			}
			
			if($attrs['list_editable'])
			{
				$this->data['list_editable_fields'][$field_id] = $attrs;	
			}
			// insert file fields;
			if(in_array($attrs['type'],$file_types))
			{
				$this->file_fields[$field_id] = $attrs;
			}
		}
		$this->split_relation_fields();
	}
	
	/*
	Splits the relation fields array into data['relation'][$model]['page_fields'][$visible_page][$field_id][$field_attributes]
	*/
	protected function split_relation_fields()
	{
		if(!is_array($this->data['relation'])) return 0;
		
		$file_types = array('image','video','archive');
		foreach($this->data['relation'] as $model => $model_attrs)
		{
			$this->data['relation'][$model]['page_fields'] = array();
			foreach($model_attrs['fields'] as $field_id => $attrs)
			{
				if($attrs['visibility'])
				{
					$visibilities = explode("|", $attrs['visibility']);
					foreach($visibilities as $visibility)
					{
						$this->data['relation'][$model]['page_fields'][$visibility][$field_id] = $attrs;	
					}
				}
			}
		}
	}

	protected function replace_vars(&$field)
	{
		// '$this->var' is replaced by [[var]] 
		$var = get_text_between_tags("[[","]]",$field);
		if($var)
		{
			$vars = explode("->",$var);
			switch(count($vars))
			{
				case 1: $var = $this->$vars[0];
						break;
				case 2: $var = $this->$vars[0]->$vars[1];
						break;
				case 3: $var = $this->$vars[0]->$vars[1]->$vars[2];
						break;
				case 4: $var = $this->$vars[0]->$vars[1]->$vars[2]->$vars[3];
			}
			$field = replace_text_between_tags("[[","]]" ,$var, $field, true);
		}
	}
}

?>