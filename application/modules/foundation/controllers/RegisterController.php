<?php
class Foundation_RegisterController extends Zend_Controller_Action {
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		$db_student= new Foundation_Model_DbTable_DbStudent();
		$rs_rows = $db_student->getAllStudent();
		$list = new Application_Form_Frmtable();
		if(!empty($rs_rows)){
				$glClass = new Application_Model_GlobalClass();
				$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
			} 
			else{
				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
			}
			$collumns = array("STUDENT NAME EU","STUDENT NAME KH","GENDER","BATCH","SESSION");
			$link=array(
					'module'=>'foundation','controller'=>'register','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('cate_name'=>$link,'title'=>$link));
			
	}
	function addAction(){
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $rows = $_db->getAllFecultyName();
		$this->view->occupation = $row =$_db->getOccupation();
		$this->view->province = $row =$_db->getProvince();
	}
	public function editAction(){
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $rows = $_db->getAllFecultyName();
		$this->view->occupation = $row =$_db->getOccupation();
		$this->view->province = $row =$_db->getProvince();
	}
	
}
