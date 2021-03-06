<?php

class Registrar_Model_DbTable_DbRptStudentNearlyEndService extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_tuitionfee';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function getAllStudentNearlyEndService($search){
    	$db=$this->getAdapter();
//     	echo $end_date = $search['end_date'];
//     	echo NOW();exit();
		$user=$this->getUserId();
    	$sql="SELECT 
				  sp.`receipt_number` AS receipt,
				  s.stu_code AS code,
				  CONCAT(s.stu_khname,' - ',s.stu_enname) AS name,
				  (select name_en from rms_view where rms_view.type=2 and key_code=s.sex )AS sex,
				  s.tel,
				  pn.`title` service,
				  spd.`start_date` as start,
				  spd.`validate` as end
				  
				FROM
				  `rms_student_paymentdetail` AS spd,
				  `rms_student_payment` AS sp,
				  `rms_program_name` AS pn,
				  rms_student as s
				WHERE spd.`is_start` = 1
				  
				  AND s.stu_id=sp.student_id 
				  AND sp.id=spd.`payment_id`
				  AND spd.`service_id`=pn.`service_id` 
    			  and sp.user_id = ".$user ;
    	
    	
    	$where="  ";
     	
    	$order=" ORDER by spd.`validate` DESC ";
     	
    	$str_next = '+1 week';
     	$search['end_date']=date("Y-m-d", strtotime($search['end_date'].$str_next));
     	
      	//$from_date =(empty($search['start_date']))? '1': " sp.create_date >= '".$search['start_date']." 00:00:00'";
      	$to_date = (empty($search['end_date']))? '1': " spd.validate <= '".$search['end_date']." 23:59:59'";
      	
      	$where .= " AND ".$to_date;
     	
      	
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
    		if($search['study_year']>0){
    			$where.=" AND sp.year=".$search['study_year'];
    		}
    		if($search['service']>0){
    			$where.=" AND spd.service_id=".$search['service'];
    		}
    		
    		//echo $sql.$where;
    	return $db->fetchAll($sql.$where.$order);
    }
    
}
   
    
   