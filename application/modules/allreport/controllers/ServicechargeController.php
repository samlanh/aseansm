<?php
class Allreport_servicechargeController extends Zend_Controller_Action {
	
	
public function init()
    {    	
     /* Initialize action controller here */
    	//header('content-type: text/html; charset=utf8');
	}
	
	public function indexAction(){
// 		$group= new allreport_Model_DbTable_DbRptGroup();
// 		$this->view->rs = $rs_rows = $group->getAllGroup();
			
	}
	public function rptServiceChargeAction(){
		
// 		$group= new allreport_Model_DbTable_DbRptServiceCharge();
// 		$this->view->rs = $rs_rows = $group->getAllServiceCharge();
		
		$db = new Allreport_Model_DbTable_DbRptServiceCharge();
		$service= $db->getAllServiceFee();
		
		$model = new Application_Model_DbTable_DbGlobal();
		$row=0;$indexterm=1;$key=0;$rs_rows=array();
		if(!empty($service)){
			foreach ($service as $i => $rs) {
				$rows = $db->getServiceFeebyId($rs['id']);
				$fee_row=1;
				if(!empty($rows))foreach($rows as $payment_tran){
					if($payment_tran['payment_term']==1){
						$rs_rows[$key]=$this->headAddRecordTuitionFee($rs,$key);
						$term = $model->getAllPaymentTerm($fee_row);
						 
						$rs_rows[$key]['service_id'] = $payment_tran['service_id'];
						$rs_rows[$key]['quarter'] = $payment_tran['price_fee'];
						$key_old=$key;
						$key++;
					}elseif($payment_tran['payment_term']==2){
						$term = $model->getAllPaymentTerm($payment_tran['payment_term']);
						$rs_rows[$key_old]['semester'] = $payment_tran['price_fee'];
						 
					}elseif($payment_tran['payment_term']==3){
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
	
	public function headAddRecordTuitionFee($rs,$key){
		$result[$key] = array(
				'id' 	  => $rs['id'],
				'academic'=> $rs['academic'],
				'generation'=> $rs['generation'],
				'service_id'=>'',
				'quarter'=>'',
				'semester'=>'',
				'year'=>'',
				'date'=>$rs['create_date'],
				'status'=> Application_Model_DbTable_DbGlobal::getAllStatus($rs['status'])
		);
		return $result[$key];
	}
	
	
}

