<?
class Stats extends ADMIN_Controller
{
	public function __construct()
	{
		parent::__construct();
		
	}

	public function show_stats()
	{
		$sql = "SELECT * FROM companies";
		$companies = $this->db->query($sql)->result_array();
		
		foreach($companies AS $company)
		{
			$company_id = $company['company_id'];
			vd("<h1>".$company['name']."</h1>");
			
			$sql = "SELECT COUNT(*) AS total FROM bitauth_users WHERE company_id = '".$company_id."'";
			$row = $this->db->query($sql)->row();
			$stats['total_users'] = $row->total;
			
			$sql = "SELECT COUNT(*) AS total FROM bitauth_users WHERE company_id = '".$company_id."' AND active = 1";
			$row = $this->db->query($sql)->row();
			$stats['total_active_users'] = $row->total;
		
			$sql = "SELECT COUNT(*) AS total FROM wall WHERE company_id = '".$company_id."'";
			$row = $this->db->query($sql)->row();
			$stats['total_wall_posts'] = $row->total;
			/*
			$sql = "SELECT COUNT(*) AS total FROM wall_comments WHERE company_id = '".$company_id."'";
			$row = $this->db->query($sql)->row();
			$stats['total_wall_comments'] = $row->total;
			*/
			$sql = "SELECT COUNT(*) AS total FROM prognostics WHERE company_id = '".$company_id."' AND exact_result_match = 1";
			$row = $this->db->query($sql)->row();
			$stats['total_exact_results'] = $row->total;

			$sql = "SELECT COUNT(*) AS total FROM prognostics WHERE company_id = '".$company_id."' AND result_match = 1";
			$row = $this->db->query($sql)->row();
			$stats['total_results'] = $row->total;

			$sql = "SELECT SUM(result) AS total FROM prognostics_qualys WHERE company_id = '".$company_id."' GROUP BY company_id";
			$row = $this->db->query($sql)->row();
			$stats['total_qualys'] = $row->total;
			
			$sql = "SELECT SUM(result) AS total FROM prognostics_winners WHERE company_id = '".$company_id."' GROUP BY company_id";
			$row = $this->db->query($sql)->row();
			$stats['total_winners'] = ($row->total/3);
			
			
			$sql = "SELECT badges_code FROM user_badges WHERE company_id = '".$company_id."' AND badges_code != ''";
			$result = $this->db->query($sql)->result();
			
			
			$badges_names = array(1 => "anti", 
							2 => "grupo_a", 
							3 => "grupo_b", 
							4 => "grupo_c", 
							5 => "grupo_d", 
							6 => "grupo_e", 
							7 => "grupo_f", 
							8 => "grupo_g",
							9 => "grupo_h",
							10 => "five_or_more",
							11 => "mejor",
							12 => "peor",
							13 => "super", 
							15 => "turtle",
							16 => "vende",
							18 => "vidente"
							);	
			
			foreach($result as $row)
			{
				$code = $row->badges_code;
				
				$code = explode("|",$code);
				
				foreach($code as $badge_code)
				{
					$badge_code = explode("/",$badge_code);
					$stats['badges'][$badges_names[$badge_code[0]]] += $badge_code[1];
				}	
				
			}
			
			$sql = "SELECT COUNT(*) AS total FROM friends_leagues WHERE company_id = '".$company_id."'";
			$row = $this->db->query($sql)->row();
			$stats['total_friends_leagues'] = $row->total;
			
			$sql = "SELECT COUNT(*) AS total FROM user_badges WHERE turtle = 0 AND company_id = '".$company_id."'";
			$row = $this->db->query($sql)->row();
			$stats['0 turtle'] = $row->total;
			
			$sql = "SELECT COUNT(*) AS total FROM user_badges WHERE turtle = 1 AND company_id = '".$company_id."'";
			$row = $this->db->query($sql)->row();
			$stats['1 turtle'] = $row->total;
			
			$sql = "SELECT COUNT(*) AS total FROM user_badges WHERE turtle = 2 AND company_id = '".$company_id."'";
			$row = $this->db->query($sql)->row();
			$stats['2 turtle'] = $row->total;
			
			$sql = "SELECT COUNT(*) AS total FROM user_badges WHERE turtle >= 3 AND company_id = '".$company_id."'";
			$row = $this->db->query($sql)->row();
			$stats['3+ turtle'] = $row->total;
		
			$sql = "SELECT SUM(points) AS total FROM scores WHERE company_id = '".$company_id."' AND points > 0 GROUP BY '".$company_id."'";
			$row = $this->db->query($sql)->row();
			$stats['total_points'] = $row->total;
			
			$sql = "SELECT points FROM scores WHERE company_id = '".$company_id."' ORDER BY points DESC LIMIT 1";
			$row = $this->db->query($sql)->row();
			
			$sql = "SELECT * FROM scores WHERE company_id = '".$company_id."' AND points = '".$row->points."'";
			$result = $this->db->query($sql)->result();
			$stats['max_points'] = $row->points;
			foreach($result as $row)
			{
				$stats['max_points_users'][$row->user_id] = $row->username;
			}
			
			$sql = "SELECT COUNT(*) AS total FROM prognostics_winners WHERE company_id = '".$company_id."' AND winner1_id = '39'";
			$row = $this->db->query($sql)->row();
			$stats['total_germany_winner'] = $row->total;
			
			$sql = "SELECT COUNT(*) AS total FROM prognostics_winners WHERE company_id = '".$company_id."' AND winner2_id = '8'";
			$row = $this->db->query($sql)->row();
			$stats['total_argentina_cipolla'] = $row->total;
			
			$sql = "SELECT COUNT(*) AS total FROM prognostics_winners WHERE company_id = '".$company_id."' AND winner3_id = '22'";
			$row = $this->db->query($sql)->row();
			$stats['total_nederlands_3d'] = $row->total;
			
			$qualy_teams = array(	"13","38", //a
								"22","28", //b
								"10","16", //c
								"33","17", //d
								"12","19", //e
								"8","30", //f
								"39","34", //g
								"9","24" //h						
							);
			
			foreach($qualy_teams as $key => $team_id)
			{
				
				$sql = "SELECT COUNT(*) AS total FROM prognostics_qualys WHERE team".( $key+1)."_id = '".$team_id."' AND company_id = '".$company_id."'";
				$row = $this->db->query($sql)->row();
				
				$stats['qualys_winners_chosen'][ $key+1] = $row->total;	
			}
			
			vd($stats);
			$stats = array();
		}
	}

}
?>