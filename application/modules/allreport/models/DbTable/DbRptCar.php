<?php

class Allreport_Model_DbTable_DbRptCar extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_car';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    public function getAllCar($search){
    	$db = $this->getAdapter();
    	$sql = "SELECT carid,carname,drivername,tel,zone,note,status FROM rms_car ";
    	$where=' where 1';
    	if(empty($search)){
    		return $db->fetchAll($sql);
    	}
//     	if(!empty($search['txtsearch'])){
//     		$where.=" AND rms_car.carid LIKE '%".$search['txtsearch']."%'";
//     	}
		
    	$searchs = $search['txtsearch'];
    	if($search['searchby'] == 0){
    		$where.= '';
    	}
    	if($search['searchby'] == 1){
    		$where.= " AND carid = ".$search['txtsearch'];
    	}
    	if($search['searchby'] == 2){
    		$where.= " AND carname LIKE '%".$searchs."%'";
    	}
    	if($search['searchby'] == 3){
    		$where.= " AND drivername LIKE '%".$searchs."%'";
    	}
    	
    	echo $sql.$where;
    	return $db->fetchAll($sql.$where);
    	 
    }
   
    
    
    
    
    
    
}