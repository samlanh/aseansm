<?php
class allreport_Model_DbTable_DbStudent extends Zend_Db_Table_Abstract
{
	public function getAllStudentre(){
		$_db = $this->getAdapter();
		$sql = "SELECT stu_id,stu_enname,stu_khname,
		(SELECT name_kh FROM `rms_view` WHERE type=2 AND key_code = sex) as gender
		,stu_code,dob,remark,tel,(SELECT province_kh_name FROM `rms_province` WHERE `province_id`= rms_student.province_id) as pro,
		father_phone,mother_phone,address,home_num,street_num,village_name,commune_name,district_name,
		(SELECT name_kh FROM `rms_view` WHERE type=1 AND key_code = status) as status,nationality,
		(SELECT `kh_name` FROM `rms_dept` WHERE `dept_id`= degree) as degree,(SELECT `major_enname` FROM `rms_major` WHERE `major_id`=grade) as grade,
		(SELECT `name_kh` FROM `rms_view` WHERE TYPE=4 AND key_code =session)as session ,
		(SELECT (SELECT `title` FROM`rms_program_name` WHERE `service_id` = `level`) FROM `rms_study_history` WHERE `stu_id` = rms_student.stu_id) as level
		FROM rms_student where status = 1";
		$orderby = " ORDER BY stu_enname ";
		return $_db->fetchAll($sql.$orderby);
	}
 
}



