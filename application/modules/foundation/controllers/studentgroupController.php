<?php
class Foundation_StudentGroupController extends Zend_Controller_Action {
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
			
	}
	function addAction(){
		$_model = new Application_Model_GlobalClass();
		$this->view->service_options = $_model->getAllServiceItemOption(1);
		
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $rows = $_db->getAllFecultyName();
	}
	public function editAction(){
		
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
