<?php

class Registrar_Model_DbTable_DbRptStudentPaymentLate extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_tuitionfee';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    
    function getAllStudentPaymentLate($search){
    	$db=$this->getAdapter();
    	$user=$this->getUserId();
    	$sql="SELECT 
				  sp.`receipt_number` AS receipt,
				  (select stu_code from rms_student where rms_student.stu_id=sp.student_id limit 1)AS code,
				  (select CONCAT(stu_khname,' - ',stu_enname) from rms_student where rms_student.stu_id=sp.student_id limit 1)AS name,
				  (select name_en from rms_view where rms_view.type=2 and key_code=(select sex from rms_student where rms_student.stu_id=sp.student_id limit 1))AS sex,
				  (select tel from rms_student where rms_student.stu_id=sp.student_id limit 1)AS tel,
				  pn.`title` service,
				  spd.`start_date` as start,
				  spd.`validate` as end
				  
				FROM
				  `rms_student_paymentdetail` AS spd,
				  `rms_student_payment` AS sp,
				  `rms_program_name` AS pn
				WHERE spd.`is_start` = 1 
				  AND sp.id=spd.`payment_id`
				  AND spd.`service_id`=pn.`service_id`
    			  and sp.user_id=".$user;
    	
     	$order=" ORDER by spd.validate DESC ";
//      	$from_date =(empty($search['start_date']))? '1': " sp.create_date >= '".$search['start_date']." 00:00:00'";
     	$to_date = (empty($search['end_date']))? '1': " spd.validate < '".$search['end_date']." 23:59:59'";
     	$where = " AND ".$to_date;
     	
    		if(!empty($search['adv_search'])){
    			$s_where = array();
    			$s_search = addslashes(trim($search['adv_search']));
    			$s_where[] = " sp.receipt_number LIKE '%{$s_search}%'";
    			$s_where[] = " (select stu_code from rms_student where rms_student.stu_id=sp.student_id) LIKE '%{$s_search}%'";
    			$s_where[] = " (select CONCAT(stu_khname,stu_enname) from rms_student where rms_student.stu_id=sp.student_id) LIKE '%{$s_search}%'";
    			$s_where[] = " (select title from rms_program_name where rms_program_name.service_id=spd.service_id) LIKE '%{$s_search}%'";
    			$s_where[] = " spd.comment LIKE '%{$s_search}%'";
    			$where .=' AND ( '.implode(' OR ',$s_where).')';
    		}
    		if(!empty($search['service']) AND $search['service']>0){
    			$where.= " AND spd.service_id = ".$search['service'];
    		}
//     		if($search['study_year']>0){
//     			$where.= " AND spd.service_id = ".$search['study_year'];
//     		}
     		//echo $sql.$where;
    	return $db->fetchAll($sql.$where.$order);
    }
    
}
   
    
   