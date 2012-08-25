<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CalendarNote extends CI_Controller
{	
	public function __construct() {
		parent::__construct();	                		
        session_start();		  
		if (empty($_SESSION['userid']) || $_SESSION['userid'] < 1){
			redirect('login','refresh');
		}				
	}
	function addDlg($id){
		$data['text'] = '';			
		$data['id'] = $id;			
		$this->load->view('user/note',$data);
	}
	function editDlg($id){		
		if(!empty($id)){
			$this->load->model('MVolunteer');					
			$note = $this->MVolunteer->get_note($id,intval($_SESSION['userid'] ));
			if($note==null)
				die('cant find note');
			$data['text'] = $note->note;
			$data['id'] = $id;	
			$data['disablesave'] = FALSE;
			if(intval($note->g9_user_id)!=intval($_SESSION['userid'] ))
				$data['disablesave'] = TRUE;
			$this->load->view('user/note',$data);						
		}		
	}
	function savenote(){
		$this->load->model('MVolunteer');
		echo json_encode($this->MVolunteer->save_note(
			$this->input->post('id'),$this->input->post('text'),intval($_SESSION['userid'] ))
		);
	}
}
