<?php
class Allreport_FoundationController extends Zend_Controller_Action {
	
	
public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction()
	{	
		
	}
	public function rptExamDegreeAction()
	{
	}
	public function rptFinalExamsAction()
	{
	
	}
	

	public function rptStuidAction()
	{
	
	}
	public function rptFoundationAction()
	{
	
	}
	public function rptDropOutAction()
	{
	
	}
	public function rptTotalAttendanceAction()
	{
	
	}
	public function rptAmountStudentAction()
	{
	
	}
	
	public function rptScorelistAction()
	{
	
	}
	public function rptStudentAttendanceAction()
	{
	
	}
	public function rptFullResultAction()
	{
	
	}
	public function rptStudentListAction()
	{
		if($this->getRequest()->isPost()){
			$_data=$this->getRequest()->getPost();
			$search = array(
					'txtsearch' => $_data['txtsearch']
			);
		
		}
		else{
			$search = array(
					'txtsearch' => ""
			);
		}
		$db = new Allreport_Model_DbTable_DbStudent();
		$row = $db->getAllStudentre($search);
		$this->view->rs = $row;
	}
	public function rptStudentInfoAction()
	{
		if($this->getRequest()->isPost()){
			$_data=$this->getRequest()->getPost();
			$search = array(
					'txtsearch' => $_data['txtsearch']
			);
		
		}
		else{
			$search = array(
					'txtsearch' => ""
			);
		}
		$db = new Allreport_Model_DbTable_DbStudent();
		$row = $db->getStudentInfo($search);
		$this->view->rs = $row;
	}
	public function rptAllresultAction()
	{
		
	}
	public function rptCertificateAction()
	{
	
	}
	public function rptStudentGroupAction()
	{	
		$id=$this->getRequest()->getParam("id");
		if($this->getRequest()->isPost()){
			$_data=$this->getRequest()->getPost();
			$search = array(
					'txtsearch' => $_data['txtsearch']
			);
			
		}
		else{
			$search = array(
					'txtsearch' => ""
			);
		}		
		$db = new Allreport_Model_DbTable_DbRptGroup();
		$row = $db->getStudentGroup($id,$search);
		$this->view->rs = $row;
		$rs= $db->getGroupDetailByID($id);
		$this->view->rr = $rs;
	
	}
	public function studentGroupAction()
	{	
		if($this->getRequest()->isPost()){
			$_data=$this->getRequest()->getPost();
			$search = array(
					'txtsearch' => $_data['txtsearch']
			);
		
		}
		else{
			$search = array(
					'txtsearch' => ""
			);
		}
		$db = new Allreport_Model_DbTable_DbRptGroup();
		$rs= $db->getGroupDetail($search);
		$this->view->rs = $rs;	
	
	}
	
}

