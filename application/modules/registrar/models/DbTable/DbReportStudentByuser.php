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
// 	     	$from_date = (empty($search["start_date"]))?'Y-m-01':$search["start_date"];
// 	     	$to_date =  (empty($search["end_date"]))?'Y-m-01' :$search["end_date"];
// 	     	$where =" AND sp.create_date BETWEEN '$from_date' AND '$to_date'";
            $where="";
	    	$sql=" SELECT  spd.id,sp.receipt_number,s.stu_code,s.stu_khname,s.stu_enname, 
                   spd.type,(SELECT pg.title FROM rms_program_name AS pg WHERE pg.service_id=spd.service_id) AS service_id,
                   spd.fee,spd.discount_fix,spd.subtotal,spd.paidamount,spd.balance,spd.start_date,sp.user_id,spd.note
                   FROM rms_student AS s,rms_student_payment AS sp,
                   rms_student_paymentdetail AS spd
                   WHERE sp.id=spd.payment_id AND  s.stu_id=sp.student_id AND sp.user_id=$user_id";
	    	
	    	if(!empty($search['txtsearch'])){
	    		$s_where=array();
	    		$s_search=$search['txtsearch'];
	    		$s_where[]= " stu_code LIKE '%{$s_search}%'";
	    		$s_where[]=" receipt_number LIKE '%{$s_search}%'";
	    		$s_where[]= " stu_khname LIKE '%{$s_search}%'";
	    		$s_where[]= " stu_enname LIKE '%{$s_search}%'";
	    		$s_where[]= " grade LIKE '%{$s_search}%'";
	    		$where.=' AND ('.implode(' OR ', $s_where).')';
	    	}
	    	$order=" ORDER By stu_id DESC ";
	    	//print_r($sql.$where);
	    	return $db->fetchAll($sql.$where.$order);
	    }
}

