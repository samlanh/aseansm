<?php

class Accounting_Model_DbTable_DbServiceCharge extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_servicefee';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function sqltuitionfee($search=''){
    	$sql = "SELECT p.service_id as id,p.`title` AS service_name,p.status,t.title as cate_name FROM `rms_program_name` AS p,`rms_program_type` AS t
					WHERE t.id=p.ser_cate_id ";
    	$order=" ORDER BY p.title";
    	$where = '';
    	if(empty($search)){
    		return $sql.$order;
    	}
    	if(!empty($search['txtsearch'])){
    		$where.=" AND title LIKE '%".$search['txtsearch']."%'";
    	}
    	if($search['type']>-1){
    		$where.= " AND type = ".$search['type'];
    	}
    	if($search['status']>-1){
    		$where.= " AND status = '".$search['status']."'";
    	}
    	return $sql.$where.$order;
    }
    
    function getAllServiceFee($search){
    	$db=$this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,' - ',to_academic) AS academic,
    		    generation,create_date ,status FROM `rms_servicefee` WHERE 1";
    	$order=" ORDER BY id DESC ";
    	$where = '';
    	
    	return $db->fetchAll($sql.$where.$order);
    }
    
    
    public function addServiceCharge($_data){
    	
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{   		
    		$_arr = array(
    				'from_academic'=>$_data['from_year'],
    				'to_academic'=>$_data['to_year'],
    				'generation'=>$_data['generation'],
    				'note'=>$_data['note'],
    				'status'=>$_data['status'],
    				'create_date'=>$_data['create_date'],
    				'user_id'=>$this->getUserId()
    				);
    		$fee_id = $this->insert($_arr);
    		
    		$this->_name='rms_servicefee_detail';
    		$ids = explode(',', $_data['identity']);
    		$id_term =explode(',', $_data['iden_term']);
    		foreach ($ids as $i){
    			foreach ($id_term as $j){
    				$_arr = array(
    						'service_feeid'=>$fee_id,
    						'service_id'=>$_data['class_'.$i],
    						'payment_term'=>$j,
    						'price_fee'=>$_data['fee'.$i.'_'.$j],
    						'remark'=>$_data['remark'.$i]
    				);
    				$this->insert($_arr);
    			}
//     			$_arr = array(
//     					'service_feeid'=>$fee_id,
//     					'service_id'=>$_data['class_'.$i],
//     					'payment_term'=>4,
//     					'price_fee'=>$_data['monthly'.$i],
//     					'remark'=>$_data['remark'.$i]
//     			);
//     			$this->insert($_arr);
    		}
    		
    	    $db->commit();
    	    return true;
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		return false;
    	}
    }
    public function updateServiceCharge($_data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{    
    		$_arr = array(
    				'from_academic'=>$_data['from_year'],
    				'to_academic'=>$_data['to_year'],
    				'generation'=>$_data['generation'],
    				'note'=>$_data['note'],
    				'status'=>$_data['status'],
    				'create_date'=>$_data['create_date'],
    				'user_id'=>$this->getUserId()
    		);
//     		$fee_id = $this->insert($_arr);
    		$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    		$this->update($_arr, $where);
    
    		$this->_name='rms_servicefee_detail';
    		$where = "service_feeid = ".$_data['id'];
    		$this->delete($where);
    		$ids = explode(',', $_data['identity']);
    		$id_term =explode(',', $_data['iden_term']);
    		foreach ($ids as $i){
    			foreach ($id_term as $j){
    				$_arr = array(
    						'service_feeid'=>$_data['id'],
    						'service_id'=>$_data['class_'.$i],
    						'payment_term'=>$j,
    						'price_fee'=>$_data['fee'.$i.'_'.$j],
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
    function getServiceFeebyId($service_id){
    	
    	$db = $this->getAdapter();
    	$sql = "SELECT id,service_id,price_fee,payment_term,remark,(select title from rms_program_name where rms_program_name.service_id=rms_servicefee_detail.service_id limit 1)AS service_name FROM `rms_servicefee_detail` WHERE service_feeid=".$service_id." ORDER BY service_id ";
    		 
    	return $db->fetchAll($sql);
    	 
    }
    public function setServiceChargeExist($service_id,$pay_type){
    	$db = $this->getAdapter();
    	$sql = "SELECT servicefee_id,price FROM `rms_servicefee_detail` WHERE service_id=$service_id AND pay_type=$pay_type ";
    	return $db->fetchRow($sql);
    }
    public function getServiceChargeById($service_id){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM rms_servicefee WHERE id=$service_id LIMIT 1";
    	
    /*	$sql = "SELECT ser_cate_id,status,
    	sd.service_id,pay_type,price,remark,s.create_date
    	FROM `rms_program_name` AS s,`rms_servicefee_detail` AS sd
    	WHERE sd.service_id=s.service_id AND s.service_id=$service_id";*/
    	return $db->fetchRow($sql);
    
    }
}



