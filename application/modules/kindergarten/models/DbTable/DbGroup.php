<?php

class Kindergarten_Model_DbTable_DbGroup extends Zend_Db_Table_Abstract
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
	public function getGroupKindergarten(){
		$db = $this->getAdapter();
		$sql="SELECT id,group_code as name FROM rms_group WHERE status = 1 AND is_use = 0 and degree IN(1) ";
		return $db->fetchAll($sql);
	}
	
	public function getGroupToEdit(){
		$db = $this->getAdapter();
		$sql="SELECT id,group_code as name FROM rms_group WHERE status = 1 AND is_use = 1 ";
		return $db->fetchAll($sql);
	}
	
	public function addGroup($_data){
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
		$this->_name='rms_group';
		return $this->insert($arr);
		 
	}
	public function getGroupById($id){
		$db = $this->getAdapter();
		$sql = "
		SELECT group_id,stu_id,status FROM rms_group_detail_student WHERE group_id=".$id;
		return $db->fetchRow($sql);
	
	}
	public function getStudentGroup($id){
		$db = $this->getAdapter();
		$sql = "
			SELECT `group_id`,stu_id,(SELECT `stu_code` FROM `rms_student`WHERE `stu_id`=`rms_group_detail_student`.`stu_id`)AS code,
			(SELECT `stu_enname` FROM `rms_student`WHERE `stu_id`=`rms_group_detail_student`.`stu_id`)AS en_name, 
			(SELECT `stu_khname` FROM `rms_student`WHERE `stu_id`=`rms_group_detail_student`.`stu_id`)AS kh_name
			FROM `rms_group_detail_student` WHERE `status`=1 AND`group_id`=".$id;
		return $db->fetchAll($sql);
		
	}
	
	public function editStudentGroup($_data,$id){
		//print_r($_data);exit();
		$db = $this->getAdapter();
		$rr = $this->getStudentGroup($id);
		$this->_name='rms_student';
		if(!empty($rr)){
			foreach($rr as $row){
				$data=array(
						'is_setgroup' => 0,
				);
				$where='stu_id = '.$row['stu_id'];
				$this->update($data, $where);
		    }
		}else{
			
		    }
		
		$where = $this->getAdapter()->quoteInto("group_id=?", $id);
		$this->_name='rms_group_detail_student';
		$this->delete($where);
		
		$a = $_data['public-methods'];
		foreach ($a as $rs){
			$arr = array(
					'user_id'=>$this->getUserId(),
					'group_id'=>$_data['group'],
					'stu_id'=>$rs,
					'status'=>$_data['status'],
					'date'=>date('Y-m-d')
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
	public function addStudentGroup($_data){
		//print_r($_data);exit();
		$db = $this->getAdapter();
		$a = $_data['public-methods'];
		foreach ($a as $rs){
			$arr = array(
					'user_id'=>$this->getUserId(),
					'group_id'=>$_data['group'],
					'stu_id'=>$rs,
					'status'=>$_data['status'],
					'date'=>date('Y-m-d')
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
		$this->_name = 'rms_group';
		$data_gro = array(
				'is_use'=> 1,
		);
		$where = 'id = '.$_data['group'];
		$this->update($data_gro, $where);

	}
	public function getGroupDetail(){
		$db = $this->getAdapter();
		$sql = 'SELECT
		`g`.`id`,
		`g`.`group_code`    AS `group_code`,g.academic_year,
		
		
		`g`.`semester` AS `semester`,
		
		(SELECT kh_name
		 FROM `rms_dept`
		 WHERE (`rms_dept`.`dept_id`=`g`.`degree`)),
		(SELECT major_khname
		 FROM `rms_major`
		 WHERE (`rms_major`.`major_id`=`g`.`grade`)),
		(SELECT	`rms_view`.`name_en`
		FROM `rms_view`
		WHERE ((`rms_view`.`type` = 4)
		AND (`rms_view`.`key_code` = `g`.`session`))
		LIMIT 1) AS `session`,
		(SELECT
		`r`.`room_name`
		FROM `rms_room` `r`
		WHERE (`r`.`room_id` = `g`.`room_id`)) AS `room_name`,
		`g`.`start_date`,
		`g`.`expired_date`,
		`g`.`note`,
		(SELECT
		`rms_view`.`name_en`
		FROM `rms_view`
		WHERE ((`rms_view`.`type` = 1)
		AND (`rms_view`.`key_code` = `g`.`status`))
		LIMIT 1) AS `status`,
		(SELECT COUNT(`stu_id`) FROM `rms_group_detail_student` WHERE `group_id`=`g`.`id`)AS Num_Student
		FROM rms_group as g where g.degree IN (1)
		 ORDER BY `g`.`id` DESC ';	
		
		return $db->fetchAll($sql);
	}
}

