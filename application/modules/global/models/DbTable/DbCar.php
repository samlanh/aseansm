<?php

class Global_Model_DbTable_DbCar extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_car';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    
    function addcar($data){
    	$arr=array(
    			'carid'=>$data['Car_ID'],
    			'carname'=>$data['Car_Name'],
    			'drivername'=>$data['Driver_Name'],
    			'tel'=>$data['Tel'],
    			'zone'=>$data['Zone'],
    			'note'=>$data['Note'],
    			'userid'=>$this->getUserId(),
    			'status'=>$data['status']
    			);  	
    	$this->insert($arr);   	
    }
    function getAllCars($search=null){
    	$db = $this->getAdapter();
    	$sql = " SELECT id,carid,carname,drivername,tel,zone,note,status
    	FROM rms_car
    	WHERE 1 ";
    	$order=" order by carname";
    	$where = '';
    	if(!empty($search['title'])){
    		$where.=" AND ( carname LIKE '%".$search['title']."%') ";
    	}
    	if($search['status']>-1){
    		$where.= " AND is_active = ".$db->quote($search['status']);
    	}
    	return $db->fetchAll($sql.$where.$order);
    }
    
    public function getCarById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM rms_car WHERE id = ".$id;
    	$sql.=" LIMIT 1";
    	$row=$db->fetchRow($sql);
    	return $row;
    }
    public function updateCar($data){
    	$_arr=array(
    			
    			'carid'=>$data['Car_ID'],
    			'carname'=>$data['Car_Name'],
    			'drivername'=>$data['Driver_Name'],
    			'tel'=>$data['Tel'],
    			'zone'=>$data['Zone'],
    			'note'=>$data['Note'],
    			'status'=>$data['status']
    			
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $data['id']);
    	$this->update($_arr, $where);
    }
    
    
}
	

