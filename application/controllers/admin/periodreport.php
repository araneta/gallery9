<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class PeriodReport extends CI_Controller
{	
	public function __construct() {
		parent::__construct();	                		
		session_start();		  
		if (empty($_SESSION['userid']) || $_SESSION['userid'] < 1){
			redirect('login','refresh');
		}
		if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] < 1){
			$this->session->set_flashdata('message','Your not an admin');
			redirect('login','refresh');
		}				
	}
	function display(){		
		$this->load->model('MPeriodReport');
		$data['title'] = "Period Report";		
		$data['main'] = 'admin/period_report';			
		$data['periods'] = $this->MPeriodReport->get_all_period();
		$periodid = 0;
		if(isset($_POST['periodid'])){
			$periodid = intval($this->input->post('periodid'));			
			$data['selperiod'] = $this->MPeriodReport->get_period_by_id($periodid);			
		}else{
			$firstperiod = $this->MPeriodReport->get_first_period();
			if($firstperiod!=null){
				$periodid = intval($firstperiod['id']);
				$data['selperiod'] = $firstperiod;
			}
		}
		$data['empty_dates'] = $this->MPeriodReport->get_empty_dates($periodid);
		$data['missing_signup'] = $this->MPeriodReport->get_missing_signup($periodid);
		
		$this->load->view('admin/dashboard_print',$data);	
	}
}
?>
