<?php
class Accounting_SuspendserviceController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	private $type = array(1=>'service',2=>'program');
	public function init()
	{
		header('content-type: text/html; charset=utf8');
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function start(){
		return ($this->getRequest()->getParam('limit_satrt',0));
	}
	public function indexAction(){
		try{
			$db =  new Accounting_Model_DbTable_DbSuspendservice();
			$rs = $db->getStudentSuspendService();
			if(!empty($rs)){
				$list = new Application_Form_Frmtable();
				$collumns = array("SUSPEND No","ឈ្មោះ","NAME","GENDER","DATE");
				$link=array(
						'module'=>'accounting','controller'=>'suspendservice','action'=>'edit',
				);
				$this->view->list=$list->getCheckList(0, $collumns, $rs,array('suspend_no'=>$link,'kh_name'=>$link));
			}
			else{
				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
			}
			
		}catch (Exception $e){
			Application_Form_FrmMessage::message("APPLICATION_ERROR");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
		
	}
public function addAction(){
	if($this->getRequest()->isPost()){
		$_data = $this->getRequest()->getPost();
		try{
				$db = new Accounting_Model_DbTable_DbSuspendservice();
				$row = $db->addSuspendservice($_data);
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$frm = new Accounting_Form_FrmServicesuspend();
		$frm_servicesuspend=$frm->FrmServiceSuspend();
		Application_Model_Decorator::removeAllDecorator($frm_servicesuspend);
		$this->view->frm_servicesuspend = $frm_servicesuspend;
		 
		$db = new Accounting_Model_DbTable_DbSuspendservice();
		$this->view->rs = $db->getAllStudentCode();
		 
		$_model = new Application_Model_GlobalClass();
		$this->view->all_service = $_model->getAllServiceItemOption(2);
	
}
public function editAction(){
	$id=$this->getRequest()->getParam("id");
	
	if($this->getRequest()->isPost())
	{
		$_data = $this->getRequest()->getPost();
		try{
			$_db = new Accounting_Model_DbTable_DbSuspendservice();
			$row = $_db->editSuspendService($_data);
			Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/accounting/suspendservice/index");
		}catch(Exception $e){
			Application_Form_FrmMessage::message("EDIT_FAIL");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	$frm = new Accounting_Form_FrmServicesuspend();
	$frm_servicesuspend=$frm->FrmServiceSuspend();
	Application_Model_Decorator::removeAllDecorator($frm_servicesuspend);
	$this->view->frm_servicesuspend = $frm_servicesuspend;
	
	$_db = new Accounting_Model_DbTable_DbSuspendservice();
	$this->view->rs = $_db->getAllStudentCode();
	$this->view->id = $_db->getStudentSuspendServiceByID($id);
	$this->view->service = $_db->getSuspendServiceByID($id);
	
	$_model = new Application_Model_GlobalClass();
	$this->view->all_service = $_model->getAllServiceItemOption(2);
	
}

function getStudentAction(){
	if($this->getRequest()->isPost()){
		$data=$this->getRequest()->getPost();
		$db = new Accounting_Model_DbTable_DbSuspendservice();
		$studentinfo = $db->getAllStudentInfo($data['studentid']);
		//array_unshift($makes, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
		print_r(Zend_Json::encode($studentinfo));
		exit();
	}
}

}