<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Info extends CI_Controller
{	
	public function __construct() {
		parent::__construct();	                		
        session_start();		  
		if (empty($_SESSION['userid']) || $_SESSION['userid'] < 1){
			redirect('login','refresh');
		}				
	}
	function index(){
		$data['title'] = "My Account";		
		$data['main'] = 'user/info';					
		$this->load->model('MUser');
		$this->load->model('MVolunteer');
		$data['userinfo'] = $this->MUser->user_info(intval($_SESSION['userid']));
		$data['datesvolunteered'] = $this->MVolunteer->dates_volunteered(intval($_SESSION['userid']));
		$this->load->view('user/dashboard',$data);		
	}
	
	function update_info_validate(){
		//$this->form_validation->set_rules('username','Username','trim|required|alpha_numeric|max_length[20]|xss_clean');
		$this->form_validation->set_rules('phone','Phone','trim|required|max_length[20]|xss_clean');
		$this->form_validation->set_rules('email','email','trim|required|valid_email|max_length[40]|xss_clean');
		return $this->form_validation->run();
	}
	function update(){
		if($this->update_info_validate()==FALSE){
			$this->index();
			return;
		}else
		{
			$this->load->model('MUser');
			$this->MUser->update_user_info(
				array(
				//'username'=>$this->input->post('username'),
				'email'=>$this->input->post('email'),
				'phone'=>$this->input->post('phone'),
				'userid'=>intval($_SESSION['userid'])
				)
			);
			$this->session->set_flashdata('message','Info Updated');
			redirect('user/info','refresh');
				
		}
	}
	function printinfo(){
		$data['title'] = "My Account";		
		$data['main'] = 'user/info_print';							
		$this->load->model('MUser');
		$this->load->model('MVolunteer');	
		$data['userinfo'] = $this->MUser->user_info(intval($_SESSION['userid']));
		$data['datesvolunteered'] = $this->MVolunteer->dates_volunteered(intval($_SESSION['userid']));
		$this->load->view('user/dashboard_print',$data);		
	}
}
