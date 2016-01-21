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
			$collumns = array("STUDENT NAME EU","STUDENT NAME KH","GENDER","BATCH","SEMESTER");
			$link=array(
					'module'=>'foundation','controller'=>'register','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('stu_enname'=>$link,'stu_khname'=>$link));
			
	}
	function addAction(){
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $rows = $_db->getAllFecultyName();
		$this->view->occupation = $row =$_db->getOccupation();
		$this->view->province = $row =$_db->getProvince();
	}
	public function editAction(){
		$id=$this->getRequest()->getParam("id");
		$db= new Foundation_Model_DbTable_DbStudent();
		$row = $db->getStudentById($id);
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				$data["id"]=$id;
				$db = new Foundation_Model_DbTable_DbStudent();
				$row=$db->updateStudent($data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/foundation/register/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}	
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $rows = $_db->getAllFecultyName();
		$this->view->occupation = $row =$_db->getOccupation();
		$this->view->province = $row =$_db->getProvince();
		$this->view->rs = $db->getStudentById($id);
	}
	
}
