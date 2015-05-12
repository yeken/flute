<?

class Datatables
{
	var $ci;
	var $imported;
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
	public function show_list()
	{
		$this->ci->data['ao_columns'] = "";
		
		$i = 1;
		foreach($this->ci->data['page_fields']['list'] as $field => $attr)
		{
			$extra = '';
			if(($attr['list_visibility'] == 'expanded') || ($attr['list_visibility'] == 'hidden'))
			{
				$extra = '"bSearchable": false, "bVisible": false';
			}			
			
			if($attr['list_visibility'] == 'expanded')
			{
				$this->ci->data['table_row_details'][] = array('label' => $attr['label'], 'ao_index' => $i);
			}
			
			
			if(!isset( $attr['ao_columns'] ) )
			{
				
				switch($attr['type'])
				{
					case 'checkbox':
					case 'radio':
									$this->ci->data['ao_columns'] .= "{'sClass' : 'checkbox_row' ".($extra ? ",".$extra : "")."},";
									break;
					case 'text':
									switch($attr['class'])
									{
										case 'coin':
													$this->ci->data['ao_columns'] .= "{'sClass' : 'coin' ".($extra ? ",".$extra : "")."},";
													break;
										case 'number':
													$this->ci->data['ao_columns'] .= "{'sClass' : 'number' ".($extra ? ",".$extra : "")."},";
													break;
										case 'email':
													$this->ci->data['ao_columns'] .= "{'sClass' : 'email' ".($extra ? ",".$extra : "")."},";
													break;			
														
										default:	$this->ci->data['ao_columns'] .= $extra ? "{".$extra."}," : "null,";
													break;
									}
									break;
					
					case 'date_time':
									$this->ci->data['ao_columns'] .= "{'sClass' : 'date_time', type: 'date-range' ".($extra ? ",".$extra : "")." },";
									break;
					case 'date':
									$this->ci->data['ao_columns'] .= "{'sClass' : 'date', type: 'date-range' ".($extra ? ",".$extra : "")." },";
									break;	
				
					default:		$this->ci->data['ao_columns'] .= $extra ? "{".$extra."}," : "null,";
									break; 
				}

			}
			else
			{
				
				$this->ci->data['ao_columns'] .= "{";
				
				foreach($attr['ao_columns'] as $option => $value)
				{
					$this->ci->data['ao_columns'] .= "'".$option."' : '".$value."',";
				}

				$this->ci->data['ao_columns'] = substr($this->ci->data['ao_columns'],0,-1);

				$this->ci->data['ao_columns'] .= ($extra ? ",".$extra : "")."},";
			}
		
			$i++;
		}
		
		if(is_array($this->ci->datatable_actions))
		{
			$this->ci->data['ao_columns'] .= '{"sClass" : "actions", "bSortable" : false},';
		}
		
		if(is_array($this->ci->data['table_row_details']))
		{
			$this->ci->data['ao_columns'] = '{"sClass" : "col_expand_details", "bSortable" : false},'.$this->ci->data['ao_columns'];
		}
		
		$this->ci->data['ao_columns'] = substr($this->ci->data['ao_columns'],0,-1);

		$this->ci->load->view('admin/common/inc.datatable.php', $this->ci->data);
	}

	public function export_query()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */

		$hide_db_index = false;

		$sTable = $this->ci->data['datatable_db_table'] ? $this->ci->data['datatable_db_table'] : $this->ci->main_model->db_table;
		
		foreach($this->ci->data['page_fields']['list'] as $field => $attr)
		{
			$aColumns[] = $attr['table'] ? $attr['table'].".".$field : $field ;
		}
		
		if(!in_array($this->ci->main_model->db_index, $aColumns))
		{
			$dbIndex = $this->ci->main_model->db_index;
			$queryIndex = ($this->ci->add_db_table_to_index ? $this->ci->main_model->db_table."." : '').$this->ci->main_model->db_index;
			//$aColumns[] = $queryIndex;
			$hide_db_index = true;
		}
		else
		{
			$queryIndex = $this->ci->main_model->db_table.".".$this->ci->main_model->db_index;
		}
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = $queryIndex;
		
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
						".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$iniCondition = $this->ci->ini_condition ? $this->ci->ini_condition : 1; 
	
		$sWhere = "WHERE ".$iniCondition;

		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			$sWhere .= " AND (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
			$sWhere .= substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE  ".$iniCondition." AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}		
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   $sTable
			$sWhere
			$sOrder
		";
		// $rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$mainResult = $this->ci->db->query($sQuery);
		
		return $mainResult;
	}
	
	public function ajax_datatable()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */

		$hide_db_index = false;

		$sTable = $this->ci->data['datatable_db_table'] ? $this->ci->data['datatable_db_table'] : $this->ci->main_model->db_table;
		
		foreach($this->ci->data['page_fields']['list'] as $field => $attr)
		{
			$aColumns[] = $attr['table'] ? $attr['table'].".".$field : $field ;
		}
		
		if(!in_array($this->ci->main_model->db_index, $aColumns))
		{
			$dbIndex = $this->ci->main_model->db_index;
			$queryIndex = ($this->ci->add_db_table_to_index ? $this->ci->main_model->db_table."." : '').$this->ci->main_model->db_index;
			$aColumns[] = $queryIndex;
			$hide_db_index = true;
		}
		else
		{
			$queryIndex = $this->ci->main_model->db_table.".".$this->ci->main_model->db_index;
		}
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = $queryIndex;
		
		/* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
				mysql_real_escape_string( $_GET['iDisplayLength'] );
		}

		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					
					$sOrder .= $aColumns[  $this->ci->data['datatable_details'] ? intval( $_GET['iSortCol_'.$i]) - 1 : intval( $_GET['iSortCol_'.$i])]."
						".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$iniCondition = $this->ci->ini_condition ? $this->ci->ini_condition : 1; 
		$sWhere = "WHERE ".$iniCondition;
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			$sWhere .= " AND (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE  ".$iniCondition." AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		/* Custom filtering */
		foreach($this->ci->data['page_fields']['list'] as $field => $attr)
		{
			switch($attr['filter'])
			{
				case 'date_range_filter':
							
							if($_GET['min_filter_'.$field])
							{
								if($attr['type'] == 'date_time')
								{
									$_GET['min_filter_'.$field] = dateFormat(str_replace("/",".",$_GET['min_filter_'.$field]), 'Y-m-d H:i:s');
								}
								$sWhere .= " AND TIMESTAMPDIFF(SECOND, ".($attr['table'] ? $attr['table'].".".$field : $field ).",'".$_GET['min_filter_'.$field]."') < 0 ";
							}
							if($_GET['max_filter_'.$field])
							{
								if($attr['type'] == 'date_time')
								{
									$_GET['max_filter_'.$field] = dateFormat(str_replace("/",".",$_GET['max_filter_'.$field]), 'Y-m-d H:i:s');
								}
								$sWhere .= " AND TIMESTAMPDIFF(SECOND, ".($attr['table'] ? $attr['table'].".".$field : $field ).",'".$_GET['max_filter_'.$field]."') > 0";
							}
							break;
							
				default: break;
			}
			
		}
		
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   $sTable
			$sWhere
			$sOrder
			$sLimit
		";
		// $rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());+
		
		$mainResult = $this->ci->db->query($sQuery);
		
		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS()
		";
		$aResultFilterTotal = $this->ci->db->query($sQuery)->result_array();
		$iFilteredTotal = $aResultFilterTotal[0]['FOUND_ROWS()'];

		/* Total data set length */
		$sQuery = "
			SELECT COUNT(".$sIndexColumn.")
			FROM   $sTable
		";
		$aResultTotal = $this->ci->db->query($sQuery)->result_array();
		$iTotal = $aResultTotal[0]['COUNT('.$sIndexColumn.')'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		$icons = $this->ci->config->item('general');
		$icons = $icons['admin']['table_icons'];
		
		foreach ( $mainResult->result_array() as $aRow )
		{
			$row = array();
			
			if($this->ci->data['datatable_details'])
			{
				$row[] = '<img src="'.base_url().'/assets_be/images/details_open.png" index="'.$aRow[$dbIndex].'">';
				
			}
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				/* Special output formatting column */
				if ( $aColumns[$i] == $queryIndex)
				{
					$actions_col = "";
					foreach($this->ci->datatable_actions as $action => $attrs)
					{
						if(isset($attrs['dialog']))
						{
							$href = 'if(confirm("'.$attrs['dialog'].'")){ window.location.href="'.$attrs["url"].$aRow[$dbIndex].'"; return true; }else{ return false; }';
							$actions_col.= "<a onclick='{$href}' class='ajax-links tooltip-link' title='".$attrs['label']."'><span class='".$icons[$action]."'></span></a> ";
						}else{
							$actions_col.= "<a href='".$attrs['url'].$aRow[$dbIndex]."' class='ajax-links tooltip-link' title='".$attrs['label']."'><span class='".$icons[$action]."'></span></a> ";
						}
					}
					
					if(!$hide_db_index)
					{
						$row[] = $aRow[ $aColumns[$i]];	
					}
				}
				else if ( $aColumns[$i] != ' ' )
				{
					$field = explode(".",$aColumns[$i]);
					$field = $field[1] ? $field[1] : $field[0];
					/* General output */
					switch($this->ci->data['page_fields']['list'][$aColumns[$i]]['type'])
					{
						case 'radio':
								foreach($this->ci->data['page_fields']['list'][$aColumns[$i]]['options'] as $e => $opt)
								{
									if(array_key_exists('value', $opt) && $opt['value'] == $aRow[$field])
									{
										$row[] = $opt['label'];
									}
								}
								break;
						
						case 'checkbox':
								$row[] = (int)$aRow[$field] ? '<span class="glyphicon glyphicon-ok-sign glyph-ok" title="SI"></span>' : '<span class="glyphicon glyphicon-remove-sign glyph-no" title="NO"></span>'; 
									
								//$row[] = '<img src="'.base_url().'/assets_be/images/'.(int)$aRow[$field].'.png">';
								break;
								
						case 'date_time':
								$row[] = dateFormat($aRow[$field],"d/m/Y H:i");
								break;
						case 'date':	
								$row[] = dateFormat($aRow[$field],"d/m/Y");
								break;
					
						case 'serialized_array':
								$separator = $this->ci->data['page_fields']['list'][$aColumns[$i]]['separator'];
								$join = $this->ci->data['page_fields']['list'][$aColumns[$i]]['join'];
								$deserialized = unserialize($aRow[$field]) ?unserialize($aRow[$field]) : array() ;
								$deserialized = implode(($separator ? $separator: ", "),$deserialized);			
								$row[] = $deserialized;
								break;
						default: 
								$row[] = $aRow[$field]; 
								break;
					}
				}
			}
			$row[] = $actions_col;
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );
		
	}
	
	public function generate($table, $columns, $index, $joins, $where, $search)
	{
	  $sLimit = $this->get_paging();
	  $sOrder = $this->get_ordering($columns);
	  $sWhere = $this->get_filtering($columns, $where, $search);
	  $rResult = $this->get_display_data($table, $columns, $sWhere, $sOrder, $sLimit, $joins, $where);
	  $rResultFilterTotal = $this->get_data_set_length();
	  $aResultFilterTotal = $rResultFilterTotal->result_array();
	  $iFilteredTotal = $aResultFilterTotal[0]["FOUND_ROWS()"];
	  $rResultTotal = $this->get_total_data_set_length($table, $index, $sWhere, $joins, $where);
	  $aResultTotal = $rResultTotal->result_array();
	  $iTotal = $aResultTotal[0]["COUNT($index)"];
	  return $this->produce_output($columns, $iTotal, $iFilteredTotal, $rResult);
	  
	}
	
	protected function get_paging()
	{
	if($this->ci->input->get("iDisplayStart") && $this->ci->input->get("iDisplayLength") !== "-1")
		  $sLimit = "LIMIT " . $this->ci->input->get("iDisplayStart"). ", " .$this->ci->input->get("iDisplayLength");
		  else
		  {
			 $iDisplayLength = $this->ci->input->get("iDisplayLength");
			 if(empty($iDisplayLength)){
				  $sLimit = "LIMIT " . "0,10";
			 }else{
			
				$sLimit = "LIMIT " . "0,". $this->ci->input->get("iDisplayLength");
			 }
			 
		  }
	
		return $sLimit; 
	}
	
	protected function get_ordering($columns)
	{
	  $sOrder = "";
	
	  if($this->ci->input->get("iSortCol_0"))
	  {
		$sOrder = "ORDER BY ";
	
		for($i = 0; $i < intval($this->ci->input->get("iSortingCols")); $i++)
		  $sOrder .= $columns[intval($this->ci->input->get("iSortCol_" . $i))] . " " . $this->ci->input->get("sSortDir_" . $i) . ", ";
	
		$sOrder = substr_replace($sOrder, "", -2);
	  }
	
	  return $sOrder;
	}
	
	protected function get_filtering($columns, $where, $search)
	{
		$sWhere = "";

		if($this->ci->input->get("sSearch") != '')
		{
			$sWhere = "WHERE 1 AND ";
			for($i = 0; $i < count($columns); $i++)
			  $sWhere .= $columns[$i] . " LIKE '%" . $this->ci->input->get("sSearch") . "%' OR ";

			$sWhere = substr_replace($sWhere, "", -3);
		}

		if($sWhere == '')
		{
			if($where !== '')
			{
				$where = 'WHERE 1 ' . $where;
			}
		}
		
		return $sWhere . $where;
	} 
	
	protected function get_display_data($table, $columns, $sWhere, $sOrder, $sLimit, $joins, $where)
	{
	  return $this->ci->db->query
	  ("
		SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
		FROM $table
		$joins
		$sWhere
		$sOrder
		$sLimit
	  ");
	}
	
	protected function get_data_set_length()
	{
	  return $this->ci->db->query("SELECT FOUND_ROWS()");
	}
	
	protected function get_total_data_set_length($table, $index, $sWhere, $joins, $where)
	{
	  return $this->ci->db->query
	  ("
		SELECT COUNT(" . $index . ")
		FROM $table
		$joins
		$sWhere
		
	  ");
	}
	
	protected function produce_output($columns, $iTotal, $iFilteredTotal, $rResult)
	{
	  $aaData = array();
	
	  foreach($rResult->result_array() as $row_key => $row_val)
	  {
		foreach($row_val as $col_key => $col_val)
		{
			switch( $this->ci->data['page_fields']['list'][ $col_key ]['type'] )
			{
						case 'checkbox':
								$aaData[$row_key][] = '<img src="'.base_url().'/assets_be/images/'.(int)$col_val.'.png">';
								break;
								
						case 'date_time':
								$aaData[$row_key][] = dateFormat($col_val,"d/m/Y H:i");
								break;
						case 'date':	
								$aaData[$row_key][] = dateFormat($col_val,"d/m/Y");
								break;
					
						default: 
								$aaData[$row_key][] = $col_val;
								break;
			}
		
		}
	  }
		/* Special output formatting column */
		if ( $aColumns[$i] == $this->ci->main_model->db_index)
		{
			$actions_col = "";
			foreach($this->ci->datatable_actions as $action => $attrs)
			{
				$actions_col.= "<a href='".$attrs['url'].$aRow[$aColumns[$i]]."' class='ajax-links'>".$attrs['label']."</a> ";
			}
			
			if(!$hide_db_index)
			{
				$row[] = $aRow[ $aColumns[$i]];	
			}
		}
		else if ( $aColumns[$i] != ' ' )
		{
			/* General output */
			switch($this->ci->data['page_fields']['list'][$aColumns[$i]]['type'])
			{
				case 'checkbox':
						$row[] = '<img src="'.base_url().'/assets_be/images/'.(int)$aRow[$aColumns[$i]].'.png">';
						break;
						
				case 'date_time':
						$row[] = dateFormat($aRow[$aColumns[$i]],"d/m/Y H:i");
						break;
				case 'date':	
						$row[] = dateFormat($aRow[$aColumns[$i]],"d/m/Y");
						break;
			
				default: 
						$row[] = $aRow[ $aColumns[$i] ]; 
						break;
			}
		}
	  $sOutput = array
	  (
		"sEcho"                => intval($this->ci->input->get("sEcho")),
		"iTotalRecords"        => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData"               => $aaData
	  );
	
	  return json_encode($sOutput);
	}
}