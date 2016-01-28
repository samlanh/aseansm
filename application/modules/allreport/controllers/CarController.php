<?php
class Allreport_carController extends Zend_Controller_Action {
	
	
public function init()
    {    	
     /* Initialize action controller here */
    	//header('content-type: text/html; charset=utf8');
	}
	
	public function indexAction(){
// 		$group= new allreport_Model_DbTable_DbRptGroup();
// 		$this->view->rs = $rs_rows = $group->getAllGroup();
			
	}
	public function rptCarAction(){
		$group= new Allreport_Model_DbTable_DbRptCar();
		$this->view->rs = $rs_rows = $group->getAllCar();
			
	}
}

