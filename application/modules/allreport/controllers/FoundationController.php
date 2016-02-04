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
		$db = new Allreport_Model_DbTable_DbStudent();
		$row = $db->getAllStudentre();
		$this->view->rs = $row;
	}
	public function rptStudentInfoAction()
	{
		$db = new Allreport_Model_DbTable_DbStudent();
		$row = $db->getStudentInfo();
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
					'txtsearch' => $_data['txtsearch'],
					'searchby' => $_data['searchby'],
			);
			
		}
		else{
			$search='';
		}		
		$db = new Allreport_Model_DbTable_DbRptGroup();
		$row = $db->getStudentGroup($id,$search);
		$this->view->rs = $row;
		$rs= $db->getGroupDetailByID($id);
		$this->view->rr = $rs;
	
	}
	public function studentGroupAction()
	{
		$db = new Allreport_Model_DbTable_DbRptGroup();
			$rs= $db->getGroupDetail();
			$list = new Application_Form_Frmtable();
			
			if(!empty($rs)){
			}
			else{
				$result = Application_Model_DbTable_DbGlobal::getResultWarning();
			}
			$collumns = array("GROUP NAME","YEARS","SEMESTER","DEGREE","GRADE","SESSION","ROOM","START DATE","END DATE","NOTE","STATUS","ចំនួនសិស្ស");
			$link=array(
					'module'=>'allreport','controller'=>'foundation','action'=>'rpt-student-group',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs,array('group_code'=>$link,'room_name'=>$link));
	
	}
	
}

