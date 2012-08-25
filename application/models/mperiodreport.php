<?php
class MPeriodReport extends CI_Model {
		
	function __construct()
	{			
		parent::__construct();
		
	}
	function get_all_period(){
		$sql = sprintf("select * from g9_period order by period_date asc");
		$q = $this->db->query($sql);
		$n = $q->num_rows();
		$data = array();
		if($n>0)
		{
			foreach($q->result_array() as $row)
			{
				$data[] = $row;
			}
		}
		$q->free_result();
		return $data;
	}
	function get_first_period(){
		$sql = sprintf("select * from g9_period order by period_date asc limit 0,1");
		$q = $this->db->query($sql);
		$n = $q->num_rows();
		$data = null;
		if($n>0)
		{
			$row = $q->result_array();			
			$data = $row[0];
		}
		$q->free_result();
		return $data;
	}
	function get_period_by_id($periodid){
		$sql = sprintf("select * from g9_period where id=%d",id_clean($periodid));
		$q = $this->db->query($sql);
		$n = $q->num_rows();
		$data = null;
		if($n>0)
		{
			$row = $q->result_array();			
			$data = $row[0];
		}
		$q->free_result();
		return $data;
	}
	
	function get_empty_dates($periodid){		
		$period = $this->get_period_by_id($periodid);
		if($period==null)
			die('period id:'.$periodid.'not found');
		
		$sql = sprintf("select distinct(date) as date 
			from g9_volunteer 
			INNER JOIN g9_user ON g9_volunteer.g9_user_id = g9_user.id
			where g9_user.is_inactive <> 1 
			and	(date between '%s' and '%s') 
			order by date asc",
			$period['period_date'],$period['period_date_end']);
		$q = $this->db->query($sql);
		$n = $q->num_rows();
		//convert to time for faster comparison
		$filleddate = array();
		if($n>0)
		{
			foreach($q->result_array() as $row)
			{
				$filleddate[] = strtotime($row['date']);
			}
		}
		$q->free_result();
		
		//iterate from first date of a period to end of period
		$interval = new DateInterval('P1D'); // 1day
		$start = new DateTime($period['period_date']);
		$end = new DateTime($period['period_date_end']);
		$nfilleddate = sizeof($filleddate);
		$emptydates = array();
		while ($end->getTimestamp() >= $start->getTimestamp()) {
			$currtime = $start->getTimestamp();
			$found = FALSE;
			for($i=0;$i<$nfilleddate;$i++)
			{
				if($filleddate[$i]==$currtime){
					$found = TRUE;
				}
			}
			if($found==FALSE)
			{
				if(date('l',$start->getTimestamp())!='Monday')
					$emptydates[]= date('Y-m-d',$start->getTimestamp());
			}
			$start = $start->add($interval);
		}
		return $emptydates;

	}
	function get_missing_signup($periodid){
		$period = $this->get_period_by_id($periodid);
		if($period==null)
			die('period id:'.$periodid.'not found');
		$sql = sprintf("SELECT * 
						FROM g9_user 
						WHERE is_inactive <> 1 
						and id NOT IN (
						SELECT g9_user_id FROM g9_volunteer 
						WHERE date BETWEEN '%s' AND '%s')",
						$period['period_date'],$period['period_date_end']);
		$q = $this->db->query($sql);
		$n = $q->num_rows();		
		$missing = array();
		if($n>0)
		{
			foreach($q->result_array() as $row)
			{
				$missing[] = array(
					"username"=>$row['username'],
					"phone"=>$row['phone'],
					"nsignup"=>0
					);
			}
		}
		$q->free_result();
		$sql = sprintf("SELECT * 
			FROM g9_user 
			WHERE is_inactive <> 1 
			and id IN (
			SELECT g9_user_id FROM g9_volunteer 
			WHERE date BETWEEN '%s' AND '%s' GROUP BY g9_user_id
			HAVING count( g9_user_id ) =1)",$period['period_date'],$period['period_date_end']);
		$q = $this->db->query($sql);
		$n = $q->num_rows();				
		if($n>0)
		{
			foreach($q->result_array() as $row)
			{
				$missing[] = array(
					"username"=>$row['username'],
					"phone"=>$row['phone'],
					"nsignup"=>1
					);
			}
		}
		$q->free_result();
		return $missing;
	}
}
?>
