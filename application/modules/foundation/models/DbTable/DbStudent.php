<?php

class Foundation_Model_DbTable_DbStudent extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'rms_student';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
	public function getAllStudent(){
		$_db = $this->getAdapter();
		$sql = "SELECT stu_id,stu_enname,stu_khname,
		(SELECT name_kh FROM `rms_view` WHERE type=2 AND key_code = sex) as sex
		,(SELECT `major_enname` FROM `rms_major` WHERE `major_id`=grade),nationality,dob,tel,email ,
		(SELECT name_kh FROM `rms_view` WHERE type=1 AND key_code = sex) as status
		FROM rms_student where status = 1 ";
		$orderby = " ORDER BY stu_enname ";
		return $_db->fetchAll($sql.$orderby);
	}
	public function getStudentById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_student WHERE stu_id =".$id;
		return $db->fetchRow($sql);
	}
	public function addStudent($_data){
		$_db= $this->getAdapter();
		$_arr= array(
				'user_id'=>$this->getUserId(),
				'stu_enname'=>$_data['name_en'],
				'stu_khname'=>$_data['name_kh'],
				'sex'=>$_data['sex'],
				'nationality'=>$_data['studen_national'],
				'dob'=>$_data['date_of_birth'],
				'tel'=>$_data['st_phone'],
				'email'=>$_data['st_email'],
				'address'=>$_data['place_of_birth'],
				'home_num'=>$_data['home_note'],
				'street_num'=>$_data['way_note'],
				'village_name'=>$_data['village_note'],
				'commune_name'=>$_data['commun_note'],
				'district_name'=>$_data['distric_note'],
				'province_id'=>$_data['student_province'],
				'stu_code'=>$_data['student_id'],
				'degree'=>$_data['degree'],
				'grade'=>$_data['grade'],
				'lang_level'=>$_data['level'],
				'session'=>$_data['session'],
				//'school_location'=>$_data['school_location'],
				'father_enname'=>$_data['fa_name_en'],
				'father_khname'=>$_data['fa_name_kh'],
				'father_old'=>$_data['fa_age'],
				'father_nation'=>$_data['fa_national'],
				'father_job'=>$_data['fa_job'],
				'father_phone'=>$_data['fa_phone'],
				'mother_khname'=>$_data['mom_name_kh'],
				'mother_enname'=>$_data['mom_name_en'],
				'mother_old'=>$_data['age'],
				'mother_nation'=>$_data['mom_nation'],
				'mother_job'=>$_data['mom_job'],
				'mother_phone'=>$_data['mon_phone'],
				'guardian_enname'=>$_data['guardian_name_en'],
				'guardian_khname'=>$_data['guardian_name_kh'],
				'guardian_old'=>$_data['guardian_age'],
				'guardian_nation'=>$_data['guardian_national'],
				'guardian_document'=>$_data['guardian_identity'],
				'guardian_job'=>$_data['guardian_job'],
				'guardian_tel'=>$_data['guardian_phone'],
				'guardian_email'=>$_data['guardian_email'],
				'status'=>$_data['status'],
				'remark'=>$_data['remark']
				);
		$this->insert($_arr);
	}
	public function updateStudent($_data){
		$_arr=array(
			//	'stu_type'=>$_data[1],
				'user_id'=>$this->getUserId(),
				'stu_enname'=>$_data['name_en'],
				'stu_khname'=>$_data['name_kh'],
				'sex'=>$_data['sex'],
				'nationality'=>$_data['studen_national'],
				'dob'=>$_data['date_of_birth'],
				'tel'=>$_data['st_phone'],
				'email'=>$_data['st_email'],
				'address'=>$_data['place_of_birth'],
				'home_num'=>$_data['home_note'],
				'street_num'=>$_data['way_note'],
				'village_name'=>$_data['village_note'],
				'commune_name'=>$_data['commun_note'],
				'district_name'=>$_data['distric_note'],
				'province_id'=>$_data['student_province'],
				'stu_code'=>$_data['student_id'],
				'degree'=>$_data['degree'],
				'grade'=>$_data['grade'],
				'lang_level'=>$_data['level'],
				'session'=>$_data['session'],
				//'school_location'=>$_data['school_location'],
				'father_enname'=>$_data['fa_name_en'],
				'father_khname'=>$_data['fa_name_kh'],
				'father_old'=>$_data['fa_age'],
				'father_nation'=>$_data['fa_national'],					
				'father_job'=>$_data['fa_job'],					
				'father_phone'=>$_data['fa_phone'],
				'mother_khname'=>$_data['mom_name_kh'],
				'mother_enname'=>$_data['mom_name_en'],
				'mother_old'=>$_data['age'],
				'mother_nation'=>$_data['mom_nation'],
				'mother_job'=>$_data['mom_job'],
				'mother_phone'=>$_data['mon_phone'],
				'guardian_enname'=>$_data['guardian_name_en'],
				'guardian_khname'=>$_data['guardian_name_kh'],
				'guardian_old'=>$_data['guardian_age'],
				'guardian_nation'=>$_data['guardian_national'],
				'guardian_document'=>$_data['guardian_identity'],
				'guardian_job'=>$_data['guardian_job'],
				'guardian_tel'=>$_data['guardian_phone'],
				'guardian_email'=>$_data['guardian_email'],
				'status'=>$_data['status'],
				'remark'=>$_data['remark']
	
				);
		$where=$this->getAdapter()->quoteInto("stu_id=?", $_data["id"]);
		$this->update($_arr, $where);
	}
}

