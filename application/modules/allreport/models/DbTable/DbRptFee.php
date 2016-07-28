<?php

class Allreport_Model_DbTable_DbRptFee extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_tuitionfee';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function getAllTuitionFee($search){
    	$db=$this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,' - ',to_academic) AS academic,note,
    		    generation,(select name_en from `rms_view` where `rms_view`.`type`=7 and `rms_view`.`key_code`=`rms_tuitionfee`.`time`)AS time,create_date ,status FROM `rms_tuitionfee`";
    	$where= ' WHERE 1';
    	$order=" ORDER BY id DESC ";
//     	if(empty($search)){
//     		return $db->fetchAll($sql);
//     	}
//     	$searchs=$search['txtsearch'];
    	
//     	if($search['searchby']==1){
//     		$where.=" AND rms_tuitionfee.generation LIKE '%".$searchs."%'";
//     	}
//     	if($search['searchby']==2){
//     		$where.=" AND (SELECT major_enname FROM `rms_major` WHERE (major_id = (select class_id from rms_tuitionfee_detail where (rms_tuitionfee_detail.fee_id = rms_tuitionfee.id ) limit 1))) LIKE '%".$searchs."%'";
//     	}
    	
    	if(empty($search)){
    		return $db->fetchAll($sql.$order);
    	}
//     	$s=$search['txtsearch'];
    	if(!empty($search['txtsearch'])){
    		$s_where = array();
    		$abc = $s_search = addslashes(trim($search['txtsearch']));
    		$s_where[] = " CONCAT(from_academic,'-',to_academic) LIKE '%{$s_search}%'";
    		$s_where[] = " rms_tuitionfee.generation LIKE '%{$s_search}%'";
    		$s_where[] = " rms_tuitionfee.from_academic LIKE '%{$s_search}%'";
    		$s_where[] = " rms_tuitionfee.to_academic LIKE '%{$s_search}%'";
    		//$s_where[] = " (SELECT major_enname FROM rms_major WHERE rms_major.major_id = (select class_id from rms_tuitionfee_detail where rms_tuitionfee_detail.fee_id = rms_tuitionfee.id  limit 1)limit 1) LIKE '%{$s_search}%'";
    		$s_where[] = " (select name_en from rms_view where rms_view.type=7 and rms_view.key_code=rms_tuitionfee.time) LIKE '%{$s_search}%'";
    		$where .=' AND ( '.implode(' OR ',$s_where).')';
    	}

    	return $db->fetchAll($sql.$where.$order);
    }
    function getFeebyOther($fee_id){
    	//print_r($fee_id);exit();
    	$db = $this->getAdapter();
    	$sql = "select *,
    	(SELECT major_enname FROM `rms_major` WHERE major_id=rms_tuitionfee_detail.class_id) as class,
    	(select name_en from rms_view where type=4 and key_code=session) as session
    	from rms_tuitionfee_detail where fee_id =".$fee_id." ORDER BY id";
    	return $db->fetchAll($sql);
    }
}
   
    
   