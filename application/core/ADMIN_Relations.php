<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__)."/ADMIN_Categories.php");

class ADMIN_Relations extends ADMIN_Categories {

	public function relation_ajax_datatable($model, $id)
	{
		$this->load->library('datatables');

		$this->main_model->get($id);

		foreach($this->data['relation'][$model]['fields'] as $field => $attrs )
		{
			if($attrs['field'])
			{
				$this->replace_vars($attrs['field']);

				$column_field =  $attrs['field'];
			}
			else
			{
				$column_field =	$this->data['relation'][$model]['db_table'].".".$field;
			}
			$columns[] = $column_field;
		}
		
		$relation_table = isset( $this->data['relation'][$model]['db_table_related'] ) ? $this->data['relation'][$model]['db_table_related'] : $this->main_model->db_table."_".$this->data['relation'][$model]['db_table'];
		
		$joins = "LEFT JOIN ".$relation_table." ON 
						".$relation_table.".".$this->main_model->db_index." = 
						".$this->main_model->db_table.".".$this->main_model->db_index." 
				INNER JOIN ".$this->data['relation'][$model]['db_table']." ON 
						".$relation_table.".".$this->data['relation'][$model]['db_index']." =
						".$this->data['relation'][$model]['db_table'].".".$this->data['relation'][$model]['db_index']." ";

		if( is_array( $this->data['relation'][$model]['arr_nested_tables'] ) )
		{
			foreach( $this->data['relation'][$model]['arr_nested_tables'] as $attrs )
			{
				$joins.= " LEFT JOIN ".$attrs['db_table']." ON  ".$attrs['db_table'].".".$attrs['db_index']." = ".$relation_table.".".$attrs['db_index']." ";
			}
		}

		$where = " AND ".$this->main_model->db_table.".".$this->main_model->db_index." = ".$this->main_model->get_id()." ";
		$search = $this->main_model->db_table.'.'.$columns[0];

		echo $this->datatables->generate($this->main_model->db_table ,$columns,$this->main_model->db_table.".".$this->main_model->db_index, $joins, $where, $search);

	}

	public function relation($model, $id)
	{
		
		if($this->main_model->get($id))
		{
			$this->data['relation_model'] = $model;
			$this->data['current_tab'] = 'relation_'.$model;
			
			switch( $this->data['relation'][$model]['type'] )
			{
				case 'datatable_list':
					$this->relate_datatable($model, $id);
					break;
				case 'multiselect':
					$this->relate_multiselect($model, $id);
					break;
				default: hola('You did not specified the relation type.');
			}
		}else{
			hola("No se pudo establecer la relaci&oacute;n con ".$model);
		}
	}

	public function relate_multiselect($model, $id)
	{
		$this->replace_vars( $this->data['relation'][$model]['label_field'] );

		$order_field = isset( $this->data['relation'][$model]['order_field'] ) ? $this->data['relation'][$model]['order_field']:'priority';

		$sqlRelated = "SELECT T.".$this->data['relation'][$model]['db_index']." as value, T.".$this->data['relation'][$model]['label_field']." as label 
				FROM ".$this->data['relation'][$model]['db_table']." AS T LEFT JOIN ".$this->data['relation'][$model]['db_table_related']." AS TR ON TR.".$this->data['relation'][$model]['db_index']." = T.".$this->data['relation'][$model]['db_index']." 
				WHERE TR.".$this->main_model->db_index." = ".$id."  ORDER BY TR.".$order_field." ASC ";

		$this->data['items_related']= $this->db->query($sqlRelated)->result();
		
		$sqlNotRelated = "SELECT T.".$this->data['relation'][$model]['db_index']." as value, T.".$this->data['relation'][$model]['label_field']." as label 
				FROM ".$this->data['relation'][$model]['db_table']." AS T 
				LEFT JOIN ".$this->data['relation'][$model]['db_table_related']." AS TR ON ( 
					TR.".$this->data['relation'][$model]['db_index']." = T.".$this->data['relation'][$model]['db_index']." 
					AND TR.".$this->main_model->db_index." = ".$id."
				)
				WHERE TR.".$this->main_model->db_index." IS NULL  ORDER BY TR.".$order_field." ASC ";

		$this->data['items_not_related'] = $this->db->query($sqlNotRelated)->result();

		$this->load->view('admin/common/inc.relation.multiselect.php', $this->data);
	}
	
	public function save_relation($model)
	{
		if ( $this->input->post('main_model_id') != 0 && $this->input->post('values') != '' )
		{
			$arr = explode(',',$this->input->post('values'));
			
			if(count($arr) > 0 && $this->main_model->get( $this->input->post('main_model_id') ) )
			{
				// Delete previous relations
				$delete_sql = "DELETE FROM ".$this->data['relation'][$model]['db_table_related']." 
								WHERE ".$this->main_model->db_index." = ".$this->main_model->get_id()."";
				$this->db->query($delete_sql);
				
				// Insert new relations
				$insert_sql = "INSERT IGNORE INTO ".$this->data['relation'][$model]['db_table_related']." ( ".$this->main_model->db_index.", ".$this->data['relation'][$model]['db_index'].", priority ) VALUES ";
				$priority=0;
				foreach($arr as $value)
				{
					$priority++;
					$insert_sql .= "(".$this->main_model->get_id().",".$value.", ".$priority."),";
				}

				$insert_sql = substr($insert_sql,0,-1);

				$this->db->query($insert_sql);

			}
		}
		elseif( $this->input->post('values') == '' && $this->input->post('main_model_id') != 0 )
		{
			if(	$this->main_model->get( $this->input->post('main_model_id') ) )
			{
				// Delete all relations
				$delete_sql = "DELETE FROM ".$this->data['relation'][$model]['db_table_related']." 
								WHERE ".$this->main_model->db_index." = ".$this->main_model->get_id()."";
				$this->db->query($delete_sql);
			}										   
		}else{
			hola('No especifico el ID a cual se debe relacionar');
		}
	}
	
	public function relate_datatable($model, $id)
	{
		$this->data['ao_columns'] = "";

		if(!is_array($this->data['relation'][$model]['page_fields']))
		{
			hola('No hay campos para mostrar');
		}

		foreach($this->data['relation'][$model]['page_fields']['relation_'.$model] as $field => $attr)
		{
			if(!isset( $attr['ao_columns'] ) )
			{
				switch($attr['type'])
				{
					case 'number':
									$this->data['relation'][$model]['ao_columns'] .= "{'sClass' : 'number'},";
									break;
					case 'checkbox':
									$this->data['relation'][$model]['ao_columns'] .= "{'sClass' : 'checkbox'},";
									break;
					case 'date_time':
									$this->data['relation'][$model]['ao_columns'] .= "{'sClass' : 'date_time'},";
									break;
					case 'date':
									$this->data['relation'][$model]['ao_columns'] .= "{'sClass' : 'date'},";
									break;	
				
					default:		$this->data['relation'][$model]['ao_columns'] .= "null,";
									break; 
				}
			}
			else
			{
				$this->data['relation'][$model]['ao_columns'] .= "{";

				foreach($attr['ao_columns'] as $option => $value)
				{
					$this->data['relation'][$model]['ao_columns'] .= "'".$option."' : '".$value."',";
				}

				$this->data['relation'][$model]['ao_columns'] = substr($this->data['relation'][$model]['ao_columns'],0,-1);

				$this->data['relation'][$model]['ao_columns'] .= "},";
			}
		}

		$this->data['relation'][$model]['ao_columns'] = substr($this->data['relation'][$model]['ao_columns'],0,-1);


		$this->load->view('admin/common/inc.relation.datatable.php', $this->data);
	}
	
}
?>