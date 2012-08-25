<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Calendar extends CI_Controller
{	
	public function __construct() {
		parent::__construct();	                		
        session_start();		  
		if (empty($_SESSION['userid']) || $_SESSION['userid'] < 1){
			redirect('login','refresh');
		}		
		
	}
	function init($isprint){
		$conf = array(
			'start_day' => 'sunday',
			'show_next_prev' => true,
			'next_prev_url' => base_url() . 'user/calendar/display'
		);
		
		$conf['template'] = '
			{table_open}<table border="0" cellpadding="0" cellspacing="0" class="calendar">{/table_open}
			
			{heading_row_start}<tr>{/heading_row_start}
			';
			if($isprint==FALSE)
				$conf['template'] .= '{heading_previous_cell}<th><a href="{previous_url}"><img src="'.base_url().'/images/leftbtn.png" /></a></th>{/heading_previous_cell}';
			$conf['template'] .= '{heading_title_cell}<th colspan="{colspan}"><span class="monthname">';
			if($isprint==TRUE){
				$conf['template'] .='Gallery 9 Calendar: ';
			}
			$conf['template'] .= '{heading}</span></th>{/heading_title_cell}';
			if($isprint==FALSE)
				$conf['template'] .= '{heading_next_cell}<th><a href="{next_url}"><img src="'.base_url().'/images/rightbtn.png" /></a></th>{/heading_next_cell}';
			$conf['template'] .= '{heading_row_end}</tr>{/heading_row_end}			
			{week_row_start}<tr>{/week_row_start}
			{week_day_cell}<td>{week_day}</td>{/week_day_cell}
			{week_row_end}</tr>{/week_row_end}
			
			{cal_row_start}<tr class="days">{/cal_row_start}
			{cal_cell_start}<td class="day">{/cal_cell_start}
			
			{cal_cell_content}
				<div class="day_num">{day}</div>
				<div class="content">{content}</div>
			{/cal_cell_content}
			{cal_cell_content_today}
				<div class="day_num highlight">{day}</div>
				<div class="content">{content}</div>
			{/cal_cell_content_today}
			
			{cal_cell_no_content}<div class="day_num">{day}</div>{/cal_cell_no_content}
			{cal_cell_no_content_today}<div class="day_num highlight">{day}</div>{/cal_cell_no_content_today}
			
			{cal_cell_blank}&nbsp;{/cal_cell_blank}
			
			{cal_cell_end}</td>{/cal_cell_end}
			{cal_row_end}</tr>{/cal_row_end}
			{table_close}
			';
			if($isprint==FALSE)
				$conf['template'] .= '<tr><td colspan="8" >				
				</td></tr>';
			
			$conf['template'] .= '</table>{/table_close}';		
		$this->load->library('calendar', $conf);
	}
	
	function display($year = null, $month = null) {
		$this->init(FALSE);		
		if (!$year) {
			$year = date('Y');
		}
		if (!$month) {
			$month = date('m');
		}
		
		$this->load->model('MCalendar');
				
		$cal_data = $this->MCalendar->get_calendar_data_user($year,$month,$_SESSION['username']);						
		$data['calendar'] = $this->calendar->generate($year, $month, $cal_data);				
		$data['title'] = "Calendar";		
		$data['main'] = 'user/calendar';			
		$data['year'] = $year;
		$data['month'] = $month;
		$data['username'] = $_SESSION['username'];
		$data['cssfiles'] = array('redmond/jquery-ui-1.8.16.custom.css','calendar.css');				
		$data['jsfiles'] = array('jquery-ui-1.8.9.custom.min.js','calendar-user.js');		
		$this->load->view('user/dashboard',$data);	
	}		
	function send_email($user,$admin,$action,$date){
		$this->load->helper('mail');						
		$param = array(
			'from' =>'NoReply@domain.com',
			'from_name'=>'NoReply@domain.com',					
			'tos'=>$admin['email'],
			'subject'=> sprintf('G9C:%s: %s, %s',$action,$user['username'],$date),
			'body'=>$this->create_email_content($user,$date,$action)
		);			
		return send_email($param);
	}
	function create_email_content($user,$date,$action){
		$html ='This is an automated message from the Gallery 9 Calendar.'."\n\n";
		$html .= 'Action: '.$action."\n";
		$html .= 'Username: '.$user['username']."\n";
		$html .= 'Phone: '.$user['phone']."\n";
		$html .= 'Email: '.$user['email']."\n";
		$html .= 'Phone: '.$user['phone']."\n";
		$html .= 'Date: '.$date."\n";
		return $html;
	}
	function register(){
		$this->load->model('MVolunteer');
		$year = intval($this->input->post('year'));
		$month = intval($this->input->post('month'));
		$day = intval($this->input->post('day'));	
		
		$ret = $this->MVolunteer->register_user(
					intval($_SESSION['userid']),
					"$year-$month-$day"				
				);		
		if($ret['success']==TRUE){	
			$this->load->model('MUser');			
			$admin = $this->MUser->admin_info();			
			$user = $this->MUser->user_info(intval($_SESSION['userid']));
			$this->send_email($user,$admin,'Sign up',$year.'-'.$month.'-'.$day);			
			
		}
		echo json_encode($ret);
	}
	function unregister(){
		$this->load->model('MVolunteer');
		$idvolunteer = intval($this->input->post('idvolunteer'));		
		$ret =$this->MVolunteer->unregister_user(
					intval($_SESSION['userid']),					
					$idvolunteer				
				);
		
		$year = intval($this->input->post('year'));
		$month = intval($this->input->post('month'));
		$day = intval($this->input->post('day'));
		$this->load->model('MUser');	
		$admin = $this->MUser->admin_info();			
		$user = $this->MUser->user_info(intval($_SESSION['userid']));
		$this->send_email($user,$admin,'Deleted',$year.'-'.$month.'-'.$day);			
		echo json_encode($ret);
	}
	function printcal($year = null, $month = null){
		$this->init(TRUE);		
		if (!$year) {
			$year = date('Y');
		}
		if (!$month) {
			$month = date('m');
		}
		
		$this->load->model('MCalendar');
				
		$cal_data = $this->MCalendar->get_calendar_data_user($year,$month,$_SESSION['username']);						
		$data['calendar'] = $this->calendar->generate($year, $month, $cal_data);				
		$data['title'] = "Calendar";		
		$data['main'] = 'user/calendar_print';			
		$data['year'] = $year;
		$data['month'] = $month;
		$data['username'] = $_SESSION['username'];
		$data['cssfiles'] = array('calendar.css');		
		$this->load->view('user/dashboard_print',$data);	
	}
	function printcaluser($year = null, $month = null){
		$this->init(TRUE);		
		if (!$year) {
			$year = date('Y');
		}
		if (!$month) {
			$month = date('m');
		}
		
		$this->load->model('MCalendarReport');
				
		$data['users'] = $this->MCalendarReport->get_user($year,$month);								
		$data['title'] = "User Report";				
		$data['main'] = 'user/calendar_user_print';			
		$data['year'] = $year;
		$data['month'] = $month;				
		$this->load->view('user/dashboard_print',$data);	
	}
}
?>
