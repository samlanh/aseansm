<?php
class Accounting_FeeController extends Zend_Controller_Action {
	public function init()
    {    	
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
    public function indexAction()
    {
    	try{
    		if($this->getRequest()->isPost()){
    			$_data = $this->getRequest()->getPost();
    			$search = array(
    					'title' => $session_servicetype->txtsearch,
    					'txtsearch' => $_data['title'],
    					'status' => $_data['status_search'],
    					'type' => $_data['type'],
    			);
    		}
    		else{
    			$search='';
    		}
    		$db = new Accounting_Model_DbTable_DbTuitionFee();
    		$service= $db->getAllTuitionFee($search);
    		$model = new Application_Model_DbTable_DbGlobal();
    		$row=0;$indexterm=1;$key=0;
    		if(!empty($service)){
    			foreach ($service as $i => $rs) {
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
    							}elseif($payment_tran['payment_term']==3){
    								$term = $model->getAllPaymentTerm($payment_tran['payment_term']);
    								$rs_rows[$key_old]['semester'] = $payment_tran['tuition_fee'];
    								
    							}elseif($payment_tran['payment_term']==2){
    								$term = $model->getAllPaymentTerm($payment_tran['payment_term']);
    								$rs_rows[$key_old]['year'] = $payment_tran['tuition_fee'];
    							}
//     							else{
//     								$term = $model->getAllPaymentTerm($payment_tran['payment_type']);
//     								$rs_rows[$key_old]['full_fee'] = $payment_tran['tuition_fee'];
//     							}
//     							if($rs['class_id']==1){
//     									$rs_rows[$key_old]['faculty_name']= Application_Model_DbTable_DbGlobal::getAllMention($payment_tran['metion']);
//     							}
//     							else{
//     								$r_facu = $model->getDeptById($payment_tran['metion']);
//     								$rs_rows[$key_old]['faculty_name']= $r_facu['en_name'];
//     						 }			
    				}
    			}
    		}
    		else{
    			$rs_rows=array();
    			$result = Application_Model_DbTable_DbGlobal::getResultWarning();
    		}
    		$pay_term = $model->getAllPaymentTerm();
    		$payment_term='';
    		foreach ($pay_term as $value){
    			$payment_term.='"'.$value.'",';
    		}
    		$list = new Application_Form_Frmtable();
    		$collumns = array("YEARS","BATCH","CLASS_ID","QUARTER","SEMESTER","YEAR","TIME","CREATED_DATE","STATUS");
    		$link=array(
    				'module'=>'accounting','controller'=>'fee','action'=>'edit',
    		);
    		$urlEdit = BASE_URL ."/product/index/update";
    		$this->view->list=$list->getCheckList(0, $collumns, $rs_rows , array('academic'=>$link,'class'=>$link,'generation'=>$link));
    	}catch (Exception $e){
    		Application_Form_FrmMessage::message("APPLICATION_ERROR");
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
    	$frm = new Global_Form_FrmSearchMajor();
    	$frm = $frm->frmSearchTutionFee();
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->frm_search = $frm;
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
    public function addAction()
    {
    	if($this->getRequest()->isPost()){
    		try {
	    		$_data = $this->getRequest()->getPost();
	    		$_model = new Accounting_Model_DbTable_DbTuitionFee();
	    		$rs =  $_model->addTuitionFee($_data);
    		if(!empty($rs))Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/accounting/fee/add");
    		}catch(Exception $e){
    			Application_Form_FrmMessage::message("INSERT_FAIL");
	   			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		}
    	}
    	
    	$_model = new Application_Model_GlobalClass();
    	$this->view->all_metion = $_model ->getAllMetionOption();
    	$this->view->all_faculty = $_model ->getAllFacultyOption();
    	$model = new Application_Model_DbTable_DbGlobal();
    	$this->view->payment_term = $model->getAllPaymentTerm();
    	
    	$frm = new Application_Form_FrmOther();
    	$frm =  $frm->FrmAddDept(null);
    	Application_Model_Decorator::removeAllDecorator($frm);
    	$this->view->add_dept = $frm;
    }
 	
    public function editAction()
	{

		if($this->getRequest()->isPost()){
			try {
				$_data = $this->getRequest()->getPost();
				$_model = new Accounting_Model_DbTable_DbTuitionFee();
				$rs =  $_model->updateTuitionFee($_data);
				if(!empty($rs))Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/accounting/fee");
			}catch(Exception $e){
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		 
		$_model = new Application_Model_GlobalClass();
		$this->view->all_metion = $_model ->getAllMetionOption();
		$this->view->all_faculty = $_model ->getAllFacultyOption();
		$model = new Application_Model_DbTable_DbGlobal();
		$this->view->payment_term = $model->getAllPaymentTerm();
		 
		$frm = new Application_Form_FrmOther();
		$frm =  $frm->FrmAddDept(null);
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->add_dept = $frm;
		
		$db=new Accounting_Model_DbTable_DbTuitionFee();
		$id=$this->getRequest()->getParam("id");
		$this->view->rs =$db->getFeeById($id);

		$row=0;$indexterm=1;$key=0;

				$rows = $db->getFeeDetailById($id);
				$fee_row=1;$rs_rows=array();
				if(!empty($rows))foreach($rows as $payment_tran){
					if($payment_tran['payment_term']==1){
						
						$rs_rows[$key] = array(
								'class_id'=>$payment_tran['class_id'],
								'quarter'=>$payment_tran['tuition_fee'],
								'semester'=>'',
								'year'=>'',
								'note'=>$payment_tran['remark'],
								'status'=>$payment_tran['status'],
						);
						
						//$rs_rows[$key]['quarter'] = $payment_tran['tuition_fee'];
						$key_old=$key;
						$key++;
					}elseif($payment_tran['payment_term']==3){
						$rs_rows[$key_old]['year'] = $payment_tran['tuition_fee'];
		
					}elseif($payment_tran['payment_term']==2){
						$rs_rows[$key_old]['semester'] = $payment_tran['tuition_fee'];
					}
	
				}
				
			   $this->view->rows =$rs_rows;
			   
	}
    
}
