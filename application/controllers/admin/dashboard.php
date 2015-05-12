<?
class Dashboard extends ADMIN_Controller
{
	
	public function index()
	{	
		//$this->show_reports();
		$this->load->view('admin/dashboard/inc.dashboard.php', $this->data);
	}

	public function show_reports()
	{

		$this->ini_condition = $this->ini_condition ? $this->ini_condition : '';	
		if($this->user_content_only())
		{
			$this->ini_condition .= " AND creator_id = '".$this->session->userdata('user_id')."'";	
		}

		$this->range_condition = "";
		if( $this->input->post('rangeStart') && $this->input->post('rangeFinish') )
		{
			$this->range_condition .= " AND date_created > '".$this->input->post('rangeStart')."' AND date_created < '".$this->input->post('rangeFinish')."' ";
			$this->data['rangeStart'] = $this->input->post('rangeStart');
			$this->data['rangeFinish'] = $this->input->post('rangeFinish');
		}elseif( $this->input->post('rangeStart') )
		{
			$this->range_condition .= " AND date_created > '".$this->input->post('rangeStart')."' ";
			$this->data['rangeStart'] = $this->input->post('rangeStart');
			$this->data['rangeFinish'] = "";
		}else{
			$d = new DateTime( date('Y-m-d') ); 
			$this->data['rangeFinish'] = $d->format( 'Y-m-t' );
			$this->data['rangeStart'] = date('Y-m')."-01";
			$this->range_condition .= " AND date_created > '".$this->data['rangeStart']."' AND date_created < '".$this->data['rangeFinish']."'";
		}

		$sql = "SELECT count(*) as total_consultas FROM contacts WHERE 1=1 ".$this->ini_condition.$this->range_condition;
		$this->data['total_consultas'] = $this->db->query($sql)->row()->total_consultas;

		$this->load->view('admin/dashboard/inc.dashboard.php', $this->data);
	}
	
	public function show_analytics()
	{
		
		require_once(dirname(__FILE__).'/../../libraries/gapi.class.php');
		
		/* Define variables */
		$ga_email       = 'andres.dicamillo@gmail.com';
		$ga_password    = 'awghgdtcpympztqb';
		$ga_profile_id  = '68514231';
		$ga_url         = $_SERVER['REQUEST_URI'];
		
		/* Create a new Google Analytics request and pull the results */
		$ga = new gapi($ga_email,$ga_password);
		$ga->requestReportData($ga_profile_id, array('date'),array('pageviews'), 'date', 'pagePath == '.$ga_url);    
		
		$results = $ga->getResults();
		$this->data['results'] = $results;
		
		$ga->requestReportData($ga_profile_id, 'pagePath', array('pageviews', 'uniquePageviews', 'exitRate', 'avgTimeOnPage', 'entranceBounceRate'), null, 'pagePath == '.$ga_url);
		$results = $ga->getResults();
		
		$this->data['pageviews'] = $results;

		$this->data['ga'] = $ga;

		$this->load->view('admin/dashboard/inc.analytics.php', $this->data);
	}
}
?>