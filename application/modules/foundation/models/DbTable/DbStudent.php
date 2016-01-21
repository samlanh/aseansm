<?php

class Foundation_Model_DbTable_DbStudent extends Zend_Db_Table_Abstract
{
	public function getAllStudent(){
		$_db = $this->getAdapter();
		$sql = "SELECT stu_id,stu_enname,stu_khname,sex,batch,semester FROM rms_student ";
		$orderby = " ORDER BY stu_enname ";
		return $_db->fetchAll($sql.$orderby);
	}
	public function getStudentById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_student WHERE stu_id = ".$id;
		return $db->fetchRow($sql);
	}
	public function updateStudent($_data){
		$_arr=array(
				'stu_type'=>$_data['add_title'],
				'stu_code'=>$_data['title'],
				'fm_user'=>$_data['desc'],
				'stu_card'=>Zend_Date::now(),
				'stu_enname'=>$_data['status'],
				'stu_khname'=>$_data['status'],
				'sex'=>$_data['status'],
				'dob'=>$_data['status'],
				'pob'=>$_data['status'],
				'degree'=>$_data['status'],
				'faculty_id'=>$_data['status'],
				'major_id'=>$_data['status'],
				'batch'=>$_data['status'],
				'session'=>$_data['status'],
				'year'=>$_data['status'],
				'semester'=>$_data['status'],
				'nation'=>$_data['status'],
				'address'=>$_data['status'],
				'phone'=>$_data['status'],
				'email'=>$_data['status'],
				'father_name'=>$_data['status'],
				'father_nation'=>$_data['status'],
				'father_job'=>$_data['status'],
				'father_phone'=>$_data['status'],
				'mother_name'=>$_data['status'],
				'mother_nation'=>$_data['status'],
				'mother_phone'=>$_data['status'],
				'situation'=>$_data['status'],
				'composition'=>$_data['status'],
				'school_location'=>$_data['status'],
				'from_school'=>$_data['status'],
				'finish_bacc'=>$_data['status'],
				'certificate_code'=>$_data['status'],
				'bacc_score'=>$_data['status'],
				'mention'=>$_data['status'],
				'student_add'=>$_data['status'],
				'remark'=>$_data['status'],
				'status'=>$_data['status'],
				'acc_stu_new'=>$_data['status'],
				'is_stu_new'=>$_data['status'],
				'is_hold'=>$_data['status'],
				'is_subspend'=>$_data['status'],
				'is_exam'=>$_data['status'],
				'create_date'=>$_data['status'],
				'modify_date'=>$_data['status'],
				'scholar_id'=>$_data['status'],
				);
		$where=$this->getAdapter()->quoteInto("stu_id=?", $_data["id"]);
		$this->update($_arr, $where);
	}
}

