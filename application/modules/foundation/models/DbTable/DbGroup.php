<?php

class Foundation_Model_DbTable_DbGroup extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'rms_group_detail_student';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
	public function getRoom(){
		$db = $this->getAdapter();
		$sql = "SELECT room_id,room_name FROM rms_room WHERE is_active = 1";
		return $db->fetchAll($sql);
	}
	public function getGroup(){
		$db = $this->getAdapter();
		$sql="SELECT id,group_code FROM rms_group WHERE status = 1 ";
		return $db->fetchAll($sql);
	}
	public function addGroup($_data){
		$this->_name='rms_group';
		$db = $this->getAdapter();
		$arr = array(
				'group_code' => $_data['group_code'],
				'room_id' => $_data['room'],
				'from_academic' => $_data['start_year'],
				'to_academic' => $_data['end_year'],
				'semester' => $_data['semester'],
				'session' => $_data['session_group'],
				'degree' => $_data['degree_group'],
				'grade' => $_data['grade_group'],
				'amount_month' => $_data['amountmonth'],
				'start_date' => $_data['start_date'],
				'expired_date'=>$_data['end_date'],
				'date' => date("Y-m-d"),
				'status'   => 1,
				'note'   => $_data['note'],
				'user_id'	  => $this->getUserId()
				
				);
		return $this->insert($arr);
	}
	public function addStudentGroup($_data){
		$db = $this->getAdapter();
		$a = $_data['public-methods'];
		foreach ($a as $rs){
			$arr = array(
					'user_id'=>$this->getUserId(),
					'group_id'=>$_data['group'],
					'stu_id'=>$rs,
					'status'=>1,
					'date'=>date('d-m-Y')
			);
			$this->_name='rms_group_detail_student';
			$this->insert($arr);
			
			$this->_name='rms_student';
			$data=array(
					'is_setgroup'=> 1,
					);
			$where='stu_id = '.$rs;
			$this->update($data, $where);
		}
		
	}
}

