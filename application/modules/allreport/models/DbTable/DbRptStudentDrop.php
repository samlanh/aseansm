<?php

class Allreport_Model_DbTable_DbRptStudentDrop extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_student_drop';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    public function getAllStudentDrop($search){
    	$db = $this->getAdapter();
    	$sql = "SELECT stu_id,(SELECT CONCAT(stu_khname,' - ',stu_enname) FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id`) AS name,
		(SELECT name_kh FROM `rms_view` WHERE `rms_view`.`type`=2 and `rms_view`.`key_code`=(SELECT sex FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id` limit 1))AS sex,
		(SELECT name_kh FROM `rms_view` WHERE `rms_view`.`type`=5 and `rms_view`.`key_code`=`rms_student_drop`.`type`) as type,note,
		date,(select name_kh from `rms_view` where `rms_view`.`type`=6 and `rms_view`.`key_code`=`rms_student_drop`.`status`)AS status
		 from `rms_student_drop` ";

    	$where=' where 1 ';
    	if(empty($search)){
    		return $db->fetchAll($sql);
    	}
    	if(!empty($search['txtsearch'])){
    		$where.=" AND (SELECT CONCAT(stu_khname,' - ',stu_enname) FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id` limit 1) LIKE '%".$search['txtsearch']."%'";
    	}
    	
    	
    	return $db->fetchAll($sql.$where);
    	 
    }
   
    
    
    
    
    
    
    
}