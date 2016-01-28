<?php
class Allreport_studentchangegroupController extends Zend_Controller_Action {
	
	
public function init()
    {    	
     /* Initialize action controller here */
    	//header('content-type: text/html; charset=utf8');
	}
	
	public function indexAction(){
// 		$group= new allreport_Model_DbTable_DbRptGroup();
// 		$this->view->rs = $rs_rows = $group->getAllGroup();
			
	}
	public function rptStudentChangeGroupAction(){
		$group= new allreport_Model_DbTable_DbRptStudentChangeGroup();
		$this->view->rs = $rs_rows = $group->getAllStudentChangeGroup();
			
	}
}

