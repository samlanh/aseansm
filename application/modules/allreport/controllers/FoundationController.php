<?php
class Allreport_FoundationController extends Zend_Controller_Action {
	
	
public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
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
		$db = new allreport_Model_DbTable_DbStudent();
		$row = $db->getAllStudentre();
		$this->view->rs = $row;
	}
	public function rptAllresultAction()
	{
		
	}
	public function rptCertificateAction()
	{
	
	}
	
	
}

