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
    	$sql = "SELECT (SELECT stu_code FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id`) as stu_id,(SELECT CONCAT(stu_khname,' - ',stu_enname) FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id`) AS name,
		(SELECT name_kh FROM `rms_view` WHERE `rms_view`.`type`=2 and `rms_view`.`key_code`=(SELECT sex FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id` limit 1))AS sex,
		(SELECT name_kh FROM `rms_view` WHERE `rms_view`.`type`=5 and `rms_view`.`key_code`=`rms_student_drop`.`type`) as type,note,
		date,(select name_kh from `rms_view` where `rms_view`.`type`=6 and `rms_view`.`key_code`=`rms_student_drop`.`status`)AS status
		 from `rms_student_drop` ";

    	$where=' where 1 ';
    	$order=" order by id DESC";
    	if(empty($search)){
    		return $db->fetchAll($sql.$order);
    	}
//     	if(!empty($search['txtsearch'])){
//     		$where.=" AND (SELECT CONCAT(stu_khname,' - ',stu_enname) FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id` limit 1) LIKE '%".$search['txtsearch']."%'";
//     	}
    	
    	if(empty($search)){
    		return $db->fetchAll($sql);
    	}
    	if(!empty($search['txtsearch'])){
    		$s_where = array();
    		$s_search = trim($search['txtsearch']);
    		$s_where[] = " (SELECT stu_code FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id`) LIKE '%{$s_search}%'";
    		$s_where[] = " (SELECT CONCAT(stu_khname,' - ',stu_enname) FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id`) LIKE '%{$s_search}%'";
    		$s_where[] = "  (SELECT name_kh FROM `rms_view` WHERE `rms_view`.`type`=5 and `rms_view`.`key_code`=`rms_student_drop`.`type`) LIKE '%{$s_search}%'";
//     		$s_where[] = " en_name LIKE '%{$s_search}%'";
    		$where .=' AND ( '.implode(' OR ',$s_where).')';
    	}
    	
    	
//     	$searchs=$search['txtsearch'];
    	
//     	if($search['searchby']==0){
//     		$where.='';
//     	}
//     	if($search['searchby']==1){
//     		$where.=" AND stu_id  LIKE  '%".$searchs."%' ";
//     	}
//     	if($search['searchby']==2){
//     		$where.=" AND (SELECT CONCAT(stu_khname,' - ',stu_enname) FROM `rms_student` WHERE `rms_student`.`stu_id`=`rms_student_drop`.`stu_id`) LIKE '%".$searchs."%'";
//     	}
//     	if($search['searchby']==3){
//     		$where.=" AND (SELECT name_kh FROM `rms_view` WHERE `rms_view`.`type`=5 and `rms_view`.`key_code`=`rms_student_drop`.`type`) LIKE '%".$searchs."%'";
//     	}
    	
    	return $db->fetchAll($sql.$where.$order);
    	 
    }
   
    
    
    
    
    
    
    
}