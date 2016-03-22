<?php
class Global_GroupController extends Zend_Controller_Action {
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	
	}
	public function indexAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title']);
			}
			else{
				$search = array(
						'title' => '');
			}
			$db = new Global_Model_DbTable_DbGroup();
			$rs_rows= $db->getAllGroup($search);
			$list = new Application_Form_Frmtable();
			
			$collumns = array("GROUP_CODE","YEARS","SEMESTER","DEGREE","GRADE","SESSION","ROOM_NAME","START_DATE","END_DATE","NOTE");
			
			$link=array(
					'module'=>'global','controller'=>'group','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('group_code'=>$link,'academic'=>$link,'degree'=>$link,'grade'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			echo $e->getMessage();
		}
		$frm = new Application_Form_FrmOther();
		$this->view->add_major = $frm->FrmAddMajor(null);
		$frm = new Global_Form_FrmSearchMajor();
		$frm = $frm->frmSearchTeacher();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
	}
	function addAction(){
		if($this->getRequest()->isPost()){
			try {
				$data = $this->getRequest()->getPost();
				$db= new Global_Model_DbTable_DbGroup();
				$db->AddNewGroup($data);
				if(!empty($data['save_close'])){
					Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !", "/global/group");
				}
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
		$_db = new Global_Model_DbTable_DbGroup();
		$this->view->degree = $rows = $_db->getAllFecultyName();
		
		
		
		$model = new Application_Model_DbTable_DbGlobal();
		$this->view->payment_term = $model->getAllPaymentTerm(null,1);
		
		$room = $model->getAllRoom();
		array_unshift($room, array ( 'id' => -1,'name' => 'បន្ថែមថ្មី'));
		$this->view->room = $room;

		$_model = new Global_Model_DbTable_DbGroup();
		$this->view->subject = $_model->getAllSubjectStudy();
		
		$this->view->year = $_model->getAllYear();
		//print_r($this->view->year);exit();
	}
		
		
	function editAction(){
		$db= new Global_Model_DbTable_DbGroup();
		
		if($this->getRequest()->isPost()){
			try {
				$data = $this->getRequest()->getPost();
				
				$db->updateGroup($data);
				if(!empty($data['save'])){
					Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !", "/global/group/index");
				}
				//Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
			} catch (Exception $e) {
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
		$id=$this->getRequest()->getParam("id");
		
		$this->view->rs = $db->getGroupById($id);
		
		$this->view->row = $db->getGroupSubjectById($id);
		
// 		print_r($this->view->row);exit();
		
		
		$db = new Global_Model_DbTable_DbGroup();
		$this->view->degree = $rows = $db->getAllFecultyName();
		
		$model = new Application_Model_DbTable_DbGlobal();
		
		$faculty =  $model->getAllMajor();
		array_unshift($faculty, Array('id'=> -1 ,'name' =>'Add New'));
		$this->view->faculty =$faculty;
	
		$room = $model->getAllRoom();
		array_unshift($room, Array('id'=> -1 ,'name' =>'Add New'));
		$this->view->room =$room;
	
		
		$_model = new Global_Model_DbTable_DbGroup();
		$this->view->subject = $_model->getAllSubjectStudy();
		
		$this->view->year = $_model->getAllYear();
	}
	
	
	function addRoomAction(){
		if($this->getRequest()->isPost()){
			try{
				$data=$this->getRequest()->getPost();
				$db = new Global_Model_DbTable_DbGroup();
				$room = $db->addNewRoom($data);
				print_r(Zend_Json::encode($room));
				exit();
			}catch (Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
			
		}
	}
	
	
	
	
	
	
}

