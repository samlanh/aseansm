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
   public function getStudentGroup($id,$search){
   	$db = $this->getAdapter();
   	$sql= 'SELECT 
   	(SELECT `group_code` FROM `rms_group` WHERE id= group_id) AS group_code,
   	(SELECT `stu_code` FROM `rms_student` WHERE `stu_id` = rms_group_detail_student.stu_id) AS stu_code,
   	(SELECT `stu_khname` FROM `rms_student` WHERE `stu_id` = rms_group_detail_student.stu_id) AS kh_name,
   	(SELECT `stu_enname` FROM `rms_student` WHERE `stu_id` = rms_group_detail_student.stu_id) AS en_name,
   	(SELECT `nationality` FROM `rms_student` WHERE `stu_id` = rms_group_detail_student.stu_id) AS nation,
   	(SELECT `address` FROM `rms_student` WHERE `stu_id` = rms_group_detail_student.stu_id) AS pob,
   	(SELECT `tel` FROM `rms_student` WHERE `stu_id` = rms_group_detail_student.stu_id) AS tel,
   	(SELECT (SELECT `name_kh` FROM `rms_view` WHERE `type` = 2 AND `key_code`= rms_student.sex) FROM `rms_student`WHERE `stu_id`=rms_group_detail_student.`stu_id`) AS sex,
   	(SELECT `dob` FROM `rms_student` WHERE `stu_id` = rms_group_detail_student.stu_id) AS dob,
    (SELECT (SELECT `room_name` FROM `rms_room`WHERE `room_id`= rms_group.`room_id`) FROM `rms_group` WHERE id = `group_id`)AS room ,
   	(SELECT (SELECT `name_en` FROM `rms_view` WHERE `type`=4 AND key_code = `session`) FROM `rms_group` WHERE id= `group_id`) AS session,
   	 (SELECT CONCAT(`from_academic`,"-",`to_academic`) FROM `rms_group` WHERE id= `group_id`) AS academic,
   	status FROM rms_group_detail_student WHERE status = 1 AND group_id='.$id;
   	$where='';

   	if(empty($search)){
   		return $db->fetchAll($sql);
   	}
   	
   	$searchs = $search['txtsearch'];
   	if($search['searchby']==0){
   		$where.='';
   	}
   	if($search['searchby']==1){
   		$where.= " AND (SELECT rms_student.stu_khname FROM `rms_student` WHERE (rms_student.stu_id = rms_group_detail_student.stu_id))  LIKE  '%".$searchs."%'";
   	}
   	$sq = "SELECT (SELECT rms_student.stu_khname FROM rms_student	WHERE rms_student.stu_id = rms_group_detail_student.stu_id) From rms_group_detail_student WHERE status = 1 AND group_id=".$id;
   	if($search['searchby']==2){
   		$where.= " AND $sq LIKE '%".$searchs."%'" ;
   	}
   
  	//print_r($db->fetchAll($sq));exit();
  
   	return $db->fetchAll($sql,$where);
   }
   
   public function getGroupDetail(){
   	$db = $this->getAdapter();
   	$sql = 'SELECT
   	`g`.`id`,
   	`g`.`group_code`    AS `group_code`,
   
   	CONCAT(`g`.`from_academic`," - ",`g`.`to_academic`) AS academic ,
   
   	`g`.`semester` AS `semester`,
   
   	(SELECT kh_name
   	FROM `rms_dept`
   	WHERE (`rms_dept`.`dept_id`=`g`.`degree`)),
   	(SELECT major_khname
   	FROM `rms_major`
   	WHERE (`rms_major`.`major_id`=`g`.`grade`)),
   	(SELECT	`rms_view`.`name_en`
   	FROM `rms_view`
   	WHERE ((`rms_view`.`type` = 4)
   	AND (`rms_view`.`key_code` = `g`.`session`))
   	LIMIT 1) AS `session`,
   	(SELECT
   	`r`.`room_name`
   	FROM `rms_room` `r`
   	WHERE (`r`.`room_id` = `g`.`room_id`)) AS `room_name`,
   	`g`.`start_date`,
   	`g`.`expired_date`,
   	`g`.`note`,
   	(SELECT
   	`rms_view`.`name_en`
   	FROM `rms_view`
   	WHERE ((`rms_view`.`type` = 1)
   	AND (`rms_view`.`key_code` = `g`.`status`))
   	LIMIT 1) AS `status`,
   	(SELECT COUNT(`stu_id`) FROM `rms_group_detail_student` WHERE `group_id`=`g`.`id`)AS Num_Student
   	FROM `rms_group` `g`
   	ORDER BY `g`.`id` DESC ';
   
   	return $db->fetchAll($sql);
   }
   public function getGroupDetailByID($id){
   	$db = $this->getAdapter();
   	$sql = 'SELECT
   	`g`.`id`,
   	`g`.`group_code`    AS `group_code`,
   	 
   	CONCAT(`g`.`from_academic`," - ",`g`.`to_academic`) AS academic ,
   	 
   	`g`.`semester` AS `semester`,
   	 
   	(SELECT kh_name
   	FROM `rms_dept`
   	WHERE (`rms_dept`.`dept_id`=`g`.`degree`)) as degree,
   	(SELECT major_khname
   	FROM `rms_major`
   	WHERE (`rms_major`.`major_id`=`g`.`grade`)) as grade,
   	(SELECT	`rms_view`.`name_kh`
   	FROM `rms_view`
   	WHERE ((`rms_view`.`type` = 4)
   	AND (`rms_view`.`key_code` = `g`.`session`))
   	LIMIT 1) AS `session`,
   	(SELECT
   	`r`.`room_name`
   	FROM `rms_room` `r`
   	WHERE (`r`.`room_id` = `g`.`room_id`)) AS `room_name`,
   	`g`.`start_date`,
   	`g`.`expired_date`,
   	`g`.`note`,
   	(SELECT
   	`rms_view`.`name_en`
   	FROM `rms_view`
   	WHERE ((`rms_view`.`type` = 1)
   	AND (`rms_view`.`key_code` = `g`.`status`))
   	LIMIT 1) AS `status`,
   	(SELECT COUNT(`stu_id`) FROM `rms_group_detail_student` WHERE `group_id`=`g`.`id`)AS Num_Student
   	FROM `rms_group` `g` WHERE `g`.`id`='.$id;
  //print_r($db->fetchRow($sql)); exit();
   	 
   	return $db->fetchRow($sql);
   }
       
}