<?php

class Allreport_Model_DbTable_DbRptAcademicYear extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_academicperiod';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    public function getAllAcademic($search){
    	$db = $this->getAdapter();
    	$sql = 'select * from rms_academicperiod where 1';	
    	
    	//$sql.=" LIMIT 1";
    	
    	$where=' where 1';
    	if(empty($search)){
    		return $db->fetchAll($sql);
    	}
    	
//     	if(!empty($search['txtsearch'])){
//     		$where.=" AND g.group_code LIKE '%".$search['txtsearch']."%'";
//     	}

    	$searchs = $search['txtsearch'];
    	if($search['searchby']==0){
    		$where.='';
    	}
    	if($search['searchby']==1){
    		$where.= " AND group_code  LIKE  '%".$searchs."%'";
    	}
    	if($search['searchby']==2){
    		$where.= " AND (SELECT rms_room.room_name FROM rms_room	WHERE (rms_room.room_id = g.room_id)) LIKE '%".$searchs."%'" ;
    	}
    	if($search['searchby']==3){
    		$where.= " AND (SELECT rms_view.name_en	FROM rms_view WHERE ((rms_view.type = 4)
		AND (rms_view.key_code = g.session))LIMIT 1)  LIKE '%".$searchs."%'" ;
    	}
    	if($search['searchby']==4){
    		$where.= " AND (SELECT major_khname FROM `rms_major` WHERE (`rms_major`.`major_id`=`g`.`grade`))  LIKE  '%".$searchs."%'";
    	}
    	
    	return $db->fetchAll($sql.$where);
    	 
    }
   
    
       
}