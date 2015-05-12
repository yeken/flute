<?
function this_url()
{
    $_SERVER['PHP_SELF'];
}

function project_code($id,$name)
{
	return substr(base64_encode(floatval(($id*$id*$id)/9).$name[0].($id%2)." 666 *//".($id%2).($id%3)."--regále: ".strlen($name).($id*$id/9)),0,6);	
}


function admin_url()
{
    return base_url()."admin/";
}

function base_dir()
{
    return dirname(__FILE__)."/../../";
}

function views_dir()
{
    return dirname(__FILE__)."/../views/";
}

function thumb_image($file)
{	
	$parts = explode('.',$file);	
	$total_parts = count($parts);
	$parts[$total_parts - 2] = $parts[$total_parts - 2]."_thumb";
	return implode(".",$parts);		
}

function valid_url($url)
{
	if (@file_get_contents($url, 0, NULL, 0, 1)) {
		return 1;
	}
	return 0;   
}

function vd($str)
{
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}

function default_front_image_url($name = '')
{
	return base_url()."assets_fe/images/defaults/".$name;
}

function hola($str = "HOLA")
{
	vd($str); 
	die();
}

function load_scripts($scripts = array())
{
	if(is_array($scripts["js"]))
	{
		foreach($scripts["js"] AS $script)
		{
			$str .= "<script type=\"text/javascript\" src=\"".$script."\" ></script>";
		}	
	}
	
	if(is_array($scripts["css"]))
	{
		foreach($css as $stylesheet)
		{
			$str .= '<link href="'.$stylesheet.'" rel="stylesheet" type="text/css">';
		}			
	}
	return $str;
}

function preview($str, $len)
{
	return strip_tags(substr($str,0,$len));
}

function encode_url($url = "")
{
    $url = $url ? $url : uri_string(); // by default use the uri_string() function provided by the url helper, so it can be used in conjunction with

    return urlencode($url);
}

function decode_url($url)
{
    return urldecode($url);
}

function get_text_between_tags($start_point, $end_point,$source)
{
		$source = " ".$source;
		$ini = strpos($source,$start_point);
		if ($ini == 0) return "";
		$ini += strlen($start_point);  
		$len = strpos($source,$end_point,$ini) - $ini;
		return substr($source,$ini,$len);
}


function replace_text_between_tags($start_point, $end_point, $new_text, $source, $remove_tags = false) 
{
    $str = preg_replace('#('.preg_quote($start_point).')(.*)('.preg_quote($end_point).')#si', '$1'.$new_text.'$3', $source);

	if($remove_tags)
	{
		$str = str_replace(array($start_point, $end_point),"",$str);	
	}
	return $str;
}

function form_field_error($field)
{
    if(form_error($field))
    {
        $str = strip_tags(form_error($field));
        return "<p class='error'>".$str."</p>";
    }
}

/*
 * $monthsage parameter should be delivered as: $monthsageName => array('messageType' => array("message 1","message 2"))
 * or $monthsageName => array('messageType' => "message 1","message 2");
 *
 * messageType values: error, warning, success
 *
 * Otherwise you can send the type as second parameter
 */
function get_messages($monthsages, $type = "")
{
    if($type != "")
    {
        $msgs[$type] = $monthsages;
    }
    else
    {
       $msgs = $monthsages;
    }

    if(is_array($msgs))
    {
        foreach($msgs as $monthsageType => $monthsageList)
        {
            $str = "<div class='message ".strtolower($monthsageType)."Message'>";
            $str .= "<ul>";
            if(is_array($monthsageList))
            {
                foreach($monthsageList as $msg)
                {
                    $str .= "<li>".$msg."</li>";
                }
            }
            else
            {
                $str .= "<li>".$monthsageList."</li>";
            }
            $str .= "</ul></div>";
        }
        return $str;
    }
}

function show_messages($monthsages)
{
    echo get_messages($monthsages);
}


function dateFormat($date, $format = null, $offset = NULL)
{
    if(strtotime($date)==-62169968592) return "-";
    $format = $format? $format : "Y/m/d";
    $time = strtotime($date) + $offset;
    return date($format, $time);
}

function dateDiff ($d1, $d2) {
// Return the number of days between the two dates:

  return round((strtotime($d1)-strtotime($d2))/86400);

}

function hourDiff($d1,$d2)
{
  return round((strtotime($d1)-strtotime($d2))/3600);	
}

function minDiff($d1,$d2)
{
  return round((strtotime($d1)-strtotime($d2))/60);	
}

function month($month, $lang = "")
{
	if(!$lang)
	{
		$lang = get_cookie('lang') == 'en' ? 'en' : 'es';
	}
	$months['en'] = array("january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
	$months['es'] = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"); 	
	
	return $months[$lang][$month -1];
}

function resizeMarkup($markup, $dimensions)
{
	$w = $dimensions['width'];
	$h = $dimensions['height'];
	 
	$patterns = array();
	$replacements = array();
	if( !empty($w) )
	{
		$patterns[] = '/width="([0-9]+)"/';
		$patterns[] = '/width:([0-9]+)/';
		 
		$replacements[] = 'width="'.$w.'"';
		$replacements[] = 'width:'.$w;
	}
	 
	if( !empty($h) )
	{
		$patterns[] = '/height="([0-9]+)"/';
		$patterns[] = '/height:([0-9]+)/';
		 
		$replacements[] = 'height="'.$h.'"';
		$replacements[] = 'height:'.$h;
	}
	 
	return preg_replace($patterns, $replacements, $markup); 
}

function get_file_icon($ext)
{

	if($ext=='')
		return false;

	switch($ext)
	{
		case 'png':
		case 'jpg':
		case 'gif':
			$ext = 'image';
			break;
		case 'swf':
		case 'flv':
		case 'mpeg':
			$ext = 'video';
			break;
		case 'rar':
		case 'zip':
			$ext = 'zip';
			break;
		case 'pdf':
			$ext = 'pdf';
			break;
		case 'doc':
		case 'docx':
			$ext = 'doc';
			break;
		case 'xls':
		case 'xlsx':
			$ext = 'xls';
			break;
		case 'ppt':
		case 'pptx':
			$ext = 'ppt';
			break;
		default:
			$ext = 'zip';
			break;
	}
	
	return site_url('assets_fe/images/common/fileicons/'.$ext.'.gif');

}


function strtolower_es($string)
{
	$low=array("Á" => "á", "É" => "é", "Í" => "í", "Ó" => "ó", "Ú" => "ú", "Ü" => "ü", "Ñ" => "ñ");
	return strtolower(strtr($string,$low));
}



function is_field_visible($field_visibility = "", $section)
{
	if(!$field_visibility)
	{
		return true;
	}
	
	$visibilities = explode("|",$field_visibility);
	return in_array($section, $visibilities);	
}



function selectDates($date, $input_name = "", $class_name = "", $y_offset_up = 10, $y_offset_down = 25, $with_hours = false, $usa_mode = false)
{
    list($date_ymd, $date_hms) = explode(" ", $date);
    list($year, $month, $day) = explode("-", $date_ymd);
    list($hour, $min, $seg) = explode(":", $date_hms);
    $input_name = $input_name ? $input_name : "date";
    $top_range = (int)date('Y') + $y_offset_up;
    $bottom_range = (int)date('Y') - $y_offset_down;
    // Day
    $dayStr = "<select name=\"".$input_name."_dia\"  id=\"".$input_name."_dia\" class=\"". $class_name ."\"  onChange=\"setDays('".$input_name."');\">\n";
    for($i = 1; $i <= 31 ;$i++)
    {
        $i = sprintf("%02d", $i);
        $dayStr .= "<option value=\"". $i ."\" ". ($day==$i? "selected" : "") .">". $i ."</option>\n";
    }
    $dayStr .= "</select>";

    // Month
    $monthStr = "<select name=\"".$input_name."_mes\" id=\"".$input_name."_mes\" class=\"". $class_name ."\"  onChange=\"setDays('".$input_name."');\">\n";
    for($i = 1; $i <= 12; $i++)
    {
        $i = sprintf("%02d", $i);
        $monthStr .= "<option value=\"". $i ."\" ". ($month==$i? "selected" : "") .">". $i ."</option>\n";
    }
    $monthStr .= "</select>";

    // Year
    $yearStr = "<select name=\"".$input_name."_anio\" id=\"".$input_name."_anio\" class=\"". $class_name ."\"  onChange=\"setDays('".$input_name."');\">\n";
	for( $i=$top_range;$i>$bottom_range;$i--)
    {
        $yearStr .= "<option value=\"". $i ."\" ". ((int)$year==$i? "selected" : "") .">". $i ."</option>\n";
    }
    $yearStr .= "</select>";

    if($usa_mode)
    {
        echo $monthStr;
        echo $dayStr;
        echo $yearStr;
    }
    else
    {
        echo $dayStr;
        echo $monthStr;
        echo $yearStr;
    }

    echo "<input type='hidden' name='".$input_name."' id='".$input_name."' value='".$date."'><script>setDays('".$input_name."');</script>";
  
    if($with_hours)
    {
        echo "Hr. <select name=\"".$input_name."_hora\" id=\"".$input_name."_hora\" class=\"". $class_name ."\"  onChange=\"setDays('".$input_name."');\">\n";
        for( $i=0;$i < 24;$i++)
        {
            $i = sprintf("%02d", $i);
            echo "<option value=\"". $i ."\" ". ((int)$hour==$i? "selected" : "") .">". $i ."</option>\n";
        }
        echo "</select>";

        echo "<select name=\"".$input_name."_min\" id=\"".$input_name."_min\" class=\"". $class_name ."\"  onChange=\"setDays('".$input_name."');\">\n";
        for( $i=0;$i < 60;$i++)
        {
            $i = sprintf("%02d", $i);
            echo "<option value=\"". $i ."\" ". ((int)$min==$i? "selected" : "") .">". $i ."</option>\n";
        }
        echo "</select>";

        echo "<select name=\"".$input_name."_seg\" id=\"".$input_name."_seg\" class=\"". $class_name ."\"  onChange=\"setDays('".$input_name."');\">\n";
        for( $i=0;$i < 60;$i++)
        {
            $i = sprintf("%02d", $i);
            echo "<option value=\"". $i ."\" ". ((int)$seg==$i? "selected" : "") .">". $i ."</option>\n";
        }
        echo "</select>";
    }
}

function isActive($pageID,$linkID){
    if($pageID == $linkID){
        return "active";
    }
}


function showDattatableFilter($field_id, $field_attrs)
{
	$str = '';
	switch($field_attrs['filter'])
	{
		case 'date_range_filter':
									$str = "<div class='datatable_filter'>
											<label class=' col-md-2 col-sm-3 pull-left'>".$field_attrs['label']."</label>
											<div class='input-group col-md-3 col-sm-4 pull-left'>
												<input placeholder='desde' id='min_filter_".$field_id."' name='min_filter_".$field_id."' class='form-control ' >
													<span class='input-group-addon' id='clear_min_filter_".$field_id."' style='cursor:pointer'>
														<i class='glyphicon glyphicon-remove'></i>
													</span>
												</div> 
											<div class='input-group col-md-3 col-sm-4 pull-left'>
												<input placeholder='hasta' id='max_filter_".$field_id."' name='max_filter_".$field_id."' class='form-control' >
													<span class='input-group-addon' id='clear_max_filter_".$field_id."' style='cursor:pointer'>
														<i class='glyphicon glyphicon-remove'></i>
													</span>
												</div>
											<div class='col-md-1 col-sm-1 pull-left'>
												<span id='submit_filter_".$field_id."' class='btn btn-default'>Filtrar</span>
											</div>
											<div class='clearfix'></div>
											</div>";	
			break;
		default: break; 
	}
	return $str;
}
function encrypt($arr)
{
	if(!is_array($arr)) return;
	$str = count($arr).implode('f!rm56.-s',$arr).implode('-',$arr);
	return substr(base64_encode($str),0,8);	
}

function fb_cmp($a, $b) 
{
	if ($a->name == $b->name) 
	{
		return 0;
	}
	return ($a->name[0] < $b->name[0]) ? -1 : 1;
}

function get_bet_percentage($bets)
{
	$out = array();
	foreach($bets as $bet_id => $bet)
	{
		$odds = explode("/",$bet);
		$total = $odds[0] + ($odds[1] ? $odds[1] : 1);
		$out[$bet_id] = 100 - round($odds[0]*100/$total,2);	
	}
	return $out;
}

function get_match_code($match_id, $match_ended)
{
	$match_id2 = (int)$match_id*8;
	return  $match_id2.(int)$match_ended."-".substr(base64_encode($match_id."/".(int)$match_ended."-".(float)$match_ended."-".$match_ended."/elprodedelagente"),0,6);	
}

function get_date($date)
{
	$CI =& get_instance();
	
	$lang = $CI->session->userdata('lang') ? $CI->session->userdata('lang') : "AR";
	
	
	$dias['AR'] = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$meses['AR'] = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$dias['MX'] = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$meses['MX'] = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$dias['US'] = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Sunday");
	$meses['US'] = array("January","February","March","April","May","June","July","August","September","October","November","December");

	$dias['PR'] = array("Domingo","Segunda-feira","Terça-Feira","Quarta-Feira","Quinta-Feira","Sexta-Feira","Sábado");
	$meses['PR'] = array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
	
	switch($lang)
	{
		case 'MX':
		case 'AR':
					$date = $dias[$lang][dateFormat($date,'w')]." ".dateFormat($date,'d')." de ".$meses[$lang][dateFormat($date,'n')-1]. " del ".dateFormat($date,'Y') ;
					break;
		case 'PR':
					$date = $dias[$lang][dateFormat($date,'w')]." ".dateFormat($date,'d')." de ".$meses[$lang][dateFormat($date,'n')-1]. " de ".dateFormat($date,'Y') ;	
					break;
		case 'US':
					$date = $dias[$lang][dateFormat($date,'w')]." ".dateFormat($date,'d').", ".$meses[$lang][dateFormat($date,'n')-1]. " ".dateFormat($date,'Y') ;
	
	}
	return $date;
}

function var_lang($word, $rep)
{
	
	return str_replace("$1",$rep,lang($word));
}


function lang($word)
{
	$CI =& get_instance();
	
	$lang = $CI->session->userdata('lang') ? $CI->session->userdata('lang') : "AR";
	$link_url = $CI->data['link_url'];
	$company_name = $CI->company_model->name;
	$words =  $CI->config->item('words');
	$word = $words[$word][$lang] ? $words[$word][$lang] : $word; 
	$word = str_replace("[[link_url]]",$link_url,$word);
	$word = str_replace("[[company_name]]",$company_name,$word);
	return $word;
}

?>