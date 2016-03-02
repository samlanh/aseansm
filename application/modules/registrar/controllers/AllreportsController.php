<?php
class Registrar_AllreportsController extends Zend_Controller_Action {
	
	
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	
	}

    public function indexAction(){
    	try{
    		if($this->getRequest()->isPost()){
    			$_data=$this->getRequest()->getPost();
    			$search = array(
    					'txtsearch' =>$_data['txtsearch'],
    					'start_date'=> $_data['from_date'],
    					'end_date'=> $_data['to_date']
    			);
    			$this->view->search = $search;
    		}
    		else{
    			$search = array(
    					'txtsearch' =>'',
    					'start_date'=> date('Y-m-01'),
    					'end_date'=>date('Y-m-d'),
    			);
    		}
    		
    		$db = new Registrar_Model_DbTable_DbReportStudentByuser();
    		$this->view->row = $db->getAllGernAndGepRegister($search);
    			
    	}catch(Exception $e){
    		Application_Form_FrmMessage::message("Application Error");
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
    }
    public function addAction(){
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
    					'start_date'=> date('Y-m-d'),
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
    public function wuRegisterAction()
    {
       $frm = new Registrar_Form_FrmRegister();
       $frm_register=$frm->FrmRegistarWU();
       Application_Model_Decorator::removeAllDecorator($frm_register);
       $this->view->frm_register = $frm_register;
       $key = new Application_Model_DbTable_DbKeycode();
       $this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
       
    }
    public function wuRequestAction()
    {
    	$frm = new Registrar_Form_FrmRegister();
    	$frm_request=$frm->FrmStudentRequest();
    	Application_Model_Decorator::removeAllDecorator($frm_request);
    	$this->view->frm_request = $frm_request;
    	$key = new Application_Model_DbTable_DbKeycode();
    	$this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
    }
    public function wuReceiptAction()
    {
    	$frm = new Registrar_Form_FrmRecept();
    	$frm_recept=$frm->FrmRecept();
    	Application_Model_Decorator::removeAllDecorator($frm_recept);
    	$this->view->frm_recept = $frm_recept;
    	//$key = new Application_Model_DbTable_DbKeycode();
    	//$this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
    }
    public function rptDailyAction()
    {
    
    }
}
