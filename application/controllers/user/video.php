<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Video extends CI_Controller
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
		$data['title'] = "Video Tutorials";		
		$data['main'] = 'user/video';	
		$this->load->view('user/dashboard',$data);		
	}
}
