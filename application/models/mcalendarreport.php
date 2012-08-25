<?php
class MCalendarReport extends CI_Model {
		
	function __construct()
	{			
		parent::__construct();
		
	}
	function get_user($year, $month) {
		$data = array();
		$query = $this->db->select('g9_volunteer.id,g9_volunteer.date,g9_volunteer.note,g9_user.username,g9_user.phone')
			->from('g9_volunteer')
			->join('g9_user','g9_user.id = g9_volunteer.g9_user_id')
			->like('date',"$year-$month",'after')			
			->order_by('g9_volunteer.date', "asc")
			->get();
		foreach($query->result() as $row){
			$data[] = $row;			
		}
		return $data;
		
	}
}
?>
