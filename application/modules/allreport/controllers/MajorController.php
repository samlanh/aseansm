<?php
class Allreport_carController extends Zend_Controller_Action {
	
	
public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
	public function indexAction(){
// 		$group= new allreport_Model_DbTable_DbRptGroup();
// 		$this->view->rs = $rs_rows = $group->getAllGroup();
			
	}
	public function rptCarAction(){
		
		if($this->getRequest()->isPost()){
			$_data=$this->getRequest()->getPost();
			$search = array(
					'txtsearch' => $_data['txtsearch'],
					);
		}
		else{
			$search=array(
					'Car_ID'=>'',
					'Car_Name'=>'',
					'Driver_Name' =>'',
					'txtsearch' => '',
					);;
		}
		$group= new Allreport_Model_DbTable_DbRptCar();
		$this->view->rs = $rs_rows = $group->getAllCar($search);
			
	}
}

