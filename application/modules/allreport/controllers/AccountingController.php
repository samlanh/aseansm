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
		try{
			if($this->getRequest()->isPost()){
					$_data=$this->getRequest()->getPost();
					$search = array(
							'txtsearch' =>$_data['txtsearch'],
							'start_date'=> $_data['from_date'],
		      				'end_date'=> $_data['to_date']
					);
					
				}
				else{
					$search = array(
							'txtsearch' =>'',
							'start_date'=> date('Y-m-01'),
	                        'end_date'=>date('Y-m-d'),
					);
				}
			$this->view->search = $search;
			$db = new Allreport_Model_DbTable_DbRptPayment();
			$this->view->row = $db->getStudentPayment($search);
			
		}catch(Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	function  rptPaymentdetailbytypeAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				//print_r($_data); exit();
				$search = array(
						'txtsearch' =>$_data['txtsearch'],
						'start_date'=> $_data['from_date'],
						'end_date'=> $_data['to_date'],
						'service_type'=>$_data['service']
				);
		
			}
			else{
				$search = array(
						'txtsearch' =>'',
						'start_date'=> date('Y-m-d'),
						'end_date'=>date('Y-m-d'),
						'service_type'=>0
				);
			}
			$db = new Allreport_Model_DbTable_DbRptPayment();
			$this->view->row = $db->getPaymentDetailByType($search);
			$this->view->service = $db->getService();
			$this->view->search = $search;
		}catch(Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	
	function rptStudentpaymentdetailAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'txtsearch' =>$_data['txtsearch'],
						'start_date'=> $_data['from_date'],
	      				'end_date'=> $_data['to_date']
				);
				
			}
			else{
				$search = array(
						'txtsearch' =>'',
						'start_date'=> date('Y-m-01'),
                        'end_date'=>date('Y-m-d'),
				);
			}
			$db = new Allreport_Model_DbTable_DbRptPayment();
			$this->view->row = $db->getStudentPaymentDetail($search);
			$this->view->service = $db->getService();
			$this->view->search = $search;
		}catch(Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
	}

	function rptPaymentrecieptdetailAction(){
		$id=$this->getRequest()->getParam("id");
		$db = new Allreport_Model_DbTable_DbRptPayment();
		$row = $db->getPaymentReciptDetail($id);
		
		$this->view->row = $row;
		$this->view->rr = $db->getStudentPaymentByid($id);
	
	}
	function  rptSuspendserviceAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'txtsearch' => $_data['txtsearch'],
				);
			}
			else{
				$search='';
			}
		$db = new Allreport_Model_DbTable_DbSuspendService();
		$this->view->rs = $db->getStudetnSuspendServiceDetail($search);
		}catch (Exception $e){
			Application_Form_FrmMessage::message("APPLICATION_ERROR");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
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
	public function rptCarAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'txtsearch' => $_data['txtsearch'],
						'searchby'=> $_data['searchby'],
				);
			}
			else{
				$search='';
			}	
			$db = new Allreport_Model_DbTable_DbRptCar();
			$this->view->rs = $db->getAllCar($search);
		}catch(Exception $e){
			Application_Form_FrmMessage::message("APPLICATION_ERROR");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	
	public function rptFeeAction(){
	
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
	
		$db = new Allreport_Model_DbTable_DbRptFee();
		$group= new Allreport_Model_DbTable_DbRptFee();
		$rs_rows = $group->getAllTuitionFee($search);
	
		$model = new Application_Model_DbTable_DbGlobal();
		$row=0;$indexterm=1;$key=0;
		if(!empty($rs_rows)){
			foreach ($rs_rows as $i => $rs) {
				$rows = $db->getFeebyOther($rs['id']);
				$fee_row=1;
				if(!empty($rows))foreach($rows as $payment_tran){
					if($payment_tran['payment_term']==1){
						$rs_rows[$key]=$this->headAddRecordTuitionFee($rs,$key);
						$term = $model->getAllPaymentTerm($fee_row);
	
						$rs_rows[$key]['status'] = Application_Model_DbTable_DbGlobal::getAllStatus($payment_tran['status']);
						$rs_rows[$key]['class'] = $payment_tran['class'];
						$rs_rows[$key]['quarter'] = $payment_tran['tuition_fee'];
						$key_old=$key;
						$key++;
					}elseif($payment_tran['payment_term']==2){
						$term = $model->getAllPaymentTerm($payment_tran['payment_term']);
						$rs_rows[$key_old]['semester'] = $payment_tran['tuition_fee'];
	
					}elseif($payment_tran['payment_term']==3){
						$term = $model->getAllPaymentTerm($payment_tran['payment_term']);
						$rs_rows[$key_old]['year'] = $payment_tran['tuition_fee'];
					}
				}
			}
		}
		else{
			$rs_rows=array();
			$result = Application_Model_DbTable_DbGlobal::getResultWarning();
		}
		$this->view->rs = $rs_rows;
	}
	
	public function headAddRecordTuitionFee($rs,$key){
		$result[$key] = array(
				'id' 	  => $rs['id'],
				'academic'=> $rs['academic'],
				'generation'=> $rs['generation'],
				'class'=>'',
				'quarter'=>'',
				'semester'=>'',
				'year'=>'',
				'time'=>$rs['time'],
				'date'=>$rs['create_date'],
				'status'=>''
		);
		return $result[$key];
	}
	public function rptServiceChargeAction(){
	
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
	
		$db = new Allreport_Model_DbTable_DbRptServiceCharge();
		$service= $db->getAllServiceFee($search);
	
		$model = new Application_Model_DbTable_DbGlobal();
		$row=0;$indexterm=1;$key=0;$rs_rows=array();
		if(!empty($service)){
			foreach ($service as $i => $rs) {
				$rows = $db->getServiceFeebyId($rs['id']);
				$fee_row=1;
				if(!empty($rows))foreach($rows as $payment_tran){
					if($payment_tran['payment_term']==1){
						$rs_rows[$key]=$this->headAddRecordServiceFee($rs,$key);
						$term = $model->getAllPaymentTerm($fee_row);
	
	
						$rs_rows[$key]['service_name'] = $payment_tran['service_name'];
						$rs_rows[$key]['monthly'] = $payment_tran['price_fee'];
						$key_old=$key;
						$key++;
					}elseif($payment_tran['payment_term']==2){
						$term = $model->getAllPaymentTerm($payment_tran['payment_term']);
						$rs_rows[$key_old]['quarter'] = $payment_tran['price_fee'];
							
					}elseif($payment_tran['payment_term']==3){
						$term = $model->getAllPaymentTerm($payment_tran['payment_term']);
						$rs_rows[$key_old]['semester'] = $payment_tran['price_fee'];
					}
					elseif($payment_tran['payment_term']==4){
						$term = $model->getAllPaymentTerm($payment_tran['payment_term']);
						$rs_rows[$key_old]['year'] = $payment_tran['price_fee'];
					}
				}
			}
		}
		else{
			$rs_rows = array();
			$result = Application_Model_DbTable_DbGlobal::getResultWarning();
		}
	
		$this->view->rs = $rs_rows;
	}
	
	public function headAddRecordServiceFee($rs,$key){
		$result[$key] = array(
				'id' 	  => $rs['id'],
				'academic'=> $rs['academic'],
				'generation'=> $rs['generation'],
				'monthly'=>'',
				'quarter'=>'',
				'semester'=>'',
				'year'=>'',
				'date'=>$rs['create_date'],
				'status'=> Application_Model_DbTable_DbGlobal::getAllStatus($rs['status'])
		);
		return $result[$key];
	}
}
