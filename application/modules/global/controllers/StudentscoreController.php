<?php
class Global_StudentscoreController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function start(){
		return ($this->getRequest()->getParam('limit_satrt',0));
	}
	public function indexAction(){
		try{
			$db = new Global_Model_DbTable_DbSubjectExam();
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title'],
						'status' => $_data['status_search']);
			}
			else{
				$search = array(
						'title' => '',
						'status' => -1);
			}
			$rs_rows = $db->getAllSujectName($search);
			$glClass = new Application_Model_GlobalClass();
			$rs = $glClass->getImgActive($rs_rows, BASE_URL, true);
			 
			 
			$list = new Application_Form_Frmtable();
			$collumns = array("SUBJECT_IN_KH","SUBJECT_IN_EN","MODIFY_DATE","STATUS","USER");
			$link=array(
					'module'=>'global','controller'=>'subject','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs,array('subject_titlekh'=>$link,'subject_titleen'=>$link));
	
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Global_Form_FrmSearchMajor();
		$frm =$frm->SubjectExam();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
		
	}
	function addAction(){ 
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_model = new Global_Model_DbTable_DbStudentScore();
			try {
				$rs =  $_model->addTuitionFee($_data);
				if(isset($_data['save_close'])){
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/accounting/fee");
				}else{
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/accounting/fee/add");
				}
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
				 
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$model = new Application_Model_DbTable_DbGlobal();
		$this->view->payment_term = $model->getAllPaymentTerm(null,1);
		$db_subjec=new Global_Model_DbTable_DbStudentScore();
		$this->view->rows_parent=$db_subjec->getParentName();
		$dbs=$this->view->row_years=$db_subjec->getStudyYears();
		$this->view->rows_group=$db_subjec->getGroupAll();
		//print_r($dbs);exit();
		 
	}
	function addoldAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_model = new Accounting_Model_DbTable_DbTuitionFee();
	
			//     		$result=$_model->getCondition($_data);
	
			try {
				$rs =  $_model->addTuitionFee($_data);
				if(isset($_data['save_close'])){
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/accounting/fee");
				}else{
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/accounting/fee/add");
				}
				Application_Form_FrmMessage::message("INSERT_SUCCESS");
		   
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		 
		$_model = new Application_Model_GlobalClass();
		$this->view->all_metion = $_model ->getAllMetionOption();
		$this->view->all_faculty = $_model ->getAllFacultyOption();
		$model = new Application_Model_DbTable_DbGlobal();
		$this->view->payment_term = $model->getAllPaymentTerm(null,1);
		 
		$frm = new Application_Form_FrmOther();
		$frm =  $frm->FrmAddDept(null);
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->add_dept = $frm;
	}
	function addsubjectAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$_dbmodel = new Global_Model_DbTable_DbSubjectExam();
			$data['status']=1;	
			$id=$_dbmodel->addNewSubjectExam($data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	function getSubjectAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$_dbmodel = new Global_Model_DbTable_DbStudentScore();
			$data=$_dbmodel->getSujectById($data['parent_id']);
			print_r(Zend_Json::encode($data));
			exit();
		}
	}
	function getStudentAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$_dbmodel = new Global_Model_DbTable_DbStudentScore();
			$data=$_dbmodel->getStudent($data['group_id']);
			print_r(Zend_Json::encode($data));
			exit();
		}
	}
	
}

