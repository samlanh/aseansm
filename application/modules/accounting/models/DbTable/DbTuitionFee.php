<?php

class Accounting_Model_DbTable_DbTuitionFee extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_tuitionfee';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
    function getAllTuitionFee($search=''){
    	$db=$this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,' - ',to_academic) AS academic,
    		    generation,(select name_kh from `rms_view` where `rms_view`.`type`=7 and `rms_view`.`key_code`=`rms_tuitionfee`.`time`)AS time,
    		    create_date ,status FROM `rms_tuitionfee` WHERE 1";
    	$order=" ORDER BY id DESC ";
    	$where = '';
    	if(empty($search)){
    		return $db->fetchAll($sql.$order);
    	}
	    if(!empty($search['txtsearch'])){
	    	$s_where = array();
	    	$s_search = addslashes(trim($search['txtsearch']));
		 	$s_where[] = " CONCAT(from_academic,'-',to_academic) LIKE '%{$s_search}%'";
	    	$s_where[] = " generation LIKE '%{$s_search}%'";
	    	$s_where[] = " (select name_kh from `rms_view` where `rms_view`.`type`=7 and `rms_view`.`key_code`=`rms_tuitionfee`.`time`) LIKE '%{$s_search}%'";
// 	    	$s_where[] = " en_name LIKE '%{$s_search}%'";
	    	$where .=' AND ( '.implode(' OR ',$s_where).')';
	    }
    	return $db->fetchAll($sql.$where.$order);
    }
    function getFeebyOther($fee_id){
    	$db = $this->getAdapter();
    	$sql = "select *,
		(SELECT major_enname FROM `rms_major` WHERE major_id=rms_tuitionfee_detail.class_id) as class
    	from rms_tuitionfee_detail where fee_id =".$fee_id." ORDER BY id";
    	return $db->fetchAll($sql);
    }
    function getCondition($_data){
    	$db = $this->getAdapter();
    	$find="select id from rms_tuitionfee where from_academic=".$_data['from_year']." and to_academic=".$_data['to_year']." 
    		   and generation='".$_data['generation']."' and time=".$_data['time'];
    	
    	return $db->fetchOne($find);
    }
    ////////////////
    public function addTuitionFee($_data){
    	
    	$db = $this->getAdapter();
    	$db->beginTransaction();
		
    	$fee_id = $this->getCondition($_data);
    	try{
    		if(!empty($fee_id)){
    			
    		}else{
	    		$_arr = array(
	    				'from_academic'=>$_data['from_year'],
	    				'to_academic'=>$_data['to_year'],
	    				'generation'=>$_data['generation'],
	    				'note'=>$_data['note'],
	    				'time'=>$_data['time'],
	    				'status'=>$_data['status'],
	    				'create_date'=>date("d-m-Y"),
	    				'user_id'=>$this->getUserId()
	    				);
	    		$fee_id = $this->insert($_arr);
    		}
	    		
	    		$this->_name='rms_tuitionfee_detail';
	    		$ids = explode(',', $_data['identity']);
	    		$id_term =explode(',', $_data['iden_term']);
	    		foreach ($ids as $i){
	    			foreach ($id_term as $j){
	    				$_arr = array(
	    						'fee_id'=>$fee_id,
	    						'class_id'=>$_data['class_'.$i],
	    						'payment_term'=>$j,
	    						'tuition_fee'=>$_data['fee'.$i.'_'.$j],
	    						'remark'=>$_data['remark'.$i]
	    				);
	    				$this->insert($_arr);
	    			}
	    		}
	    	    $db->commit();
	    	    return true;
    		
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		return false;
    	}
    }
    public function updateTuitionFee($_data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    
    		$_arr = array(
    				'from_academic'=>$_data['from_year'],
    				'to_academic'=>$_data['to_year'],
    				'generation'=>$_data['generation'],
    				'note'=>$_data['note'],
    				'status'=>$_data['status'],
    				'time'=>$_data['time'],
    				'create_date'=>date("d-m-Y"),
    				'user_id'=>$this->getUserId()
    		);
//     		$fee_id = $this->insert($_arr);
    		$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    		$this->update($_arr, $where);
    
    		$this->_name='rms_tuitionfee_detail';
    		$where = "fee_id = ".$_data['id'];
    		$this->delete($where);
    		$ids = explode(',', $_data['identity']);
    		$id_term =explode(',', $_data['iden_term']);
    		foreach ($ids as $i){
    			foreach ($id_term as $j){
    				$_arr = array(
    						'fee_id'=>$_data['id'],
    						'class_id'=>$_data['class_'.$i],
    						'payment_term'=>$j,
    						'tuition_fee'=>$_data['fee'.$i.'_'.$j],
    						'remark'=>$_data['remark'.$i],
    						'status'=>$_data['status_'.$i]
    				);
     				$this->insert($_arr);
    				
    			}
    		}
    		$db->commit();
    		return true;
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		return false;
    	}
    }
    public function setServiceChargeExist($service_id,$pay_type){
    	$db = $this->getAdapter();
    	$sql = "SELECT servicefee_id,price FROM `rms_servicefee_detail` WHERE service_id=$service_id AND pay_type=$pay_type ";
    	return $db->fetchRow($sql);
    	//batch ,metion OR faculty,payment_term,(degree_type)
    }
    
    public function getFeeDetailById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM rms_tuitionfee_detail WHERE fee_id = ".$id ." ORDER BY id";
    	return $db->fetchAll($sql);
    
    }
    
    public function getFeeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM rms_tuitionfee WHERE id = ".$id;
    	$sql.=" LIMIT 1";
    	return $db->fetchRow($sql);
    	 
    }
    public function updateFee($_data){
    	$db = $this->getAdapter();
  		try{
    		$_arr = array(
    				'from_academic'=>$_data['from_year'],
    				'to_academic'=>$_data['to_year'],
    				'generation'=>$_data['generation'],
    				'note'=>$_data['note'],
    				'status'=>1,
    				'create_date'=>$_data['create_date'],
    				'user_id'=>$this->getUserId()
    				);
    		$fee_id = $this->insert($_arr);
    		
    		$this->_name='rms_tuitionfee_detail';
    		$ids = explode(',', $_data['identity']);
    		$id_term =explode(',', $_data['iden_term']);
    		foreach ($ids as $i){
    			foreach ($id_term as $j){
    				$_arr = array(
    						'fee_id'=>$fee_id,
    						'class_id'=>$_data['class_'.$i],
    						'payment_term'=>$j,
    						'tuition_fee'=>$_data['fee'.$i.'_'.$j],
    						'remark'=>$_data['remark'.$i]
    				);
    				$this->insert($_arr);
    			}
    		}
    	    $db->commit();
    	    return true;
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		return false;
    	}
    	$where=$this->getAdapter()->quoteInto("id=?", $data['id']);
    	$this->update($_arr, $where);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}