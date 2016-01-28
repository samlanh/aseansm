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
		$db = new 	Foundation_Model_DbTable_DbStudent();
		
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						
						'degree' => $_data['degree'],
						'grade' => $_data['grade'],
						'session' => $_data['session']);
				$rs =$db->getSearchStudent($search);
			}else{
				$search = array(
						'degree' => 1,
						'grade' => 0,
						'session' => 0);
				$rs = $db->getSearchStudent($search);
			}
			$this->view->rs = $rs;	
			$this->view->value=$search;
	
		}catch(Exception $e){
			Application_Form_FrmMessage::message("APPLICATION_ERROR");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $_db->getAllFecultyName();
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
