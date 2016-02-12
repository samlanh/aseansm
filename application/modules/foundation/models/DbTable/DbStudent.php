<?php

class Foundation_Model_DbTable_DbStudent extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'rms_student';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
	public function getAllStudent($search){
		$_db = $this->getAdapter();
		$sql = "SELECT stu_id,stu_khname,stu_enname,
		(SELECT name_kh FROM `rms_view` WHERE type=2 AND key_code = sex) as sex
		,(SELECT `major_enname` FROM `rms_major` WHERE `major_id`=grade) as grade,nationality,dob,tel,email ,
		(SELECT name_kh FROM `rms_view` WHERE type=1 AND key_code = status) as status
		FROM rms_student where status = 1 AND stu_type = 1 ";
		$orderby = " ORDER BY stu_id DESC ";
		$where = '';
		if(empty($search)){
			return $_db->fetchAll($sql.$orderby);
		}
		if(!empty($search['txtsearch'])){
			$s_where = array();
			$s_search = trim($search['txtsearch']);
			$s_where[]="stu_khname LIKE '%{$s_search}%'";
			$s_where[]="stu_enname LIKE '%{$s_search}%'";
			$s_where[]="(SELECT `major_enname` FROM `rms_major` WHERE `major_id`=grade) LIKE '%{$s_search}%'";
			$s_where[]="(SELECT name_kh FROM `rms_view` WHERE type=2 AND key_code = sex) LIKE '%{$s_search}%'";
			$where .=' AND ( '.implode(' OR ',$s_where).')';
		}
		return $_db->fetchAll($sql.$where.$orderby);
	}
	public function getStudentById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_student WHERE stu_id =".$id;
		return $db->fetchRow($sql);
	}
	public function getDegreeLanguage(){
		try{
			$db = $this->getAdapter();
			$sql ="SELECT id,title FROM rms_degree_language WHERE status =1";
			//print_r($db->fetchRow($sql)); exit();
			return $db->fetchAll($sql);
		}catch(Exception $e){
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	public function addStudent($_data){
			try{	
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
				$id = $this->insert($_arr);
				
				$this->_name='rms_study_history';
				$arr= array(
						'user_id'=>$this->getUserId(),
						'stu_id'=>$id,
						'stu_code'=>$_data['student_id'],
						'degree'=>$_data['degree'],
						'grade'=>$_data['grade'],
						'lang_level'=>$_data['level'],
						'session'=>$_data['session'],
						'status'=>$_data['status'],
						'remark'=>$_data['remark']
						);
				$this->insert($arr);
			}catch(Exception $e){
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
	}
	public function updateStudent($_data){
		
		try{	
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
			
			
			$this->_name='rms_study_history';
			$arr= array(
					'user_id'=>$this->getUserId(),
					'stu_code'=>$_data['student_id'],
					'degree'=>$_data['degree'],
					'grade'=>$_data['grade'],
					'lang_level'=>$_data['level'],
					'session'=>$_data['session'],
					'status'=>$_data['status'],
					'remark'=>$_data['remark'],
					
			);
			$where=$this->getAdapter()->quoteInto("stu_id=?", $_data["id"]);
			$this->update($arr, $where);
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	function getStudyHishotryById($id){
		$db = $this->getAdapter();
		$sql="SELECT * FROM rms_study_history where".$id;
		//print_r($db->fetchRow($sql)); exit();
		return $db->fetchRow($sql);
	}
	function getAllGrade($grade_id){
		$db = $this->getAdapter();
		$sql = "SELECT major_id As id,major_enname As name FROM rms_major WHERE dept_id=".$grade_id;
		$order=' ORDER BY id DESC';
		return $db->fetchAll($sql.$order);
	}

	function getStudentInfoById($stu_id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM `rms_student` WHERE stu_id=$stu_id LIMIT 1 ";
		return $db->fetchRow($sql);
	}
	function getSearchStudent($data){
		$db=$this->getAdapter();
		$sql="SELECT stu_id ,stu_code,stu_enname,stu_khname,sex,degree,grade from rms_student ";
		 $sql.= ' WHERE `status`=1 AND is_setgroup = 0 ';
		 if($data['grade']>0){
		 	$sql.=" AND grade =".$data['grade'];
		 }
		 if($data['session']>0){
		 	$sql.=" AND session =".$data['session'];
		 }
		return $db->fetchAll($sql);
	}

}

