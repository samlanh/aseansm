<?php

class Allreport_Model_DbTable_DbRptCar extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_car';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    public function getAllCar(){
    	$db = $this->getAdapter();
    	$sql = " SELECT carid,carname,drivername,tel,zone,note,status FROM rms_car";
    	return $db->fetchAll($sql);
    	 
    }
   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}