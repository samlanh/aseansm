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
    		    generation,create_date,status FROM `rms_servicefee`";
    	$order=" ORDER BY id DESC ";
    	$where = ' WHERE 1 ';
    	
    	if(empty($search)){
    		return $db->fetchAll($sql.$order);
    	}
    	$s=$search['txtsearch'];
    	if(!empty($search['txtsearch'])){
    		$s_where = array();
    		$s_search = trim($search['txtsearch']);
    		$s_where[] = " CONCAT(from_academic,'-',to_academic) LIKE '%{$s_search}%'";
    		$s_where[] = " rms_servicefee.from_academic LIKE '%{$s_search}%'";
    		$s_where[] = " rms_servicefee.to_academic LIKE '%{$s_search}%'";
    		$s_where[] = " rms_servicefee.generation LIKE '%{$s_search}%'";
    		//$s_where[] = " (select title from rms_program_name where rms_program_name.service_id=(select service_id from rms_servicefee_detail where rms_servicefee_detail.service_feeid=rms_servicefee.id limit 1)) LIKE '%{$s_search}%'";
//     		$s_where[] = " rms_tuitionfee.to_academic LIKE '%{$s_search}%'";
//     		$s_where[] = " (SELECT major_enname FROM rms_major WHERE rms_major.major_id = (select class_id from rms_tuitionfee_detail where rms_tuitionfee_detail.fee_id = rms_tuitionfee.id  limit 1)) LIKE '%{$s_search}%'";
//     		$s_where[] = " (select name_en from rms_view where rms_view.type=7 and rms_view.key_code=rms_tuitionfee.time) LIKE '%{$s_search}%'";
    		$where .=' AND ( '.implode(' OR ',$s_where).')';
    	}
    	
//     	if(empty($search)){
//     		return $db->fetchAll($sql.$order);
//     	}
//     	$searchs = $search['txtsearch'];
//     	if($search['searchby']==0){
//     		$where.="";
//     	}
//     	if($search['searchby']==1){
//     		$where.= " AND rms_servicefee.generation LIKE '%".$searchs."%' ";
//     	}
//     	if($search['searchby']==2){
//     		$where.=" AND (select title from rms_program_name where rms_program_name.service_id=(select service_id from rms_servicefee_detail where rms_servicefee_detail.service_feeid=rms_servicefee.id limit 1)) LIKE '%".$searchs."%'";
//     	}
//     	echo $sql.$where;
    	return $db->fetchAll($sql.$where.$order);
    	
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




