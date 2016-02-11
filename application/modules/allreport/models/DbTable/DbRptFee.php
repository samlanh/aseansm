<?php

class Allreport_Model_DbTable_DbRptFee extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_tuitionfee';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    function getAllTuitionFee($search){
    	$db=$this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,' - ',to_academic) AS academic,
    		    generation,(select name_kh from `rms_view` where `rms_view`.`type`=7 and `rms_view`.`key_code`=`rms_tuitionfee`.`time`)AS time,
    			create_date ,status FROM `rms_tuitionfee` WHERE 1";
//     	$order=" ORDER BY id DESC ";
    	$where= '';
    	
    	if(empty($search)){
    		return $db->fetchAll($sql);
    	}
    	
    	$searchs=$search['txtsearch'];
    	
    	if($search['searchby']==1){
    		$where.=" AND rms_tuitionfee.generation LIKE '%".$searchs."%'";
    	}
    	if($search['searchby']==2){
    		$where.=" AND (SELECT major_enname FROM `rms_major` WHERE (major_id = (select class_id from rms_tuitionfee_detail where (rms_tuitionfee_detail.fee_id = rms_tuitionfee.id ) limit 1))) LIKE '%".$searchs."%'";
    	}
    	
    	return $db->fetchAll($sql.$where);
    	
    }
    function getFeebyOther($fee_id){
    	//print_r($fee_id);exit();
    	$db = $this->getAdapter();
    	$sql = "select *,
    	(SELECT major_enname FROM `rms_major` WHERE major_id=rms_tuitionfee_detail.class_id) as class
    	from rms_tuitionfee_detail where fee_id =".$fee_id." ORDER BY id";
    	return $db->fetchAll($sql);
    }
}
   
    
   