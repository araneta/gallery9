<?php
class MVolunteer extends CI_Model {
		
	function __construct()
	{			
		parent::__construct();
		
	}	
	/*user*/	
	function check_registered_user($date){		
		$users = array();
		$sql = sprintf("select  g9_user.id, g9_user.username
			from g9_volunteer 
			inner join g9_user on g9_volunteer.g9_user_id = g9_user.id
			where 
			g9_user.is_inactive <> 1
			and g9_volunteer.date = '%s'
			",$date);
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
	function register_user($userid,$date){
		//monday is closed
		$curday = getdate(strtotime($date));
		if($curday['weekday']=='Monday'){
			return array("success"=>FALSE,"msg"=>"Sorry, Closed.","status"=>0);			
		}	
		//only allow present or future date
		if(strtotime($date)<=strtotime('-1 day'))
		{
			return array("success"=>FALSE,"msg"=>"Please select current or future date","status"=>0);
		}
		//register max two user on a day
		$sql = sprintf("select  g9_user.id, g9_user.username
			from g9_volunteer 
			inner join g9_user on g9_volunteer.g9_user_id = g9_user.id
			where 
			g9_user.is_inactive <> 1
			and g9_volunteer.date = '%s'
			",$date);
		$q = $this->db->query($sql);
		$n = $q->num_rows(); 
		if($n>0){			
			$data = array();
			$user_registered = FALSE;
			foreach($q->result_array() as $row)
			{
				$data[] = $row['username'];
				if($row['id']==$userid){
					$user_registered = TRUE;
					break;
				}
			}
			if($user_registered){
				return array("success"=>FALSE,"msg"=>"unreg();","status"=>1);
			}
			//register max 4 user on a day
			if($n>3){				
				return array("success"=>FALSE,"msg"=>"Sorry, ".implode(',',$data)." is already signed up for ".$date.".","status"=>0);
			}
		}
		//Allow users to sign up as many times as they want per period...don’t limit to 2 times any more
		//$ret = $this->check_num_register_in_period($userid,$date);
		//if($ret["num"]>1){
			//return array("success"=>FALSE,"msg"=>"Sorry,you are already signed up for 2x on this period","status"=>0);
		//}
		//Don’t let users sign up for a past period, only current and future.
		$period = $this->get_period_by_date($date);		
		if($period["num"]!=1){
			$period["success"] = FALSE;
			$period["status"] = 0;
			return $period;	
		}
		$period = $period['row'];
		//When users try to sign up for a period that doesn’t have an end flag, pop-up error message 	
		if($period['period_date_end']==null)
		{
			return array("success"=>FALSE,"msg"=>"This period is not yet available.","status"=>0);
		}
		if(strtotime('now')>strtotime($period['period_date_end']))
		{
			return array("success"=>FALSE,"msg"=>"Please sign up for current or future period","status"=>0);
		}
		
		$data = array(
				"date"=>$date,
				"g9_user_id"=>$userid
			);
		$this->db->insert('g9_volunteer',$data);	
		$ret = $this->check_num_register_in_period($userid,$date);		
		return array("success"=>TRUE,"msg"=>$ret['msg'],"status"=>0);
	}
	function get_period_by_date($date){
		$sql = sprintf("select * 
			from g9_period 
			where (period_date_end is not null and ('%s' between period_date and period_date_end))
			or (period_date_end is null and '%s' > period_date)",$date,$date);
		//echo $sql;
		$q = $this->db->query($sql);
		$n = $q->num_rows();
		if($n==0)
			return array('num'=>0,"msg"=>"This period is not yet available.");
		if($n>1)
			return array('num'=>0,"msg"=>"period more than 1");		
		
		$rows = $q->result_array();
		$q->free_result();
	
		return array('num'=>$n,"row"=>$rows[0]);
	}
	function check_num_register_in_period($userid,$registerdate){
		$period = $this->get_period_by_date($registerdate);		
		if($period["num"]!=1)
			return $period;	
		$period = $period['row'];
		$sql = sprintf("select * from g9_volunteer 
			where g9_user_id=%d 
			and date between '%s' and '%s'
			order by date asc
			",$userid,$period['period_date'],$period['period_date_end']);
		$q = $this->db->query($sql);
		$n = $q->num_rows();
		if($n>0)
		{
			$data = array();
			foreach($q->result_array() as $row)
			{
				$data[] = $row['date'];
			}
			if($n==1){
				return array('num'=>$n,"msg"=>"You signed up for ".$registerdate.". Please sign up one more time this period.");				
			}
			return array('num'=>$n,"msg"=>"You are signed up for ".implode(" and ",$data)." this period");
		}else{
			return array('num'=>0,"msg"=>"period not found2");
		}
		$q->free_result();
	}
	function unregister_user($userid,$idvolunteer){					
		$this->db->delete('g9_volunteer',array('id'=>$idvolunteer,'g9_user_id'=>$userid));		
		return array("success"=>TRUE,"msg"=>"Unregister successful");
	}
	function dates_volunteered($userid)
	{
		$sql=sprintf("select * 
			from g9_period 
			where g9_period.period_date_end >= '%s'
			order by period_date asc",date('Y-m-d'));
		$q = $this->db->query($sql);
		$n = $q->num_rows();
		$periods = array();
		if($n>0)
		{
			foreach($q->result_array() as $row)
			{				
				//get dates
				$sql2= sprintf("select * from g9_volunteer 
					where g9_user_id = %d and date between '%s' and '%s' 
					order by date asc",$userid,$row['period_date'],$row['period_date_end']);
				$q2 = $this->db->query($sql2);
				$n2 = $q2->num_rows();
				$dates = array();
				if($n2>0)
				{
					foreach($q2->result_array() as $row2)
					{
						$dates[] = $row2;
					}
				}
				$row["dates"] = $dates;
				$periods[] = $row;
				$q2->free_result();
			}
		}
		$q->free_result();
		return $periods;
	}
	function save_note($idvolunteer,$text,$userid){
		$criteria =array('g9_user_id'=> id_clean($userid),'id'=>id_clean($idvolunteer));
		$data = array('note'=>db_clean($text));
		
		$this->db->where($criteria);
		$this->db->update('g9_volunteer',$data);	
		//if there is no changes, it will return 0
		//if($this->db->affected_rows()>0)
			return array("success"=>TRUE,"msg"=>"Note Saved");
		//return array("success"=>FALSE,"msg"=>"Error while saving Note");
	}
	function get_note($idvolunteer){		
		$criteria =array('id'=>id_clean($idvolunteer));
		$query = $this->db->select('note,g9_user_id')->from('g9_volunteer')
			->where($criteria)->get();
		$n = $query->num_rows();
		if($n>0){
			$rows = $query->result();	
			return $rows[0];
		}
		return null;
		
	}
	/*admin region*/
	function admin_unregister_user($idvolunteer){		
		$this->db->delete('g9_volunteer',array('id'=>$idvolunteer));
		return array("success"=>TRUE,"msg"=>"Unregister successful");
	}
	
}
