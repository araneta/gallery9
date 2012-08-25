<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller
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
		$data['title'] = "Users";		
		$data['main'] = 'admin/user_list';			
		$data['cssfiles'] = array('panel.css');
		$data['jsfiles'] = array('panel.js','user_list.js');
		$this->load->model('MUser');
		$data['users'] = $this->MUser->list_user();
		$this->load->view('admin/dashboard',$data);				
	}
	function username_check($str)
	{
		//die('tre'.$str);
		if(empty($str)){
			$this->form_validation->set_message('username_check','username empty');
			//return FALSE;
		}
		$user = $this->MUser->get_user($str);
		if($user!=null)
		{
			if($user['is_inactive']==0){
				$this->form_validation->set_message('username_check','username already registered');
				return FALSE;
			}
		}
		return TRUE;
	}
	
	function user_add_validate(){			
		$this->form_validation->set_rules('username', 'username', 'trim|required|alpha_numeric|min_length[2]|max_length[20]|xss_clean|callback_username_check');		
		$this->form_validation->set_rules('phone', 'phone', 'trim|required|max_length[20]|xss_clean');				
		$this->form_validation->set_rules('email','email','trim|valid_email|max_length[40]|xss_clean');
		return ($this->form_validation->run());
	}
	function add(){
		$this->load->model('MUser');	
		if($this->user_add_validate()==FALSE){
			$this->index();
			return;
		}else
		{
			$email = $this->input->post('email');
			if(empty($email))
				$email = 'NoReply@domain.com';
			$this->MUser->add(
				array(
					'username'=>$this->input->post('username'),
					'phone'=>$this->input->post('phone'),
					'email'=>$email
				)
			);
			$this->session->set_flashdata('message','User Saved');
			redirect('admin/user','refresh');
		}
	}
	function change_all_user_pass_validate(){
		$this->form_validation->set_rules('password','password','trim|required|min_length[5]|max_length[256]|xss_clean');
		return ($this->form_validation->run());
	}
	function change_all_user_pass(){
		if($this->change_all_user_pass_validate()==FALSE){
			$this->index();
			return;
		}else{
			$this->load->model('MUser');
			$this->MUser->change_all_user_pass($this->input->post('password'));
			$this->session->set_flashdata('message','Password Changed');
			redirect('admin/user','refresh');
		}
	}
	function username_edit_check($str)
	{		
		if(empty($str)){
			$this->form_validation->set_message('username_edit_check','username empty');
			//return FALSE;
		}
		
		if($this->MUser->username_exists($str)==FALSE)
		{
			$this->form_validation->set_message('username_edit_check','User doesn’t exist');
			return FALSE;
		}
		return TRUE;
	}
	function update_validate(){
		$this->form_validation->set_rules('txteditusername', 'username', 'trim|required|alpha_numeric|min_length[2]|max_length[20]|xss_clean|callback_username_edit_check');		
		$this->form_validation->set_rules('txteditphone', 'phone', 'trim|required|max_length[20]|xss_clean');				
		$this->form_validation->set_rules('txteditemail','email','trim|valid_email|max_length[40]|xss_clean');
		return ($this->form_validation->run());
	}
	function update(){
		$this->load->model('MUser');
		if($this->update_validate()==FALSE){
			$this->index();
			return;
		}else
		{	
			$email = $this->input->post('txteditemail');
			if(empty($email))
				$email = 'NoReply@domain.com';		
			$this->MUser->update(
				array(
					'id'=>$this->input->post('hfedituserid'),
					//'username'=>$this->input->post('txteditusername'),
					'phone'=>$this->input->post('txteditphone'),
					'email'=>$email
				)
			);
			$this->session->set_flashdata('message','User updated');
			redirect('admin/user','refresh');
		}
	}
	function delete(){
		$this->load->model('MUser');
		$this->MUser->delete(intval($this->input->post('iduser')));
			$this->session->set_flashdata('message','User deleted');
			redirect('admin/user','refresh');
	}
}
?>
