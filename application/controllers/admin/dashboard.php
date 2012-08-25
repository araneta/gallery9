<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller
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
		$data['title'] = "Dashboard";		
		$data['main'] = 'admin/user_home';			
		$this->load->view('admin/dashboard',$data);				
	}
	function logout(){
		unset($_SESSION['userid']);
		unset($_SESSION['username']);
		unset($_SESSION['is_admin']);
		$this->session->set_flashdata('message',"You've been logged out!");
		redirect('login','refresh');
	}		
}
?>
