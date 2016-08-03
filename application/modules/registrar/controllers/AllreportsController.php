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
    			$search=$this->getRequest()->getPost();
    		}
    		else{
    			$search = array(
    					'adv_search' =>'',
    					'study_year' =>'',
    					'service'=>'',
    					'start_date'=> date('Y-m-d'),
    					'end_date'=>date('Y-m-d'),
    			);
    		}
    		$db = new Registrar_Model_DbTable_DbReportStudentByuser();
    		$data=$this->view->row = $db->getAllGernAndGepRegister($search);
    		$type=$db->getType();
    		
    	}catch(Exception $e){
    		Application_Form_FrmMessage::message("Application Error");
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
    	$form=new Registrar_Form_FrmSearchInfor();
    	$form->FrmSearchRegister();
    	Application_Model_Decorator::removeAllDecorator($form);
    	$this->view->form_search=$form;
    	$this->view->search = $search;
    }
    public function addAction(){
    	$this->_redirect("/registrar/allreports");
    }
    
    public function editAction(){
    	
    	
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
