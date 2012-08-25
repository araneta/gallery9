<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function __construct() {
		parent::__construct();	                		
        session_start();		  
		
	}
	public function index() {
		if (!empty($_SESSION['userid'])&&$_SESSION['userid'] > 0){
			redirect('user/dashboard','refresh');
			return;
		}		
		$this->load->model('MUser');
		$data['title'] = "Login";		
		$data['main'] = 'login';					
		$data['jsfiles'] = array('login.js');
		$data['admininfo'] = $this->MUser->admin_info();
		$this->load->view('template',$data);		
	}

	public function verify() 
	{
		$this->load->model('MUser');
		if ($this->input->post('username')){
			$u = $this->input->post('username');
			$pw = $this->input->post('password');
			$this->MUser->verifyUser($u,$pw);
			if (!empty($_SESSION['userid'])&&$_SESSION['userid'] > 0){
				if(!empty($_SESSION['is_admin'])&&$_SESSION['is_admin'] > 0){
					redirect('admin/calendar/display','refresh');
				}
				redirect('user/calendar/display','refresh');
				return;
			}else
			{
				$this->session->set_flashdata('message','Invalid username or password');
			}
		}else
		{
			$this->session->set_flashdata('message','Invalid username or password');
		}
		redirect('login','refresh');	
	}
	
}

?>
