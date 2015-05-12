<?

class Common extends CI_Controller
{
	
	public function departments()
	{
		$data['departments'] = array();
		$data['departments_options'] = "";
		$post = $this->input->post();
		$db_index = $post['db_index'];
		$db_label = $post['db_label'];
		if($post['province_id'])
		{
			$sql = "SELECT ".$db_index.", ".$db_label." FROM ".$post['db_table']." WHERE province_id = '".$post['province_id']."' ORDER BY ".$db_label." ASC";
			$query = $this->db->query($sql);
			foreach($query->result() as $row)
			{
				$data['departments'][$row->$db_index] = $post['ucwords'] ? ucwords(strtolower($row->$db_label)) : $row->$db_label;	
				$data['departments_options'] .= "<option value='".$row->$db_index."'>".$row->$db_label."</option>";
			}
			$data['success'] = true;
		}
		else
		{
			$data['departments'][0] = 'Seleccione una provincia';
			$data['success'] = false;
		}
		
		echo json_encode($data);
	}
	
	public function reorganize_departments()
	{
		
		$provincias = $this->config->item('provincias');
		
		foreach($provincias as $provincia => $localidades)
		{
			if(is_array($localidades))
			{
				echo $insertSQL = "INSERT INTO provinces (province_id, name) VALUES (NULL, '".$provincia."')";
				$this->db->query($insertSQL);
				$province_id = $this->db->insert_id();

				foreach($localidades as $id => $localidad)
				{
					foreach($localidad as $id => $val)
					{
						$this->db->query("INSERT INTO provinces_departments (province_id, name) VALUES ('".$province_id."', '".utf8_encode($val)."')");
					}
					
				}
			}
			
		}
		$localidades = $provincias[$this->input->post('provincia')];
		
		
	}
	
}

?>