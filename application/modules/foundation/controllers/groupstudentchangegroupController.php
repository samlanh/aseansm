<?php
class Foundation_groupstudentchangegroupController extends Zend_Controller_Action {
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		$db_student= new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
		$rs_rows = $db_student->selectAllStudentChangeGroup();
		$list = new Application_Form_Frmtable();
		if(!empty($rs_rows)){
			} 
			else{
				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
			}
			$collumns = array("STUDENT_CODE","NAME_KH","NAME_EN","SEX","FROM_GROUP","TO_GROUP","MOVING_DATE","NOTE");
			$link=array(
					'module'=>'foundation','controller'=>'studentchangegroup','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('code'=>$link,'kh_name'=>$link,'en_name'=>$link));
			
	}
	function addAction(){
		if($this->getRequest()->isPost()){
			try{
				$_data = $this->getRequest()->getPost();
				$_add = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
 				$_add->addStudentChangeGroup($_data);
 				if(!empty($_data['save_close'])){
 					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/foundation/studentchangegroup");
 				}
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		
		$_add = new Foundation_Model_DbTable_DbStudentChangeGroup();
		
		$this->view->rs = $add =$_add->getAllStudentID();
		
		$this->view->row = $add =$_add->getAllStudentChangeGroup();
		
// 		$_db = new Application_Model_DbTable_DbGlobal();
// 		$this->view->degree = $rows = $_db->getAllFecultyName();
// 		$this->view->occupation = $row =$_db->getOccupation();
// 		$this->view->province = $row =$_db->getProvince();
	}
	public function editAction(){
		$id=$this->getRequest()->getParam("id");
		$db= new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
		$row = $this->view->rows = $db->getAllStudentChangeGroupById($id);
		
		
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				$data["id"]=$id;
				$db = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
				$row=$db->updateStudentChangeGroup($data);
				
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/foundation/studentchangegroup/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}	
		
		$_add = new Foundation_Model_DbTable_DbStudentChangeGroup();
		
		$this->view->rs = $add =$_add->getAllStudentID();
		
		$this->view->row = $add =$_add->getAllStudentChangeGroup();
	}
	function getToGroupAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
			$grade = $db->getStudentChangeGroup1ById($data['to_group']);
			print_r(Zend_Json::encode($grade));
			exit();
		}
	}
	
	function getAllStudentAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
			$student = $db->getAllStudentFromGroup($data['from_group']);
			print_r(Zend_Json::encode($student));
			exit();
		}
	}
	
}
