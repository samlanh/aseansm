<?php

class Allreport_Model_DbTable_DbRptServiceCharge extends Zend_Db_Table_Abstract
{
	protected $_name = 'rms_servicefee';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    function getAllServiceFee($search){
    	$db=$this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,' - ',to_academic) AS academic,
    		    generation,create_date,status FROM `rms_servicefee` WHERE 1";
//     	$order=" ORDER BY id DESC ";
    	$where = '';
    	
    	if(empty($search)){
    		return $db->fetchAll($sql);
    	}
    	
    	$searchs = $search['txtsearch'];
    	
    	if($search['searchby']==0){
    		$where.="";
    	}
    	
    	if($search['searchby']==1){
    		$where.= " AND rms_servicefee.generation LIKE '%".$searchs."%' ";
    	}
    	
    	if($search['searchby']==2){
//     		$where.= " AND (select title from rms_program_name where rms_program_name.service_id = (select service_id from rms_servicefee_detail where (rms_servicefee_detail.service_feeid = rms_servicefee.id) limit 1)) LIKE '%".$searchs."%'";
    		
    		$where.=" AND (select title from rms_program_name where rms_program_name.service_id=(select service_id from rms_servicefee_detail where rms_servicefee_detail.service_feeid=rms_servicefee.id limit 1) limit 1) LIKE '%".$searchs."%'";
    		
    	}
    	
    	return $db->fetchAll($sql.$where);
    	
    }    
    function getServiceFeebyId($service_id){
    	$db = $this->getAdapter();
    	//     	if($type!=null){
//     	$sql = "SELECT * FROM `rms_servicefee_detail` WHERE service_feeid=".$service_id." ORDER BY service_id ";
    	$sql = "SELECT id,service_id,price_fee,payment_term,remark,
    			(select title from rms_program_name where rms_program_name.service_id=rms_servicefee_detail.service_id limit 1)AS service_name FROM `rms_servicefee_detail` 
    			WHERE service_feeid=".$service_id." ORDER BY service_id ";
    	return $db->fetchAll($sql);
    }

    
    
    
}




