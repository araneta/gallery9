<?php
class MUser extends CI_Model
{
	function __construct()
	{			
		parent::__construct();
	}
	function verifyUser($u,$pw)
	{
		$sql = sprintf("select * from g9_user where username = '%s'",db_clean($u));
		$q = $this->db->query($sql);
		if($q->num_rows()>0)
		{
			$row = $q->row_array();
			
			if(md5($pw)==$row['password'])
			{
				$_SESSION['userid'] = $row['id'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['is_admin'] = $row['is_admin'];
			}
		}		
	}
	function username_exists($username){
		$sql = sprintf("select id from g9_user where username = '%s'",db_clean($username));
		$q = $this->db->query($sql);
		if($q->num_rows()==0)
		{
			return false;
		}
		return true;
	}
	function get_user($username){
		$sql = sprintf("select * from g9_user where username = '%s'",db_clean($username));
		$q = $this->db->query($sql);
		if($q->num_rows()==0)
		{
			return null;
		}
		$rows = $q->result_array();
		return $rows[0];		
	}
	function list_user(){
		$sql = sprintf("select * from g9_user where is_admin<>1 and is_inactive <> 1");
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
	function get_user_pass(){
		$sql = sprintf("select password 
		from g9_user 
		where  is_admin=0 and password <> '' limit 0,1");
		$q = $this->db->query($sql);
		if($q->num_rows()==0)
		{
			return null;
		}
		$rows = $q->result_array();
		return $rows[0]['password'];	
	}
	function add($user){
		date_default_timezone_set('UTC');
		$olduser = $this->get_user($user['username']);
		if($olduser==null)
		{
			$data =array(
				'username' => db_clean($user['username']),
				'phone'=>db_clean($user['phone']),
				'email'=>db_clean($user['email']),
				'created_date'=> gmdate("Y-m-d\TH:i:s\Z"),
				'password'=>$this->get_user_pass()
			);
			$this->db->insert('g9_user',$data);
		}else
		{
			//reactivate user
			if($olduser['is_inactive']==1){
				$data =array(
					'is_inactive' => 0,
					'phone'=>db_clean($user['phone']),
					'email'=>db_clean($user['email'])
				);
				$this->db->where('id',id_clean($olduser['id']));
				$this->db->update('g9_user',$data);
			}
		}
	}
	function change_all_user_pass($password){
		$data = array('password'=>md5($password));
		$this->db->where('is_admin',0);
		$this->db->update('g9_user',$data);		
		
	}
	function update($user){
		$data =array(
			//'username' => db_clean($user['username']),
			'phone'=>db_clean($user['phone']),
			'email'=>db_clean($user['email'])						
		);
		$this->db->where('id',id_clean($user['id']));
		$this->db->update('g9_user',$data);
	}
	/*admin*/
	function admin_info(){
		$sql = sprintf("select * from g9_user where is_admin=1");
		$q = $this->db->query($sql);
		$n = $q->num_rows();
		$data = null;
		if($n>0)
		{			
			$data = $q->result_array();			
			$data = $data[0];
		}
		$q->free_result();
		return $data;
	}
	function update_admin_info($info){
		$data =array(
			//'username' => db_clean($info['username']),
			'phone'=>db_clean($info['phone']),			
			'email'=>db_clean($info['email'])
		);
		if(!empty($info['password']))
			$data['password'] = md5(db_clean($info['password']));
		$this->db->where('is_admin',1);
		$this->db->update('g9_user',$data);
	}
	function delete($iduser){
		date_default_timezone_set('UTC');
		$this->db->trans_start();
		//delete future signup
		
		$this->db->delete('g9_volunteer',
			array('g9_user_id'=>$iduser,'date >'=>gmdate("Y-m-d\TH:i:s\Z"))
		);		
		$data =array(
			'is_inactive' => 1			
		);
		$this->db->where('id',id_clean($iduser));
		$this->db->update('g9_user',$data);
		$this->db->trans_complete();
	}
	/*user*/
	function user_info($userid){
		$sql = sprintf("select * from g9_user where id=%d",$userid);
		$q = $this->db->query($sql);
		$n = $q->num_rows();
		$data = null;
		if($n>0)
		{			
			$data = $q->result_array();			
			$data = $data[0];
		}
		$q->free_result();
		return $data;
	}
	function update_user_info($info){
		$data =array(
			//'username' => db_clean($info['username']),
			'phone'=>db_clean($info['phone']),			
			'email'=>db_clean($info['email'])
		);		
		$this->db->where('id',$info['userid']);
		$this->db->update('g9_user',$data);
	}
}
