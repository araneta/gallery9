<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contact extends CI_Controller
{	
	public function __construct() {
		parent::__construct();	                		
        session_start();		  
		if (empty($_SESSION['userid']) || $_SESSION['userid'] < 1){
			redirect('login','refresh');
		}				
	}
	function index(){
		$this->load->model('MUser');
		$data['title'] = "Contact Administrator";		
		$data['main'] = 'user/contact';	
		$data['admin'] = $this->MUser->admin_info();				
		$this->load->view('user/dashboard',$data);		
	}
	function send_validate(){
		$this->form_validation->set_rules('subject','Subject','trim|required|xss_clean');
		$this->form_validation->set_rules('message','Message','trim|required|xss_clean');
		return $this->form_validation->run();
	}
	function send(){
		if($this->send_validate()==FALSE){
			$this->index();
			return;
		}else
		{
			$this->load->model('MUser');
			$this->load->helper('mail');
			$admin = $this->MUser->admin_info();			
			$user = $this->MUser->user_info(intval($_SESSION['userid']));
			$param = array(
					'from' =>$user['email'],
					'from_name'=>$user['username'],					
					'tos'=>$admin['email'],
					'subject'=> "G9: ".$this->input->post('subject'),
					'body'=>$this->input->post('message')
				);
			$ret = send_email($param);
			if($ret['success']==FALSE){
				$this->session->set_flashdata('message','Message Not Sent <br/>'.$ret['msg']);				
			}else
				$this->session->set_flashdata('message','Message Sent');
			redirect('user/contact','refresh');
				
		}
	}
}
