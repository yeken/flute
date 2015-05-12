<?
class Scores extends ADMIN_Controller
{
	public function __construct()
	{
		parent::__construct();
		if($this->bitauth->group_id != 1)
		{
			redirect('admin/home#player/show_list');	
		}	
		
	}
	
	public function update_qualys()
	{
		$qualy_teams = array(	"13","38", //a
								"22","28", //b
								"10","16", //c
								"33","17", //d
								"12","19", //e
								"8","30", //f
								"39","34", //g
								"9","24" //h						
							);
		$sql = "UPDATE prognostics_qualys SET result = 0";
		$this->db->query($sql);
		foreach($qualy_teams as $key => $team_id)
		{
			$sql = "UPDATE prognostics_qualys SET result =  result+1 WHERE team".($key+1)."_id = '".$team_id."'";
			$this->db->query($sql);
		}
		
		$sql = "UPDATE scores AS s, prognostics_qualys AS p
			 	SET s.qualy_points = p.result WHERE p.user_id = s.user_id";
		$this->db->query($sql);

		vd("Qualys seteada");
		$sql = "UPDATE scores SET points = results*3 + exact_results*5 + badges_points + qualy_points + winner_points ";
		$this->db->query($sql);
		vd("Scores actualizado");
		
	}

	public function update_winners()
	{
		$sql = "UPDATE prognostics_winners SET result = 0";
		$this->db->query($sql);
		$sql = "UPDATE prognostics_winners SET result = 3 WHERE winner1_id = '39'";
		$this->db->query($sql);
		$sql = "UPDATE prognostics_winners SET result = result + 3 WHERE winner2_id = '8'";
		$this->db->query($sql);
		$sql = "UPDATE prognostics_winners SET result = result + 3 WHERE winner3_id = '22'";
		$this->db->query($sql);
		
		$sql = "SELECT * FROM companies WHERE winners = 1";
		
		$companies = $this->db->query($sql)->result();
		
		foreach($companies as $company)
		{
			$sql = "UPDATE scores AS s, prognostics_winners AS p 
				SET s.winner_points = p.result WHERE p.user_id = s.user_id
				AND s.company_id = '".$company->company_id."'
				";
			$this->db->query($sql);
		
		}
		$sql = "UPDATE scores SET points = results*3 + exact_results*5 + badges_points + qualy_points + winner_points ";
		$this->db->query($sql);
		vd("Scores actualizado");		
		vd("finalizado");
		
	}
		
	public function set_score_table()
	{
		$sql = "INSERT IGNORE INTO scores (user_id, username, company, company_id, department,department_id, branch, branch_id)
				SELECT user_id, fullname, company, company_id, department, department_id, branch, branch_id FROM bitauth_users WHERE active = 1 AND group_id = 3";	
		$this->db->query($sql);
		vd("Tabla de puntajes seteada.");
	}
	
	public function calculate_badges()
	{
		$this->load->model("admin/user_badges_model","user_badges_model");
		$this->user_badges_model->calculate_badges();	
	}

	public function update_scores()
	{
		
		$this->set_score_table();
		$sql = "SELECT * FROM scores_update WHERE state = 'start'";
		$this->load->model("admin/user_badges_model","user_badges_model");
		$this->load->model("admin/match_model","match_model");
		
		
		$matches = $this->db->query($sql)->result_array();
		if(!count($matches))
		{
			vd("No hay partidos para inicializar");	
		}
		
		foreach($matches as $match)
		{
			if(!$this->match_model->get($match['match_id']))
			{
				vd("no existe el partido ".$match['match_id']." ".$match['name']);
				vd("No se actualizan los scores");
				continue;	
			}
			
			$this->prepare_match();
			
			//$this->user_badges_model->calculate_badges();	
			
			$sql = "UPDATE scores_update SET state = 'badges' WHERE match_id = '".$this->match_model->get_id()."'";
			$this->db->query($sql);
					
			vd("Insertando scores");
			$sql = "UPDATE scores AS s, prognostics_match AS p
					SET s.results = s.results + p.result_match, s.exact_results = s.exact_results + exact_result_match
					WHERE s.user_id = p.user_id";
			$this->db->query($sql);
		
			vd("Generando puntos");
			
			$sql = "UPDATE scores SET points = results*3 + exact_results*5 + badges_points + qualy_points + winner_points ";
			$this->db->query($sql);
			vd("<span style='color:green'>Generación de puntos OK. Comenzando cierre...</span>");
			$this->close_scores();
		}
		
		
	}

	public function close_scores()
	{
		$sql = "UPDATE scores_update SET state = 'finish' WHERE match_id = '".$this->match_model->get_id()."'";
		$this->db->query($sql);
		
		vd("------ Scores finalizado -------------> match_id: ".$this->match_model->get_id()."<br>CHEQUEAR DATOS<br>");
		vd("Registros de puntaje random:");
		$sql = "SELECT * FROM scores WHERE points > 0 ORDER BY RAND() LIMIT 0, 5";
		$scores = $this->db->query($sql)->result_array();
		vd($scores);	
		$sql = "SELECT * FROM scores ORDER BY RAND() LIMIT 0, 5";
		$scores = $this->db->query($sql)->result_array();
		vd($scores);	

	}
	
	protected function prepare_match()
	{
		$sql = "UPDATE prognostics SET exact_result_match = 0, result_match = 0 WHERE match_id = '".$this->match_model->get_id()."'";
		$this->db->query($sql);
		$sql = "UPDATE prognostics
				SET exact_result_match = 1 
				WHERE match_id = '".$this->match_model->get_id()."' AND team1_goals = '".$this->match_model->team1_goals."' AND team2_goals = '".$this->match_model->team2_goals."'";
		$this->db->query($sql);
		$sql = "UPDATE prognostics
				SET result_match = 1
				WHERE match_id = '".$this->match_model->get_id()."' AND result = '".$this->match_model->result."' AND exact_result_match = 0";
		$this->db->query($sql);
		
		vd("Pronosticos actualizados. match_id: ".$this->match_model->get_id());
		vd("CHEQUEAR PRONOSTICOS");
		
		$sql = "SELECT * FROM prognostics WHERE match_id = '".$this->match_model->get_id()."' ORDER BY RAND() LIMIT 0,10";
		$progs = $this->db->query($sql)->result_array();
		vd($progs);
		
		$sql = "DROP TABLE IF EXISTS prognostics_match";
		$this->db->query($sql);
		$sql = "CREATE TABLE prognostics_match LIKE prognostics";
		$this->db->query($sql);
		$sql = "INSERT prognostics_match SELECT * FROM prognostics WHERE match_id = '".$this->match_model->get_id()."'";
		$this->db->query($sql);
		vd("tabla temporal creada");
	
	}
}
?>