<?php
class Allreport_AccountingController extends Zend_Controller_Action {
	
    public function init()
    {    	
    	header('content-type: text/html; charset=utf8');
	}
	public function indexAction(){
	}
	public function rptAccountRecAction(){
		
	}
	function rptStudentpaymentAction(){
		
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
		$db = new Allreport_Model_DbTable_DbRptPayment();
		$this->view->row = $db->getStudentPayment($search);
		
	}
	function rptStudentpaymentdetailAction(){
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
		$db = new Allreport_Model_DbTable_DbRptPayment();
		$this->view->row = $db->getStudentPaymentDetail($search);
	
	}
	function rptPaymentrecieptdetailAction(){
		$id=$this->getRequest()->getParam("id");
		$db = new Allreport_Model_DbTable_DbRptPayment();
		$row = $db->getPaymentReciptDetail($id);
		$this->view->row = $row;
		$this->view->rr = $db->getStudentPaymentByid($id);
	
	}
	function rptInvoiceAction(){
	
	}
	function rptStudentListDetailPart1Action(){
	
	}
	function rptStudentListDetailPart2Action(){
	
	}
	function rptStudentListDetailPart3Action(){
	
	}
	public function rptTuitionFeeAction()
	{
	
	}
	function rptGepFeeAction(){
	
	}
	function rptGepListAction(){
	
	}
	function rptListOfItemAction(){
	
	}
	
}
