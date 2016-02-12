<?php

class Foundation_Model_DbTable_DbStudentChangeGroup extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'rms_student_change_group';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	}
	
	public function getAllStudentID(){
		$_db = $this->getAdapter();
		$sql = "SELECT stu_id,stu_code FROM `rms_student` where status = 1 ";
		$orderby = " ORDER BY stu_code ";
		return $_db->fetchAll($sql.$orderby);		
	}
	
	
	public function getAllStudentChangeGroup(){
		$db = $this->getAdapter();
		$sql = "SELECT group_code,id FROM `rms_group` where status = 1 ";
// 		$orderby = " ORDER BY stu_code ";
		return $db->fetchAll($sql);
	}
	
	
	
	public function selectAllStudentChangeGroup(){
		$_db = $this->getAdapter();
		$sql = "SELECT id,(SELECT stu_code FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_change_group`.`stu_id`) AS code,
		(SELECT stu_khname FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_change_group`.`stu_id`) AS kh_name,
		(SELECT stu_enname FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_change_group`.`stu_id`) AS en_name,
		(SELECT name_kh FROM `rms_view` WHERE `rms_view`.`type`=2 and `rms_view`.`key_code`=(SELECT sex FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_change_group`.`stu_id` limit 1))AS sex,
		(select group_code from rms_group where rms_group.id = rms_student_change_group.from_group limit 1)AS from_group,
		(select group_code from rms_group where rms_group.id = rms_student_change_group.to_group limit 1)AS to_group,
		moving_date,note from `rms_student_change_group` ";
		$order_by=" order by id DESC";
		return $_db->fetchAll($sql.$order_by);
// 		(select name_kh from `rms_view` where `rms_view`.`type`=6 and `rms_view`.`key_code`=`rms_student_change_group`.`status`)AS status
	}
	
	public function getAllStudentChangeGroupById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_student_change_group WHERE id =".$id;
		return $db->fetchRow($sql);
	}
	
	
	public function addStudentChangeGroup($_data){
			try{	
				$_db= $this->getAdapter();
				$_arr= array(
						'user_id'=>$this->getUserId(),
						'stu_id'=>$_data['studentid'],
						'from_group'=>$_data['from_group'],
						'to_group'=>$_data['to_group'],
						'moving_date'=>$_data['moving_date'],
						'note'=>$_data['note'],
						'status'=>$_data['status']
						);
				$id = $this->insert($_arr);
			
			}catch(Exception $e){
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
	}
	public function updateStudentChangeGroup($_data){
		
		try{	
			$_arr=array(
						'user_id'=>$this->getUserId(),
						'stu_id'=>$_data['studentid'],
						'from_group'=>$_data['from_group'],
						'to_group'=>$_data['to_group'],
						'moving_date'=>$_data['moving_date'],
						'note'=>$_data['note'],
						'status'=>$_data['status'],
					);
			$where=$this->getAdapter()->quoteInto("id=?", $_data["id"]);
			$this->update($_arr, $where);
			
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	function getAllGrade($grade_id){
		$db = $this->getAdapter();
		$sql = "SELECT major_id As id,major_enname As name FROM rms_major WHERE dept_id=".$grade_id;
		$order=' ORDER BY id DESC';
		return $db->fetchAll($sql.$order);
	}
	
	
	
	function getStudentChangeGroup1ById($id){
		$db = $this->getAdapter();
		$sql = "SELECT from_academic,to_academic,start_date,expired_date,
		(select en_name from `rms_dept` where `rms_dept`.`dept_id`=`rms_group`.`degree`)AS degree ,
		(select major_enname from `rms_major` where `rms_major`.`major_id`=`rms_group`.`grade`)AS grade,
		(select name_en from `rms_view` where `rms_view`.`type`=4 and `rms_view`.`key_code`=`rms_group`.`session`)AS session
		FROM `rms_group` WHERE id=$id LIMIT 1 ";
		return $db->fetchRow($sql);
	}
	
	
	
	
	
	
}

