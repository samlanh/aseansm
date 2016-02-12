<?php

class Global_Model_DbTable_DbGroup extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_group';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
    
	public function AddNewGroup($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$_arr=array(
					'group_code' => $_data['group_code'],
					'room_id' => $_data['room'],
					'from_academic' => $_data['from_year'],
					'to_academic' => $_data['to_year'],
					'semester' => $_data['semester'],
					'session' => $_data['session'],
					'degree' => $_data['degree'],
					'grade' => $_data['grade'],
					'amount_month' => $_data['amountmonth'],
// 					'is_check' => $_data['degree'],
					'start_date' => $_data['start_date'],
// 					'academic_year' => $_data['academic'],
					'expired_date'=>$_data['end_date'],
					'date' => date("Y-m-d"),
					'status'   => $_data['status'],
					'note'   => $_data['note'],
					'user_id'	  => $this->getUserId()
			);
			
			$group_data = $this->insert($_arr);
			$this->_name='rms_group_subject_detail';
			$ids = explode(',', $_data['record_row']);
			foreach ($ids as $i){
				$arr = array(
// 						'group_id'=>$group_data['group_id'],
// 						'subject_id'=>$_data['subject_id'.$i],
						'status'   => $_data['status'],
						'note'   => $_data['note'.$i],
						'date' => date("Y-m-d"),
						'user_id'	  => $this->getUserId()
						
				);
				$this->insert($arr);
			}
			return $db->commit();
		}catch (Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	
	
	public function updateGroup($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$_arr=array(
					'group_code' => $_data['group_code'],
					'room_id' => $_data['room'],
					'from_academic' => $_data['from_year'],
					'to_academic' => $_data['to_year'],
					'semester' => $_data['semester'],
					'session' => $_data['session'],
					'degree' => $_data['degree'],
					'grade' => $_data['grade'],
					'amount_month' => $_data['amountmonth'],
					// 					'is_check' => $_data['degree'],
					'start_date' => $_data['start_date'],
					// 					'academic_year' => $_data['academic'],
					'expired_date'=>$_data['end_date'],
					'date' => date("Y-m-d"),
					'status'   => $_data['status'],
					'note'   => $_data['note'],
					'user_id'	  => $this->getUserId()
			);
			$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
			$this->update($_arr, $where);
			
			return $db->commit();
		}catch (Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	
	
	
	
	
	public function getGroupById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_group WHERE id = ".$db->quote($id);
		$sql.=" LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function getallSubjectTeacherById($teacher_id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM `rms_teacher_subject` WHERE teacher_id= ".$db->quote($teacher_id);
		return $db->fetchAll($sql);;
	}
	public function updateTeacher($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
		$_arr=array(
					'teacher_code' => $_data['code'],
					'teacher_name_en' => $_data['en_name'],
					'teacher_name_kh' => $_data['kh_name'],
					'sex' => $_data['sex'],
					'phone' => $_data['phone'],
					'dob' => $_data['dob'],
					'pob' => $_data['pob'],
					'address' => $_data['address'],
					'email' => $_data['email'],
					'degree' => $_data['degree'],
					//'photo' => $_data['kh_subject'],
					'note'=>$_data['note'],
					'date' => Zend_Date::now(),
					'status'   => $_data['status'],
					'user_id'	  => $this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("id=?", $_data["id"]);
		$this->update($_arr, $where);
		
		$this->_name='rms_teacher_subject';
		$ids = explode(',', $_data['record_row']);
		foreach ($ids as $i){
			$arr = array(
					'subject_id'=>$_data['subject_id'.$i],
					'teacher_id'=>$_data["id"],
					'status'   => $_data['status'.$i],
					'date' => Zend_Date::now(),
					'user_id'	  => $this->getUserId()
		
			);
			if(!empty($_data['subexist_id'.$i])){
				$where=$this->getAdapter()->quoteInto("id=?", $_data['subexist_id'.$i]);
				$this->update($arr, $where);
			}else{
				$this->insert($arr);
			}
		}
		return $db->commit();
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage();exit();
		}
	}
	
	function getAllGroup($search){
		$db = $this->getAdapter();
//   		$sql = ' SELECT * FROM `v_getallgroup` WHERE 1';
// 		$sql = ' SELECT group_code , CONCAT(from_academic,'-',to_academic) as year,semester,session,degree,grade,room_id,start_date,expired_date,note,status FROM `rms_group` WHERE 1';
		
		$sql = 'SELECT `g`.`id`,`g`.`group_code` AS `group_code`,CONCAT(`g`.`from_academic`," - ",
		`g`.`to_academic`) AS academic ,`g`.`semester` AS `semester`,
		(SELECT kh_name FROM `rms_dept` WHERE (`rms_dept`.`dept_id`=`g`.`degree`)) AS degree,
		(SELECT major_khname FROM `rms_major` WHERE (`rms_major`.`major_id`=`g`.`grade`))AS grade,
		(SELECT`rms_view`.`name_en`	FROM `rms_view`	WHERE ((`rms_view`.`type` = 4)
		AND (`rms_view`.`key_code` = `g`.`session`))LIMIT 1) AS `session`,
		(SELECT `r`.`room_name`	FROM `rms_room` `r`	WHERE (`r`.`room_id` = `g`.`room_id`)) AS `room_name`,
		`g`.`start_date`,`g`.`expired_date`,`g`.`note`,
		(SELECT `rms_view`.`name_en` FROM `rms_view` WHERE ((`rms_view`.`type` = 1)
		AND (`rms_view`.`key_code` = `g`.`status`)) LIMIT 1) AS `status`
		FROM `rms_group` `g`
		';	
		$where =' WHERE 1 ';
		$order =  ' ORDER BY `g`.`id` DESC ' ;
		if(empty($search)){
			return $db->fetchAll($sql.$order);
		}
		if(!empty($search['title'])){
		    $s_where = array();
			$s_search = addslashes(trim($search['title']));
			$s_where[] = " `g`.`group_code` LIKE '%{$s_search}%'";
			$s_where[] = " `g`.`semester` LIKE '%{$s_search}%'";
			$s_where[] = " (SELECT major_khname FROM `rms_major` WHERE (`rms_major`.`major_id`=`g`.`grade`)) LIKE '%{$s_search}%'";
			$s_where[] = " (SELECT `r`.`room_name`	FROM `rms_room` `r`	WHERE (`r`.`room_id` = `g`.`room_id`)) LIKE '%{$s_search}%'";
			$s_where[] = " (SELECT`rms_view`.`name_en`	FROM `rms_view`	WHERE ((`rms_view`.`type` = 4)
		AND (`rms_view`.`key_code` = `g`.`session`))LIMIT 1) LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		return $db->fetchAll($sql.$where.$order);
	}
	
	function getAllGrade($grade_id){
		$db = $this->getAdapter();
		$sql = "SELECT major_id As id,major_enname As name FROM rms_major WHERE dept_id=".$grade_id;
		$order=' ORDER BY id DESC';
		return $db->fetchAll($sql.$order);
	}
	
}

