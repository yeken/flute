<?
require_once(dirname(__FILE__)."/../simple_data_model.php");

class File_model extends Simple_data_model
{	

    public $db_index = 'file_id|id';
    public $db_table = 'files';
	
	public $file_path = '';
	public $file_url = '';
	
	public $thumb_name;
	
    protected $db_fields = array(
								'file|char',
								'file_name|char',
                                "type|enum('image','video','archive')",
								'code|char',
                                'ext|smallchar',
								'group|char',
                                'date_created|datetime');	

	public function __construct()
	{
		parent::__construct();
		$this->file_path =  base_dir().'uploads/files/';
		$this->file_url = base_url().'uploads/files/';
	}
	
	public function post_get()
	{
		$this->get_thumb_name();
	}
	
	public function get_thumb_name()
	{
		if($this->thumb_name)
		{
			return $this->thumb_name; 		
		}
		$this->thumb_name = thumb_image($this->file);
	}
	
	public function post_create()
	{
		$this->file = $this->get_id()."_".str_replace(" ","_",$this->file);	
		$this->update(); 	
	}
	
	public function delete($id = NULL)
	{
		if(!$this->get_id())
		{
			$this->get($id);
		}
		if(file_exists($this->get_full_path()) && unlink($this->get_full_path()))
		{
			return parent::delete($id);
		}	
	}
	
	public function get_full_path($id = "", $file = "")
	{
		return 	$this->file_path.$this->get_system_file_name($id, $file);
	}
	
	public function get_url($id = "", $file = "")
	{
		if($this->type == 'video')
		{
			return $this->code;
		}
		
		return 	$this->file_url.$this->get_system_file_name($id, $file);
	}
	
	public function get_thumb_url($id = "", $file = "")
	{
		if($this->type == 'video')
		{
			return $this->code;
		}
		
		$this->get_thumb_name();
		
		return 	$this->file_url.$this->thumb_name;	
		
	}	
	
	public function get_system_file_name($id = "", $file = "")
	{
		return $file ? $file : $this->file;	
	}	
	
	public function get_thumb($width = 0, $height = 0)
	{
		
		switch($this->type)
		{
			case 'image': 	
			
						$height = $height? $height : 77;
						$str = '<img src="'.$this->get_thumb_url().'" '.($width ? 'width="'.$width.'px"' : '').' alt="'.$this->file_name.'" style="max-height:'.$height.'px;" title="'.$this->file_name.'">';
						break;					
			case 'video':			
						$height = $height? $height : 150;
						$width = $width? $width : 250;
						$str = '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$this->code.'" frameborder="0" allowfullscreen></iframe>';
						break;
			
			default:	
						$height = $height? $height : 77;
						$str= '<img src="'.$this->get_icon().'" title="'.$this->file.'" alt="'.$this->file.'">';
						break;
		}	
		return $str;
	}
	
	public function get_icon()
	{
	}
	
	public function get_clickable_thumb($width = 0, $height = 0)
	{
		$str = $this->get_thumb($width, $height);	
		$str = "<a href='".$this->get_url()."'>".$str."</a>";
		return $str;
	}
	
	
	public function show_thumb($width = 0, $height = 0)
	{
		echo $this->get_thumb($width, $height);	
	}

	public function duplicate()
	{
		if(!$this->get_id()) return 0;	
		
		$prev_path = $this->get_full_path();
		$prev_thumb_path = $this->file_path.$this->get_id()."_".$this->file_name."_thumb.".$this->ext;
		
		$this->remove_id();		
		
		$this->create();
		if($this->type == "image")
		{
			$this->file = $this->get_id()."_".$this->file_name.".".$this->ext;
			$thumb_name = $this->get_id()."_".$this->file_name."_thumb.".$this->ext;
			$this->update();
			if(file_exists($prev_path))
				copy($prev_path, $this->file_path.$this->file);
			if(file_exists($prev_thumb_path))
				copy($prev_thumb_path, $this->file_path.$thumb_name);
		}
		return 1;
	}

}

?>