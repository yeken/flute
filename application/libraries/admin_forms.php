<?php
class admin_forms {

	// Structure table and fields
	protected $field_name;
	protected $field_attrs;
	protected $prev_val;
	protected $description;
	protected $common_attrs;
	public $label_in_input = false;
	
	// Constructor
	function __construct($config = array()) {
		$this->ci =& get_instance();
	}

	function input_block($field_name,$field_attrs, $prev_val = '', $description = '')
	{
		$this->field_name = $field_attrs['array'] ? $field_attrs['array']."[".$field_name."]" : $field_name;
		$this->field_attrs = $field_attrs;
		$this->prev_val = $prev_val;
		$this->description = $description ? $description : $field_attrs['description'];
		$this->common_attrs =  "name='".$this->field_name."' class='form-control ".($this->field_attrs['class'] ? $this->field_attrs['class'] : "")."' ".($this->field_attrs['style'] ? " style='".$this->field_attrs['style']."' " : "")." ".$this->field_attrs['attrs'];
		$this->common_attrs .= $this->field_attrs['disabled'] ? "disabled='disabled'" : "";
		
		switch($this->field_attrs['type'])
		{
			case 'autocomplete':
				$str = $this->_autocomplete();
				break;
			case 'text':
				$str = $this->_text();
				break;
			case 'password':
				$str = $this->_password();
				break;
			case 'address':
				$str = $this->_address();
				break;
			case 'tree_select':
				$str = $this->_tree_select();
				break;
			case 'script':
			case 'textarea':
				$str = $this->_textarea();
				break;
			case 'select':
				$str = $this->_select();
				break;
			case 'select_address':
				$str = $this->_select_address();
				break;	
			case 'multiselect':
				$str = $this->_multiselect();
				break;
			case 'radio':
				$str = $this->_radio();
				break;	
			case 'checkbox':
				$str = $this->_checkbox();
				break;
			case 'date_time':
				$str = $this->_date_time();
				break;
			case 'date':
				$str = $this->_date();
				break;
			case 'image_gallery':
				$str = $this->_image_gallery();
				break;
			case 'image':
				$str = $this->_image();
				break;
			case 'video':
				$str = $this->_video();
				break;
			case 'file':
				$str = $this->_file();
				break;
			case 'hidden':
				$str = $this->_hidden();
				break;			
			default: false;
		}
		return $str;
	}

	protected function _autocomplete()
	{
		$this->common_attrs =  "name='".$this->field_name."' class='form-control autocomplete ".($this->field_attrs['class'] ? $this->field_attrs['class'] : "")."' ".($this->field_attrs['style'] ? " style='".$this->field_attrs['style']."' " : "")." ".$this->field_attrs['attrs'];
		$this->common_attrs .= $this->field_attrs['disabled'] ? "disabled='disabled'" : "";

		$val = str_replace('"','\'',$this->prev_val);
		if($this->label_in_input)
		{
			$str .= "<div class='col-sm-12 col-md-10'>";	
			$str .=	"<input type='".$this->field_attrs['type']."' id='".$this->field_name."' ".$this->common_attrs." value=\"".$val."\"/ placeholder='".$this->field_attrs['label']."'>";
			$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
			$str .= "</div>";
					
		}
		else
		{
			$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
			$str .= "<div class='col-sm-12 col-md-10'>";	
			$str .=	"<input type='".$this->field_attrs['type']."' id='".$this->field_name."' ".$this->common_attrs." value=\"".$val."\"/>";
			$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
			$str .= "</div>";
		}
		if($this->field_attrs['values'])
		{
			$str .= 	"<script>
					$('#".$this->field_name."').typeahead({                                
					  name: '',                                                          
					  local: [\"".implode('","',$this->field_attrs['values'])."\"],                                       
					  limit: 10                                                                   
					});
					</script>";
			return $str;	
		}
		
		
		if($this->field_attrs['local'])
		{
			$source_fields = implode(",",$this->field_attrs['source_fields']);
			$source_condition = ((isset($this->field_attrs['source_condition'])&&$this->field_attrs['source_condition']!='')?" AND ".$this->field_attrs['source_condition']." ":"");
			$sql = "SELECT DISTINCT {$source_fields} as label FROM ".$this->field_attrs['source_table']." WHERE 1=1 {$source_condition} ORDER BY ".$this->field_attrs['source_fields'][0]." ASC";
			$res = $this->ci->db->query($sql)->result_array();
			foreach($res as $row)
			{
				$opt[] = $row['label'];
			}
			
			$str .= 	"<script>
						$('#".$this->field_name."').typeahead({                                
						  name: '',                                                          
						  local: [\"".(is_array($opt) ? implode('","',$opt) : "")."\"],                                       
						  limit: 10                                                                   
						});
						</script>
						";
		}
		return $str;
	}

	protected function _hidden()
	{	
		$value = $this->prev_val? $this->prev_val : $this->field_attrs['value'];
		$str .=	"<input type='hidden' id='".$this->field_name."' name='".$this->field_name."' value='".$value."'/>";
		return $str;				
	}
	
	protected function _password()
	{
		$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= "<div class='col-sm-12 col-md-10'>";	
		$str .=	"<input type='".$this->field_attrs['type']."' id='".$this->field_name."' ".$this->common_attrs."/>";
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		$str .= "</div>";
		return $str;				
	}
		
	protected function _image_gallery()
	{
		$str .= "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>Seleccionar imagen</label>";
		$str .= "<span class='form_input'>";
		$str .= "<div>".$img."<input type='file' id='".$this->field_name."' ".$this->common_attrs." value='".$this->prev_val."'/></div>";
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		$str .= "</span>";
		return $str;						
	}
	
	protected function _date()
	{
		switch($this->prev_val)
		{
			case 'today':
							$prev_val = date("Y-m-d");
							break;	
			default:
						$prev_val = $this->prev_val;
						break;
		}
		
		$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= "<span class='form_input'>";
		$str .= "<div class='col-sm-12 col-md-10'>".selectDates($prev_val, $this->field_name, $this->field_attrs['class'],$this->field_attrs['offset_up'] ? $this->field_attrs['offset_up'] : 1,$this->field_attrs['offset_down'] ? $this->field_attrs['offset_down'] : 10);
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		$str .= "</div></span>";
		return $str;
	}
	
	protected function _date_time()
	{
		$this->common_attrs =  "name='".$this->field_name."' class='form-control datetimepicker ".($this->field_attrs['class'] ? $this->field_attrs['class'] : "")."' ".($this->field_attrs['style'] ? " style='".$this->field_attrs['style']."' " : "")." ".$this->field_attrs['attrs'];
		$this->common_attrs .= $this->field_attrs['disabled'] ? "disabled='disabled'" : "";
		$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= "<span class='form_input'>";
		$str .= '<div class="col-sm-12 col-md-3">
                     <input type="text"  id="'.$this->field_name.'" '.$this->common_attrs.' value="'.($this->prev_val && ($this->prev_val != '0000-00-00 00:00:00') ? dateFormat($this->prev_val,'d/m/Y G:i') : date('d/m/Y G:i')).'">
                 ';
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		$str .= "</div></span>";
		return $str;				
	}
	
	protected function _radio()
	{
		$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= "<span class='form_input'>";
		$str .='<div  class="btn-group btn-group-sm col-sm-12 col-md-10" data-toggle="buttons">';
		if(is_array($this->field_attrs['options']))
		{
			foreach($this->field_attrs['options'] as $option_id => $option)
			{
				$str .= "<label class='btn btn-default ".($option['value'] == $this->prev_val ? 'active' : '')."' for='".$option['value']."_".$option_id."'><input type='radio' value='".$option['value']."' id='".$option['value']."_".$option_id."'".$this->common_attrs." ".($option['value'] == $this->prev_val ? 'checked="checked"' : '')."/>".$option['label']."</label>";	
			}
		}
		
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		$str .="</div>";
		$str .= "</span>";
		return $str;						
	}
	
	protected function _checkbox()
	{
		$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= '<div class="col-md-4 col-sm-12">';
		$str .='<div  class="btn-group btn-group-sm btn-group-justified" data-toggle="buttons">';
		$str .= "<label for='".$this->field_name."_1' class='btn btn-success ".($this->prev_val ? "active" : "")."' ><input type='radio' value='1'  id='".$this->field_name."_1'".$this->common_attrs." ".($this->prev_val ? "checked='checked'" : "")."/>Si</label>";
		$str .= "<label for='".$this->field_name."_0' class='btn btn-danger ".(!$this->prev_val ? "active" : "")."'><input type='radio' value='0'  id='".$this->field_name."_0'".$this->common_attrs." ".(!$this->prev_val ? "checked='checked'" : "")."/>No</label>";
		$str .= "</div>";
		$str .= "</div><div class='clearfix'></div>";
		$str .= $this->description? "<div class='col-md-offset-2 col-md-10 col-sm-12 '><small>".$this->description."</small></div>" : "";
		return $str;			
	}
		
	protected function _textarea()
	{
		if($this->label_in_input)
		{
			$str .= "<div class='col-sm-12 col-md-12'>";
			$str .=	"<textarea placeholder='".$this->field_attrs['label']."'  id='".$this->field_name."' ".$this->common_attrs." >".$this->prev_val."</textarea>";
			$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
			$str .= "</div>";			
		}
		else
		{
			$str = "<label  class='col-md-2 col-sm-12 control-label' for='".$this->field_name."'>".$this->field_attrs['label']."</label>";
			$str .= "<div class='form_control col-md-10 col-sm-12'>";
			$str .=	"<textarea  id='".$this->field_name."' ".$this->common_attrs." >".$this->prev_val."</textarea>";
			$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
			$str .= "</div>";
		}
		if($this->field_attrs['class'] == 'summernote')
		{
			$str .= "<script>$('#".$this->field_name."').code('".$this->prev_val."')</script>";
		}
		return $str;						
	}

	protected function _file()
	{
		$str = "<label class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= "<div class='col-sm-12 col-md-10'>";
		if($this->prev_val)
		{
			$img = '<a href="'.$this->prev_val.'" target="_blank">'.$this->prev_val.'</a>';	
			$str .= "<div>".$img."<input type='file' id='".$this->field_name."' class='input_file' ".$this->common_attrs."/></div>";
			
		}
		else
		{
			$str .= "<div>".$img."<input type='file' id='".$this->field_name."' ".$this->common_attrs."/></div>";	
		}
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		$str .= "</div>";
							
		return $str;
	}
	
	protected function _video()
	{	
		$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= "<div class='col-sm-12 col-md-10'>";
		$str .= "<div class='comment'>Título del video</div>";
		$str .=	"<input type='text' id='".$this->field_name."_video_name' name='".$this->field_name."_video_name' value=\"".str_replace('"','\'',$this->prev_val['title'])."\"/>";
		$str .= "<div class='comment'>Video Url</div>";
		$str .=	"<input type='text' id='".$this->field_name."_video_code' name='".$this->field_name."_video_code' value=\"".str_replace('"','\'',$this->prev_val['code'])."\"/>";
		$str .= "</div>";
		return $str;						
	}
	
	protected function _text()
	{
		$val =  $this->prev_val ? str_replace('"','\'',$this->prev_val) : $this->field_attrs['value'];
		if($this->label_in_input)
		{
			$str .= "<div class='col-sm-12 col-md-10'>";	
			$str .=	"<input type='".$this->field_attrs['type']."' id='".$this->field_name."' ".$this->common_attrs." value=\"".$val."\"/ placeholder='".$this->field_attrs['label']."'>";
			$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
			$str .= "</div>";		
		}
		else
		{
			$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
			$str .= "<div class='col-sm-12 col-md-10'>";	
			$str .=	"<input type='".$this->field_attrs['type']."' id='".$this->field_name."' ".$this->common_attrs." value=\"".$val."\"/>";
			$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
			$str .= "</div>";
		}

		return $str;
	}
	
	protected function _select()
	{
		if(isset($this->field_attrs['source_table']))
		{
			$source_fields = "CONCAT (".implode(",' - ',",$this->field_attrs['source_fields']).")";
			$source_condition = ((isset($this->field_attrs['source_condition'])&&$this->field_attrs['source_condition']!='')?" AND ".$this->field_attrs['source_condition']." ":"");
			$sql = "SELECT ".$this->field_attrs['source_index_id'].", ".$source_fields." AS label FROM ".$this->field_attrs['source_table']." WHERE 1=1 {$source_condition} ORDER BY ".$this->field_attrs['source_fields'][0]." ASC";
			$res = $this->ci->db->query($sql)->result_array();
			foreach($res as $row)
			{
				$opt[] = array( 'value' => $row[$this->field_attrs['source_index_id']], 'label' =>  $row['label']);
			}
			$this->field_attrs['options'] = $opt;
		}
		if(!$this->label_in_input)
		{
			$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		}
		$str .= "<div class='col-sm-12 col-md-10'>";
		$str .="<select  id='".$this->field_name."' ".$this->common_attrs.">";
		
		$str .= $this->field_attrs['zero_option'] ? "<option value=''>".$this->field_attrs['zero_option']."</option>" : "";
		
		if(is_array($this->field_attrs['options']))
		{
			foreach($this->field_attrs['options'] as $option)
			{
				$str .= "<option value='".$option['value']."' ".($option['value'] == $this->prev_val ? 'selected' : '').">".($this->field_attrs['ucwords'] ? ucwords(strtolower($option['label'])) : $option['label'])."</option>";	
			}
		}
		$str .="</select>";
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		$str .= "</div>";
		return $str;		
	}

	/*
	Full example:
	'province_id' => array(	'label' => 'Provincia',
							'type' => 'select_address',
							'validation' => 'required',
							'visibility' => 'details|list'
							'with_departments' => true,
							//optional data.
							'source_table' => 'provinces',
							'source_index_id' => 'province_id',
							'source_fields' => array('name'),
							'department_db_table' => 'provinces_departments',
							'department_db_index' => 'department_id',
							'department_db_label' => 'name',
							'department_field_name' => 'department_id',
							'department_label' => 'Localidad',
							'ucwords' => false
							),		
	
	*/
	protected function _select_address()
	{
		$this->field_attrs['source_table'] = $this->field_attrs['source_table']? $this->field_attrs['source_table'] : "provinces";
		$this->field_attrs['source_index_id'] = $this->field_attrs['source_index_id']? $this->field_attrs['source_index_id'] : "province_id";
		$this->field_attrs['source_fields'] = $this->field_attrs['source_fields'] ? $this->field_attrs['source_fields'] : array("name");
		$this->field_attrs['ucwords'] = isset($this->field_attrs['ucwords']) ? $this->field_attrs['ucwords'] : true;
		$this->field_attrs['with_departments'] = isset($this->field_attrs['with_departments']) ? $this->field_attrs['with_departments'] : true;
		$this->field_attrs['zero_option'] = $this->field_attrs['zero_option'] ? $this->field_attrs['zero_option'] : "Seleccione provincia";
		
		$str = "<div id='".$this->field_name."_sub_box'>";	
		$str .= $this->_select();
		$str .= "</div>";
		if($this->field_attrs['with_departments'])
		{
			$this->field_attrs['department_db_table'] = $this->field_attrs['department_db_table'] ? $this->field_attrs['department_db_table'] : 'provinces_departments';
			$this->field_attrs['department_db_index'] = $this->field_attrs['department_db_index'] ? $this->field_attrs['department_db_index'] : 'department_id';
			$this->field_attrs['department_db_label'] = $this->field_attrs['department_db_label'] ? $this->field_attrs['department_db_label'] : 'name';
			$this->field_attrs['department_field_name'] = $this->field_attrs['department_field_name'] ? $this->field_attrs['department_field_name'] : 'department_id';
			$this->field_attrs['department_label'] = $this->field_attrs['department_label'] ? $this->field_attrs['department_label'] : 'Localidad';
			
			$str .= "<div id='".$this->field_attrs['department_field_name']."_sub_box'>";	
			$str .= "<label for='".$this->field_attrs['department_field_name']."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['department_label']."</label>";
			$str .= "<div class='col-sm-12 col-md-10'>";
			$str .="<select  id='".$this->field_attrs['department_field_name']."'   name='".$this->field_attrs['department_field_name']."' disabled='disabled'>";
			$str .= "<option value=''>seleccione provincia</option>";
			$str .= "</select>";
			$str .= "</div></div>";
			$str .= "<script type=\"text/javascript\">
						$(document).ready(function() {
						$('#".$this->field_name."').change(function(){
							
							var ajax_data = { province_id:	$(this).val(), ucwords:\"".$this->field_attrs['ucwords']."\", db_table: \"".$this->field_attrs['department_db_table']."\", db_index: \"".$this->field_attrs['department_db_index']."\",db_label: \"".$this->field_attrs['department_db_label']."\",province_index_name: \"".$this->field_attrs['source_index_id']."\" };
							$.ajax({
									url: \"".base_url()."common/departments\",
									type: \"POST\",
									data: ajax_data,
									cache: false,
									dataType: 'json',
						
									success: function (html) {
						
										if (html.success == true)
										{
											var sel_options;
											
											//$.each(html.departments, function(index, value){
											//	sel_options += \"<option value='\"+index+\"'>\"+value+\"</option>\";
											//});
											$('#".$this->field_attrs['department_field_name']."').html(html.departments_options);
											$('#".$this->field_attrs['department_field_name']."').removeAttr('disabled');
										}
									}
							});
						});
						});
					</script>";
			
		}
		return $str;		
	}


	
	protected function _multiselect()
	{
		$sql = "SELECT ".$this->field_attrs['source_index_id']." FROM ".$this->field_attrs['source_relation_table']." WHERE ".$this->ci->main_model->db_index." = ".$this->ci->main_model->get_id();	
		$result = $this->ci->db->query($sql)->result_array();
		
		foreach($result as $row)
		{
			$this->prev_vals[] = $row[$this->field_attrs['source_index_id']];	
		}
		
		if(isset($this->field_attrs['source_table']))
		{
			$source_fields = implode(",",$this->field_attrs['source_fields']);
			$source_condition = ((isset($this->field_attrs['source_condition'])&&$this->field_attrs['source_condition']!='')?" AND ".$this->field_attrs['source_condition']." ":"");
			$sql = "SELECT ".$this->field_attrs['source_index_id'].", {$source_fields} as label FROM ".$this->field_attrs['source_table']." WHERE 1=1 {$source_condition} ORDER BY ".$this->field_attrs['source_fields'][0]." ASC";
			$res = $this->ci->db->query($sql)->result_array();
			foreach($res as $row)
			{
				$opt[] = array( 'value' => $row[$this->field_attrs['source_index_id']], 'label' =>  $row['label']);
			}
			$this->field_attrs['options'] = $opt;
		}
		
		$str = "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= "<div class='col-sm-12 col-md-10'>";
		
		$str .="<select  id='".$this->field_name."' name='".$this->field_name."[]'  multiple='multiple' >";
		
		if(is_array($this->field_attrs['options']))
		{
			foreach($this->field_attrs['options'] as $option)
			{

				if(is_array($this->prev_vals) && in_array($option['value'], $this->prev_vals))
					$selected = "selected";
				else
					$selected = "";

				$str .= "<option value=".$option['value']." ".$selected.">".$option['label']."</option>";
			}
		}
		$str .= "</select>";
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		$str .= "</div>";
		$str .= '<script>$("#'.$this->field_name.'").multiSelect({
							selectableHeader: "<div class=\'custom-header\'><b>Lista completa</b></div>",
							selectionHeader: "<div class=\'custom-header\'><b>Items Seleccionados</b></div>"
							});</script>';
		return $str;		
	}

	protected function _image()
	{
		$str = "<label class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= "<div class='col-sm-12 col-md-10'>";
		if($this->prev_val)
		{
			$img = '<a href="'.$this->prev_val.'" target="_blank"><img src="'.thumb_image($this->prev_val).'" class="form_image"></a> <span fieldname="'.$this->field_name.'" class="modify_file">Cambiar '.$this->field_attrs['label'].'</span>';	
			$str .= "<div>".$img."<input type='file' id='".$this->field_name."' class='input_file' ".$this->common_attrs."/></div>";
			
		}
		else
		{
			$str .= "<div>".$img."<input type='file' id='".$this->field_name."' ".$this->common_attrs."/></div>";	
		}
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		$str .= "</div>";
							
		return $str;
	}

	protected function _tree_select()
	{
		$this->prev_vals = explode(",",$this->prev_val);
		$config_tree = array('table' => $this->field_attrs['tree_table'],'id' => $this->field_attrs['tree_index_id']);
		$this->ci->load->library('tree_json', $config_tree, 'categories');
		$categories = $this->ci->categories->_get_tree();
		if(count($categories))
		{
			$str = "<label>".$this->field_attrs['label']."</label>";
			$str .= "<ul class='tree_container'>";
			$prev_level = 1;
			$is_first = 0;
			$checked = "";
			foreach($categories as $k => $row)
			{
				if($row['category_id']==1)
					continue;
					
				if(in_array($row[$this->field_attrs['tree_index_id']], $this->prev_vals))
					$checked = "checked='checked'";
				else
					$checked = "";
		
				if( $prev_level == $row['level'] )
				{
					$str .= ($is_first)?'':'</li>';
					$str .= "<li class='level_".$row['level']."' >";	
					$str .= "<input type='checkbox' {$checked} name='".$this->field_name."[".$row[$this->field_attrs['tree_index_id']]."]' id='".$this->field_name."[".$row[$this->field_attrs['tree_index_id']]."]'>";
					$str .= "<label for='".$this->field_name."[".$row[$this->field_attrs['tree_index_id']]."]'>".$row['title']."</label>";
					$str .= "";
					
				}elseif( $row['level'] > $prev_level)
				{
					$str .= "<ul><li class='level_".$row['level']."' >";
					$str .= "<input type='checkbox' {$checked} name='".$this->field_name."[".$row[$this->field_attrs['tree_index_id']]."]' id='".$this->field_name."[".$row[$this->field_attrs['tree_index_id']]."]'>";
					$str .= "<label for='".$this->field_name."[".$row[$this->field_attrs['tree_index_id']]."]'>".$row['title']."</label>";
					$str .= "";
				}elseif( $row['level'] < $prev_level )
				{
					$str .= "</li></ul><li class='level_".$row['level']."' >";
					$str .= "<input type='checkbox' {$checked} name='".$this->field_name."[".$row[$this->field_attrs['tree_index_id']]."]' id='".$this->field_name."[".$row[$this->field_attrs['tree_index_id']]."]'>";
					$str .= "<label for='".$this->field_name."[".$row[$this->field_attrs['tree_index_id']]."]'>".$row['title']."</label>";
					$str .= "";
				}
				$is_first = 1;
				$prev_level = $row['level'];
			}
			if($prev_level>0)
			{
				for($i=0;$i<=$prev_level;$i++)
					$str .= "</li></ul>";
			}
			$str .= "</ul>";
		}
		else
		{
			$str .= "No hay ".$this->field_attrs['label'];
		}		
		return $str;
	}
	
	protected function _address()
	{
		$str = "<script> gmaps_init();autocomplete_init(); </script>";
		$str .= "<label for='".$this->field_name."' class='col-md-2 col-sm-12 control-label'>".$this->field_attrs['label']."</label>";
		$str .= "<div class='col-sm-12 col-md-10'>";	
		$str .=	"<input type='".$this->field_attrs['type']."' id='gmaps-input-address' ".$this->common_attrs." value=\"".str_replace('"','\'',$this->prev_val)."\"/>";
		$str .= $this->description? "<div class='clearfix'></div><small>".$this->description."</small>" : "";
		
		if(strstr($this->field_name,"]"))
		{
			$latitude = str_replace("]","_latitude]",$this->field_name);
			$longitude = str_replace("]","_longitude]",$this->field_name);		
		}
		else
		{
			$latitude = $this->field_name."_latitude";
			$longitude = $this->field_name."_longitude";
		}
		$str .= "<input type='hidden' id='gmaps-output-latitude' name='".$latitude."' value=''>
				<input type='hidden' id='gmaps-output-longitude' name='".$longitude."' value=''>
				<div id='gmaps-error'></div>
				<div id='gmaps-canvas'></div>";
		$str .= "</div>";
		$str .= "<script>if($('#gmaps-input-address').val()){geocode_lookup( 'address', $('#gmaps-input-address').val(), true );}</script>";
		return $str;				
	}
}

?>