<?php
class Foundation_ApplicationGepController extends Zend_Controller_Action {
	
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
			} 
			else{
				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
			}
			$collumns = array("STUDENT NAME","ឈ្មោះសិស្ស","SEX","GRADE","NATIONALITY","DOB","PHONE","EMAIL","STATUS");
			$link=array(
					'module'=>'foundation','controller'=>'register','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('stu_enname'=>$link,'stu_khname'=>$link));
			
	}
	function addAction(){
	
		$db= new Foundation_Model_DbTable_DbApplication();
		$this->view->serviecename = $rows = $db->getServiceType(1);
		$_hour = new Application_Model_GlobalClass();
		$this->view->hour= $row = $_hour->getHours();


	}
	public function editAction(){
		$id=$this->getRequest()->getParam("id");
		
		$db= new Foundation_Model_DbTable_DbApplication();
		$this->view->serviecename = $rows = $db->getServiceType(1);
		$_hour = new Application_Model_GlobalClass();
		$this->view->hour= $row = $_hour->getHours();
	}
	function getGradeAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbStudent();
			$grade = $db->getAllGrade($data['dept_id']);
			//print_r($grade);exit();
			//array_unshift($makes, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
			print_r(Zend_Json::encode($grade));
			exit();
		}
	}
	
}