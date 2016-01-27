<?php

class Foundation_Model_DbTable_DbStudentDrop extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'rms_student_drop';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	}
	
	public function getAllStudentID(){
		$_db = $this->getAdapter();
		$sql = "SELECT stu_id,stu_code,stu_khname,sex FROM `rms_student` where status = 1 ";
		$orderby = " ORDER BY stu_code ";
		return $_db->fetchAll($sql.$orderby);		
	}
	
	
	
	public function getAllStudentDrop(){
		$_db = $this->getAdapter();
		$sql = "SELECT id,(SELECT stu_khname FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id`) AS kh_name,
		(SELECT stu_enname FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id`) AS en_name,
		(SELECT name_kh FROM `rms_view` WHERE `rms_view`.`type`=2 and `rms_view`.`key_code`=(SELECT sex FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id` limit 1))AS sex,
		(SELECT name_kh FROM `rms_view` WHERE `rms_view`.`type`=5 and `rms_view`.`key_code`=`rms_student_drop`.`type`) as type,
		date,(select name_kh from `rms_view` where `rms_view`.`type`=6 and `rms_view`.`key_code`=`rms_student_drop`.`status`)AS status
		 from `rms_student_drop` ";
		return $_db->fetchAll($sql);
	}
	public function getStudentDropById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_student_drop WHERE id =".$id;
		return $db->fetchRow($sql);
	}
	public function addStudentDrop($_data){
			try{	
				$_db= $this->getAdapter();
				$_arr= array(
						'user_id'=>$this->getUserId(),
						'stu_id'=>$_data['studentid'],
						'type'=>$_data['type'],
						'status'=>$_data['status'],
						'date'=>$_data['datestop'],
						'note'=>$_data['reason']
						);
				$id = $this->insert($_arr);
			
			}catch(Exception $e){
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
	}
	public function updateStudentDrop($_data){
		
		try{	
			$_arr=array(
					'user_id'=>$this->getUserId(),
					'stu_id'=>$_data['studentid'],
					'type'=>$_data['type'],
					'date'=>$_data['datestop'],
					'note'=>$_data['reason'],
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
}
