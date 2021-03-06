<?php
class Foundation_groupstudentchangegroupController extends Zend_Controller_Action {
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		if($this->getRequest()->isPost()){
			$search = $this->getRequest()->getPost();
		}else{
			$search=array(
					'title'	=>'',
					'study_year' => '',
					'grade_bac'	=>'',
					'session' => '',
			);
		}
		$db_student= new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
		$rs_rows = $db_student->selectAllStudentChangeGroup($search);
		$list = new Application_Form_Frmtable();
		
		$form=new Registrar_Form_FrmSearchInfor();
		$forms=$form->FrmSearchRegister();
		Application_Model_Decorator::removeAllDecorator($forms);
		$this->view->form_search=$form;
		
		$collumns = array("FROM_GROUP","ACADEMIC_YEAR","GRADE","SESSION","TO_GROUP","ACADEMIC_YEAR","GRADE","SESSION","MOVING_DATE","NOTE");
		$link=array(
				'module'=>'foundation','controller'=>'groupstudentchangegroup','action'=>'edit',
		);
		$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('group_code'=>$link,'grade'=>$link,'session'=>$link,'to_group_code'=>$link));

		$this->view->adv_search = $search;
		
		
		
	}
	function addAction(){
		if($this->getRequest()->isPost()){
			try{
				$data = $this->getRequest()->getPost();
// 				print_r($data);exit();
				$_add = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
 				$_add->addGroupStudentChangeGroup($data);
 				if(!empty($data['save_close'])){
 					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/foundation/groupstudentchangegroup");
 				}
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		
		$_add = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
		
		$this->view->row = $add =$_add->getfromGroup();
		$this->view->rs = $add =$_add->gettoGroup();
		
		$dbstudent = new Foundation_Model_DbTable_DbGroup();
		$this->view->academy = $dbstudent->getAllYear();
		
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $_db->getAllFecultyName();
		
		$db=new Application_Model_DbTable_DbGlobal();
		$this->view->rs_session=$db->getSession();
		
		$group = new Foundation_Model_DbTable_DbGroup();
		
		$group_option = $group->getGroupToEdit();
		array_unshift($group_option, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
		$this->view->group = $group_option;
		
		$room =  $group->getRoom();
		array_unshift($room, array ( 'room_id' => 0, 'room_name' => 'Select Room') );
		$this->view->room = $room;
		
// 		$_db = new Application_Model_DbTable_DbGlobal();
// 		$this->view->degree = $rows = $_db->getAllFecultyName();
// 		$this->view->occupation = $row =$_db->getOccupation();
// 		$this->view->province = $row =$_db->getProvince();
	}
	public function editAction(){
		$id=$this->getRequest()->getParam("id");
		if($this->getRequest()->isPost())
		{
			try{
				$data = $this->getRequest()->getPost();
				//$data["id"]=$id;
				//print_r($data);exit();
				$db = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
				$row=$db->updateStudentChangeGroup($data,$id);
				
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/foundation/groupstudentchangegroup/index");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}	
		$db= new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
		$result = $db->getAllGroupStudentChangeGroupById($id);
		$this->view->rs = $result;
		
		$this->view->studentpass = $db->selectStudentPass($result['from_group']);
		
		$_add = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
		
		$this->view->row = $add =$_add->getfromGroup();
		
		$this->view->rows = $add =$_add->gettoGroup();
		
		$dbstudent = new Foundation_Model_DbTable_DbGroup();
		$this->view->academy = $dbstudent->getAllYear();
		
		$_db = new Application_Model_DbTable_DbGlobal();
		$this->view->degree = $_db->getAllFecultyName();
		
		$db=new Application_Model_DbTable_DbGlobal();
		$this->view->rs_session=$db->getSession();
		
		$group = new Foundation_Model_DbTable_DbGroup();
		
		$group_option = $group->getGroupToEdit();
		array_unshift($group_option, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
		$this->view->group = $group_option;
		
		$room =  $group->getRoom();
		array_unshift($room, array ( 'room_id' => 0, 'room_name' => 'Select Room') );
		$this->view->room = $room;
	}
	
	
	function getToGroupAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
			$grade = $db->getGroupStudentChangeGroup1ById($data['to_group'],$data['type']);
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
	
	function getAllStudentUpdateAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Foundation_Model_DbTable_DbGroupStudentChangeGroup();
			$student = $db->getAllStudentFromGroupUpdate($data['from_group']);
			print_r(Zend_Json::encode($student));
			exit();
		}
	}	
	
}











