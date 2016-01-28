<?php

class Allreport_Model_DbTable_DbRptFee extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_tuitionfee';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    function getAllTuitionFee($search=''){
    	$db=$this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,' - ',to_academic) AS academic,
    		    generation,(select name_kh from `rms_view` where `rms_view`.`type`=7 and `rms_view`.`key_code`=`rms_tuitionfee`.`time`)AS time,create_date ,status FROM `rms_tuitionfee` WHERE 1";
    	$order=" ORDER BY id DESC ";
    	$where = '';
    	return $db->fetchAll($sql.$where.$order);
    }
    function getFeebyOther($fee_id){
    	$db = $this->getAdapter();
    	$sql = "select *,
    	(SELECT major_enname FROM `rms_major` WHERE major_id=rms_tuitionfee_detail.class_id) as class
    	from rms_tuitionfee_detail where fee_id =".$fee_id." ORDER BY id";
    	return $db->fetchAll($sql);
    }
}
   
    
   