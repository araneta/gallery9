<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Info extends CI_Controller
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
	function index()
	{				 		 	    
		$data['title'] = "Admin Info";		
		$data['main'] = 'admin/info';					
		$this->load->model('MUser');
		$data['admin'] = $this->MUser->admin_info();
		$this->load->view('admin/dashboard',$data);				
	}
	
	function update_info_validate(){
		//$this->form_validation->set_rules('username','Username','trim|required|alpha_numeric|max_length[20]|xss_clean');
		$this->form_validation->set_rules('email','email','trim|required|max_length[40]|valid_email|xss_clean');
		return $this->form_validation->run();
	}
	function update(){
		if($this->update_info_validate()==FALSE){
			$this->index();
			return;
		}else
		{
			$this->load->model('MUser');
			$this->MUser->update_admin_info(
				array(
				//'username'=>$this->input->post('username'),
				'email'=>$this->input->post('email'),
				'phone'=>$this->input->post('phone'),
				'password'=>$this->input->post('password')
				)
			);
			$this->session->set_flashdata('message','Info Updated');
			redirect('admin/info','refresh');
				
		}
	}
	
}
?>
