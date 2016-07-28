<?php

class Registrar_Model_DbTable_DbReportStudentByuser extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_student';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
	
	function getAllGernAndGepRegister($search=null){
	    	$session_user=new Zend_Session_Namespace('auth');
	    	$user_id=$session_user->user_id;
	    	$db=$this->getAdapter();
	    	
        	$from_date =(empty($search['start_date']))? '1': "sp.create_date >= '".$search['start_date']." 00:00:00'";
	    	$to_date = (empty($search['end_date']))? '1': "sp.create_date <= '".$search['end_date']." 23:59:59'";
	    	
	    	$sql=" SELECT  spd.id,sp.receipt_number,s.stu_code,s.stu_khname,s.stu_enname, 
                   spd.type,
                   (SELECT CONCAT(from_academic,'-',to_academic,'(',generation,')') FROM rms_tuitionfee WHERE `status`=1 AND id=sp.year limit 1) AS year,
                   (SELECT pg.title FROM rms_program_name AS pg WHERE pg.service_id=spd.service_id) AS service_id,
                   spd.fee,spd.discount_percent,spd.discount_fix,spd.subtotal,spd.paidamount,spd.balance,spd.start_date,sp.user_id,spd.note
                   FROM 
                   rms_student AS s,
                   rms_student_payment AS sp,
                   rms_student_paymentdetail AS spd
                   WHERE sp.payfor_type=3 and sp.id=spd.payment_id AND  s.stu_id=sp.student_id AND sp.user_id=$user_id";
	    	$where = " AND ".$from_date." AND ".$to_date;
	    	
	    	if(!empty($search['adv_search'])){
	    		$s_where=array();
	    		$s_search= addslashes(trim($search['adv_search']));
	    		$s_where[]= " s.stu_code LIKE '%{$s_search}%'";
	    		$s_where[]=" sp.receipt_number LIKE '%{$s_search}%'";
	    		$s_where[]= " s.stu_khname LIKE '%{$s_search}%'";
	    		$s_where[]= " s.stu_enname LIKE '%{$s_search}%'";
	    		$s_where[]= " s.grade LIKE '%{$s_search}%'";
	    		$where.=' AND ('.implode(' OR ', $s_where).')';
	    	}
	    	if(($search['service']>0)){
	    		$where.= " AND spd.service_id = ".$search['service'];
	    	}
	    	if(($search['study_year']>0)){
	    		$where.= " AND sp.year = ".$search['study_year'];
	    	}
	    	$order=" ORDER By sp.id DESC ";
	    	//print_r($sql.$where.$order);
	    	return $db->fetchAll($sql.$where.$order);
	    }
	  public function getServices($service_id){
	   	    $db=$this->getAdapter();
	   	    $sql="SELECT pn.service_id,pn.title FROM  rms_program_name AS pn,rms_student_paymentdetail AS spd 
						WHERE pn.service_id=spd.service_id AND pn.type=2 AND spd.service_id=$service_id";
	   	    return $db->fetchOne($sql);
	   }
}

