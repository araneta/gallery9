<?php
class MCalendar extends CI_Model {
		
	function __construct()
	{			
		parent::__construct();
		
	}	
	/*admin*/
	function get_calendar_data($year, $month) {		
		$query = $this->db->select('id, period_date')->from('g9_period')
			->like('period_date', "$year-$month", 'after')->get();
			
		$cal_data = array();
		//add period data
		foreach ($query->result() as $row) {
			$cal_data[intval(substr($row->period_date,8,2))] = '<div idperiod="'.$row->id.'" class="flagperiod"><img src="'.base_url() .'/images/flag.png" /><span>New Period</span></div>';
		}
		//add volunteer data
		$query = $this->db->select('g9_volunteer.id,g9_volunteer.date,g9_user.username,g9_volunteer.note')
			->from('g9_volunteer')
			->join('g9_user','g9_user.id = g9_volunteer.g9_user_id')
			->like('date',"$year-$month",'after')->get();
		foreach($query->result() as $row){
			$prevcont = '';
			$day = intval(substr($row->date,8,2));
			if(!empty($cal_data[$day])){
				$prevcont =$cal_data[$day];
			}
			$link = '';
			//if thereis a note show [n]
			if(!empty($row->note))				
				$link = '[n]';
			//$xdate = $year.'-'.$month.'-'.$day;
			$cal_data[$day] = $prevcont. '<div class="volunteer" idvolunteer="'.$row->id.'">			
				<a href="'.base_url() .'admin/calendar/unregister?idvolunteer='.$row->id.'">'.$row->username.'</a>
				<a class="notelink" >'.$link.'</a></div>';
		}
		
		//monday is closed
		$endday = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($d=1;$d<=$endday;$d++){						
			if(date_format(date_create("$year-$month-$d"), 'l')=="Monday"){
				$prevcont = '';
				if(!empty($cal_data[$d])){
					$prevcont =$cal_data[$d];
				}
				$cal_data[$d] = $prevcont. '<div class="closed">Closed</div>';
			}
		}
		//add meeting data
		$query = $this->db->select('id,date,status')
			->from('g9_meeting')			
			->like('date',"$year-$month",'after')->get();
		foreach($query->result() as $row){
			$prevcont = '';
			$day = intval(substr($row->date,8,2));
			if(!empty($cal_data[$day])){
				$prevcont =$cal_data[$day];
				$prevcont = str_replace('<div class="closed">Closed</div>','',$prevcont);
			}
			
			if($row->status=='GalleryMeeting')
				$class = 'meetingopen';
			else
				$class = 'meetingclosed';
			$cal_data[$day] = $prevcont. '<div class="meeting" idmeeting="'.$row->id.'"><a class="'.$class.'" onclick="setMeeting('.$day.');return false;">'.$row->status.'</a></div>';
		}
		return $cal_data;
		
	}
	
	function add_calendar_data($date) {
		
		if ($this->db->select('period_date')->from('g9_period')
				->where('period_date', $date)->count_all_results()) {
			
			$this->db->where('period_date', $date)
				->update('g9_period', array(
				'period_date' => $date
			));
			
		} else {
		
			$this->db->trans_start();
			
			$sql = sprintf("select * from g9_period 
				where period_date < '%s' 
				order by period_date desc 
				limit 0,1",$date);						
			
			$query = $this->db->query($sql);			
			$n = $query->num_rows();
			if($n==1){								
				$rows = $query->result_array();
				$prev_period = $rows[0];
				$end = strtotime(date("Y-m-d", strtotime($date)) . " -1 day");			
				$this->db->where('id', $prev_period['id'])
					->update('g9_period', array(
					'period_date_end' => date("Y-m-d",$end)
				));
			}			
			$end_this_period = null;
			$sql = sprintf("select * from g9_period 
				where period_date > '%s' 
				order by period_date asc 
				limit 0,1",$date);									
			$query = $this->db->query($sql);
			$n = $query->num_rows();
			if($n==1){				
				$rows = $query->result_array();
				$next_period = $rows[0];
				$end_this_period = strtotime(date("Y-m-d", strtotime($next_period['period_date'])) . " -1 day");											
			}			
			$this->db->insert('g9_period', 
				array(
					'period_date' => $date,
					'period_date_end'=>$end_this_period!=null?date("Y-m-d",$end_this_period):null
				));
			$this->db->trans_complete();
		}
		
	}	
	function delete_calendar_data($date) {
		
		if ($this->db->select('period_date')->from('g9_period')
				->where('period_date', $date)->count_all_results()) {
			
			$this->db->where('period_date', $date)
				->delete('g9_period');
			
			$sql = sprintf("select * from g9_period 
				where period_date < '%s' 
				order by period_date desc 
				limit 0,1",$date);						
			
			$query = $this->db->query($sql);			
			$n = $query->num_rows();
			if($n==1){								
				$rows = $query->result_array();
				$prev_period = $rows[0];
					
				$sql = sprintf("select * from g9_period 
				where period_date > '%s' 
				order by period_date asc 
				limit 0,1",$date);									
				$query = $this->db->query($sql);
				$n = $query->num_rows();
				$end_this_period = null;
				if($n==1){				
					$rows = $query->result_array();
					$next_period = $rows[0];
					$end_this_period = strtotime(date("Y-m-d", strtotime($next_period['period_date'])) . " -1 day");											
				}			
				$this->db->where('id', $prev_period['id'])
						->update('g9_period', array(
						'period_date_end'=>$end_this_period!=null?date("Y-m-d",$end_this_period):null
					));			
			}	
			
		} 		
	}	
	function set_meeting($date){
		if(date_format(date_create($date), 'l')!="Monday")
			die('this '.$date.'is not monday');
			
		$sql = sprintf("select * from g9_meeting 
				where date = '%s' 				
				limit 0,1",$date);						
			
		$query = $this->db->query($sql);			
		$n = $query->num_rows();
		if($n==1){					
			$rows = $query->result_array();
			$meeting = $rows[0];
			
			if($meeting['status']=='GalleryMeeting'){				
				$data =array('status'=>'Closed');				
			}else
			{
				$data =array('status'=>'GalleryMeeting');				
			}
			$this->db->where('id', $meeting['id'])->update('g9_meeting', $data);		
		}else
		{
			$data = array('date'=>$date,'status'=>'GalleryMeeting');
			$this->db->insert('g9_meeting',$data);
		}
	}
	/*end admin region*/	
	/*user	region*/
	function get_calendar_data_user($year, $month,$username) {
		
		$query = $this->db->select('id, period_date')->from('g9_period')
			->like('period_date', "$year-$month", 'after')->get();
			
		$cal_data = array();
		
		foreach ($query->result() as $row) {
			$cal_data[intval(substr($row->period_date,8,2))] = '<div idperiod="'.$row->id.'"><img src="'.base_url() .'/images/flag.png" /><span>New Period</span></div>';
		}
		$query = $this->db->select('g9_volunteer.id,g9_volunteer.date,g9_volunteer.note,g9_user.username')
			->from('g9_volunteer')
			->join('g9_user','g9_user.id = g9_volunteer.g9_user_id')
			->like('date',"$year-$month",'after')->get();
		foreach($query->result() as $row){
			$prevcont = '';
			if(!empty($cal_data[intval(substr($row->date,8,2))])){
				$prevcont =$cal_data[intval(substr($row->date,8,2))];
			}
			
			$link = '';
			//if thereis a note show [n]
			if(!empty($row->note))				
				$link = '[n]';
			//there is no note then show add link [+]
			if($username==$row->username && empty($row->note))
				$link = '[+]';
			$cal_data[intval(substr($row->date,8,2))] = $prevcont. '<div class="volunteer" idvolunteer="'.$row->id.'"><a href="#">'.$row->username.'</a> <a class="notelink" >'.$link.'</a></div>';
		}
		//monday is closed
		$endday = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($d=1;$d<=$endday;$d++){						
			if(date_format(date_create("$year-$month-$d"), 'l')=="Monday"){
				$prevcont = '';
				if(!empty($cal_data[$d])){
					$prevcont =$cal_data[$d];
				}
				$cal_data[$d] = $prevcont. '<div class="closed">Closed</div>';
			}
		}
		//add meeting data
		$query = $this->db->select('id,date,status')
			->from('g9_meeting')			
			->like('date',"$year-$month",'after')->get();
		foreach($query->result() as $row){
			$prevcont = '';
			$day = intval(substr($row->date,8,2));
			if(!empty($cal_data[$day])){
				$prevcont =$cal_data[$day];
				$prevcont = str_replace('<div class="closed">Closed</div>','',$prevcont);
			}
			if($row->status=='GalleryMeeting')
				$class = 'meetingopen';
			else
				$class = 'meetingclosed';
			
			$cal_data[$day] = $prevcont. '<div class="meeting" idmeeting="'.$row->id.'"><a class="'.$class.'" href="#">'.$row->status.'</a></div>';
		}
		return $cal_data;
		
	}	
}
