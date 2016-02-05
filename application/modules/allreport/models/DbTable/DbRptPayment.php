<?php

class Allreport_Model_DbTable_DbRptPayment extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_student_payment';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    public function getStudentPayment($search){
    	$db = $this->getAdapter();
    	$sql=" SELECT * FROM v_getstudentpayment	";
    	$sql.=' WHERE 1';
    	if(empty($search)){
    		return $db->fetchAll($sql);
    	}
    	if(!empty($search['txtsearch'])){
    		$s_where = array();
    		$s_search = trim($search['txtsearch']);
    		$s_where[] = " total_payment LIKE '%{$s_search}%'";
	 		$s_where[] = " receipt_number LIKE '%{$s_search}%'";
    		$s_where[] = " stu_code LIKE '%{$s_search}%'";
    		$s_where[] = " kh_name LIKE '%{$s_search}%'";
    		$s_where[] = " en_name LIKE '%{$s_search}%'";
    		$s_where[] = " sex LIKE '%{$s_search}%'";
    		$s_where[] = " nationality LIKE '%{$s_search}%'";
    		$sql .=' AND ( '.implode(' OR ',$s_where).')';
    	}
    	return $db->fetchAll($sql);
    }
    
    public function getStudentPaymentDetail(){
    	$db = $this->getAdapter();
    	$sql="SELECT id,
    	payment_id,
    	(SELECT receipt_number FROM `rms_student_payment` WHERE id=payment_id) as receipt_number,
    	(SELECT (SELECT `rms_student`.`stu_khname`FROM `rms_student` WHERE (`rms_student`.`stu_id` = `rms_student_payment`.`student_id`))FROM `rms_student_payment` WHERE id=payment_id) as kh_name,
    	(SELECT (SELECT `rms_student`.`stu_enname`FROM `rms_student` WHERE (`rms_student`.`stu_id` = `rms_student_payment`.`student_id`))FROM `rms_student_payment` WHERE id=payment_id) as en_name,
    	(SELECT (SELECT (SELECT `rms_view`.`name_kh`FROM `rms_view` WHERE ((`rms_view`.`type` = 2) AND (`rms_view`.`key_code` = `rms_student`.`sex`))) FROM `rms_student` WHERE (`rms_student`.`stu_id` = `rms_student_payment`.`student_id`))FROM `rms_student_payment` WHERE id=payment_id) as sex,
    	type,
    	(SELECT title FROM `rms_program_name` WHERE `rms_program_name`.`service_id`= rms_student_paymentdetail.service_id) as service,
    	(SELECT `name_en` FROM `rms_view` WHERE  `type`=8 AND key_code= payment_term)as payment_term,
    	amount,
    	(SELECT `total_payment` FROM `rms_student_payment` WHERE id= payment_id) as total_payment,
    	(SELECT `paid_amount` FROM `rms_student_payment` WHERE id= payment_id) as paid_amount,
    	(SELECT `balance_due` FROM `rms_student_payment` WHERE id= payment_id) as balance_due,
    	(SELECT `return_amount` FROM `rms_student_payment` WHERE id= payment_id) as return_amount,
    	(SELECT `amount_in_khmer` FROM `rms_student_payment` WHERE id= payment_id) as amount_in_khmer,
    	discount_percent,
    	(SELECT CONCAT (`last_name`,' ', `first_name`) FROM `rms_users` WHERE `rms_users`.id = user_id) as user,
    	note,create_date
    	FROM rms_student_paymentdetail;
    	";
    	return $db->fetchAll($sql);
    }
    
}
   
    
   