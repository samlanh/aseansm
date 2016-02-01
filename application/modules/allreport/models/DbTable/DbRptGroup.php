<?php

class Allreport_Model_DbTable_DbRptGroup extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_group';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    public function getAllGroup($search){
    	$db = $this->getAdapter();
    	$sql = 'SELECT `g`.`id`,`g`.`group_code` AS `group_code`,CONCAT(`g`.`from_academic`," - ",
		`g`.`to_academic`) AS academic ,`g`.`semester` AS `semester`,
		(SELECT kh_name FROM `rms_dept` WHERE (`rms_dept`.`dept_id`=`g`.`degree`))AS degree,
		(SELECT major_khname FROM `rms_major` WHERE (`rms_major`.`major_id`=`g`.`grade`)) AS grade,`g`.`amount_month`,
		(SELECT`rms_view`.`name_en`	FROM `rms_view`	WHERE ((`rms_view`.`type` = 4)
		AND (`rms_view`.`key_code` = `g`.`session`))LIMIT 1) AS `session`,
		(SELECT `r`.`room_name`	FROM `rms_room` `r`	WHERE (`r`.`room_id` = `g`.`room_id`)) AS `room_name`,
		`g`.`start_date`,`g`.`expired_date`,`g`.`note`,
		(SELECT `rms_view`.`name_en` FROM `rms_view` WHERE ((`rms_view`.`type` = 1)
		AND (`rms_view`.`key_code` = `g`.`status`)) LIMIT 1) AS `status`
		FROM `rms_group` `g`  ';	
    	
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