<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Calendar extends CI_Controller
{
	var $conf;
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
	function init($isprint){
		$conf = array(
			'start_day' => 'sunday',
			'show_next_prev' => true,
			'next_prev_url' => base_url() . 'admin/calendar/display'
		);
		
		
		$conf['template'] = '
			{table_open}<table border="0" cellpadding="0" cellspacing="0" class="calendar">{/table_open}
			
			{heading_row_start}<tr>{/heading_row_start}
			';
			if($isprint==FALSE)
				$conf['template'] .= '{heading_previous_cell}<th><a href="{previous_url}"><img src="'.base_url().'/images/leftbtn.png" /></a></th>{/heading_previous_cell}';
			$conf['template'] .= '{heading_title_cell}<th colspan="{colspan}"><span class="monthname">{heading}</span></th>{/heading_title_cell}';
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
				
		$cal_data = $this->MCalendar->get_calendar_data($year,$month);						
		$data['calendar'] = $this->calendar->generate($year, $month, $cal_data);				
		$data['title'] = "Calendar";		
		$data['main'] = 'admin/calendar';			
		$data['year'] = $year;
		$data['month'] = $month;
		$data['cssfiles'] = array('redmond/jquery-ui-1.8.16.custom.css','calendar.css');
		$data['jsfiles'] = array('jquery-ui-1.8.9.custom.min.js','calendar-admin.js');				
		$this->load->view('admin/dashboard',$data);	
	}
	function addperiod(){
		$this->load->model('MCalendar');
		$year = intval($this->input->post('year'));
		$month = intval($this->input->post('month'));
		$day = intval($this->input->post('day'));
		$this->MCalendar->add_calendar_data("$year-$month-$day");		
	}
	function delperiod(){
		$this->load->model('MCalendar');
		$year = intval($this->input->post('year'));
		$month = intval($this->input->post('month'));
		$day = intval($this->input->post('day'));
		$this->MCalendar->add_calendar_data("$year-$month-$day");
		$this->MCalendar->delete_calendar_data("$year-$month-$day");
	}
	function unregister(){
		$this->load->model('MVolunteer');
		$idvolunteer = intval($this->input->get('idvolunteer'));		
		$ret =$this->MVolunteer->admin_unregister_user($idvolunteer);
		$this->session->set_flashdata('message','Unregister successful');
		redirect('admin/calendar/display','refresh');
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
				
		$cal_data = $this->MCalendar->get_calendar_data($year,$month);						
		$data['calendar'] = $this->calendar->generate($year, $month, $cal_data);				
		$data['title'] = "Calendar";		
		$data['main'] = 'admin/calendar_print';			
		$data['year'] = $year;
		$data['month'] = $month;		
		$data['cssfiles'] = array('calendar.css');		
		$this->load->view('admin/dashboard_print',$data);	
	}
	function setmeeting(){
		$this->load->model('MCalendar');
		$year = intval($this->input->post('year'));
		$month = intval($this->input->post('month'));
		$day = intval($this->input->post('day'));
		$this->MCalendar->set_meeting("$year-$month-$day");
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
		$data['main'] = 'admin/calendar_user_print';			
		$data['year'] = $year;
		$data['month'] = $month;				
		$this->load->view('admin/dashboard_print',$data);	
	}
}
?>
