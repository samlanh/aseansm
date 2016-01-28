<?php

class Allreport_Model_DbTable_DbRptServiceCharge extends Zend_Db_Table_Abstract
{

//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    function getAllServiceFee(){
    	$db=$this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,' - ',to_academic) AS academic,
    		    generation,create_date,status FROM `rms_servicefee` WHERE 1";
    	$order=" ORDER BY id DESC ";
    	$where = '';
    	
    	return $db->fetchAll($sql.$where.$order);
    	
    }    
    function getServiceFeebyId($service_id){
    	$db = $this->getAdapter();
    	//     	if($type!=null){
    	$sql = "SELECT * FROM `rms_servicefee_detail` WHERE service_feeid=".$service_id." ORDER BY service_id ";
    	return $db->fetchAll($sql);
    }

}