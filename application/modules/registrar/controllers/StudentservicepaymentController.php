<?php
class Registrar_StudentservicepaymentController extends Zend_Controller_Action {
	protected $tr;
	const REDIRECT_URL ='/registrar';
	public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	
	}

    public function indexAction()
    {
    	try{
    		$db = new Registrar_Model_DbTable_DbStudentServicePayment();
//     		    		if($this->getRequest()->isPost()){
//     		    			$search=$this->getRequest()->getPost();
//     		    		}
//     		    		else{
//     		    			$search = array(
//     		    					'adv_search' => '',
//     		    					'search_status' => -1,
//     		    					'start_date'=> date('Y-m-01'),
//     		    					'end_date'=>date('Y-m-d'));
//     		    		}
    		$rs_rows= $db->getAllStudentGep();
//     		$glClass = new Application_Model_GlobalClass();
//     		$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
    		$list = new Application_Form_Frmtable();
    		$collumns = array("STUDENT_ID","RECEIPT_NO","NAME_KH","NAME_EN","SEX","CLASS","CLASSES",
    				          "PAYMENT_TERM","TUITION_FEE","DISCOUND","OTHERS_PRICE","ADMIN_FEE","TOTALE","BOOKS","REMAINING",);
    		$link=array(
    				'module'=>'registrar','controller'=>'coursestudy','action'=>'edit',
    		);
    		$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('stu_code'=>$link,'receipt_number'=>$link,'stu_khname'=>$link,'stu_enname'=>$link));
    	}catch (Exception $e){
    		Application_Form_FrmMessage::message("Application Error");
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
    }
    public function addAction()
    {
    if($this->getRequest()->isPost()){
      	$_data = $this->getRequest()->getPost();
      	try {
      		$db = new Registrar_Model_DbTable_DbStudentServicePayment();
      		if(isset($_data['save_new'])){
      			$db->addStudentGep($_data);
      			Application_Form_FrmMessage::message($this->tr->translate('INSERT_SUCCESS'));
      		}else{
      			$db->addStudentGep($_data);
      			Application_Form_FrmMessage::Sucessfull($this->tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL . '/coursestudy/index');
      		}
      	} catch (Exception $e) {
      		Application_Form_FrmMessage::message($this->tr->translate('INSERT_FAIL'));
      		$err =$e->getMessage();
      		Application_Model_DbTable_DbUserLog::writeMessageError($err);
      	}
      }
       $frm = new Registrar_Form_FrmCourseStudy();
       $frm_register=$frm->FrmRegistarWU();
       Application_Model_Decorator::removeAllDecorator($frm_register);
       $this->view->frm_register = $frm_register;
       $key = new Application_Model_DbTable_DbKeycode();
       $this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
       $model = new Application_Form_FrmGlobal();
      
       $this->view->invoice_no = Application_Model_GlobalClass::getInvoiceNo();
       $__student_card = array();
       $this->view->student_card = $__student_card;
       $db = new Registrar_Model_DbTable_DbwuRegister();
       $this->view->invoice_num = $db->getGaneratInvoiceWU();
       //get all dept
       $_db = new Application_Model_DbTable_DbGlobal();
       $this->view->all_dept = $_db->getAllFecultyName();
       $_hour = new Application_Model_GlobalClass();
       $this->view->hour= $row = $_hour->getHours();
       
       $db = new Registrar_Model_DbTable_DbStudentServicePayment();
       $this->view->rs = $db->getAllStudentCode();
       
       $_model = new Application_Model_GlobalClass();
       $this->view->all_service = $_model->getAllServiceItemOption(2);
    }
    public function editAction()
    {
    	$id=$this->getRequest()->getParam('id');
    	if($this->getRequest()->isPost()){
    		$_data = $this->getRequest()->getPost();
    		$_data['id']=$id;
    		try {
    			$db = new Registrar_Model_DbTable_DbCourStudey();
    			if(isset($_data['save_new'])){
    				$db->updateStudentGep($_data);
    				Application_Form_FrmMessage::Sucessfull($this->tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL . '/coursestudy/index');
    			}
    		} catch (Exception $e) {
    			Application_Form_FrmMessage::message($this->tr->translate('INSERT_FAIL'));
    			$err =$e->getMessage();
    			Application_Model_DbTable_DbUserLog::writeMessageError($err);
    		}
    	}
    	$db = new Registrar_Model_DbTable_DbCourStudey();
    	$row_gep=$db->getStuentGepById($id);
    	$this->view->row_gep=$row_gep;
    	$frm = new Registrar_Form_FrmCourseStudy();
    	$frm_register=$frm->FrmRegistarWU($row_gep);
    	Application_Model_Decorator::removeAllDecorator($frm_register);
    	$this->view->frm_register = $frm_register;
    	$key = new Application_Model_DbTable_DbKeycode();
    	$this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
    	$model = new Application_Form_FrmGlobal();
    	$this->view->footer=$model->getReceiptFooter();
    	$this->view->invoice_no = Application_Model_GlobalClass::getInvoiceNo();
    	$__student_card = array();
    	$this->view->student_card = $__student_card;
    	$db = new Registrar_Model_DbTable_DbwuRegister();
    	$this->view->invoice_num = $db->getGaneratInvoiceWU();
    	//get all dept
    	$_db = new Application_Model_DbTable_DbGlobal();
    	$this->view->all_dept = $_db->getAllFecultyName();
    	$_hour = new Application_Model_GlobalClass();
    	$this->view->hour= $row = $_hour->getHours();
    }
    public function oldaddAction()
    {
    	if($this->getRequest()->isPost()){
    		$_data = $this->getRequest()->getPost();
    		$_model = new Registrar_Model_DbTable_DbwuRegister();
    		//print_r($_data);exit();
    		$_model->AddNewStudent($_data);
    	}
    	$frm = new Registrar_Form_FrmRegister();
    	$frm_register=$frm->FrmRegistarWU();
    	Application_Model_Decorator::removeAllDecorator($frm_register);
    	$this->view->frm_register = $frm_register;
    	//        $_marjor =array();
    	//        $this->view->marjorlist = $_marjor;
    	//        $model = new Application_Model_DbTable_DbGlobal();
    	//        $_marjorlist = $model->getMarjorById();
    	 
    	//        $this->view->marjorlist = $_marjorlist;
    	 
    	$key = new Application_Model_DbTable_DbKeycode();
    	$this->view->keycode=$key->getKeyCodeMiniInv(TRUE);
    	$model = new Application_Form_FrmGlobal();
    	$this->view->footer=$model->getReceiptFooter();
    	 
    	$this->view->invoice_no = Application_Model_GlobalClass::getInvoiceNo();
    	// echo Application_Model_GlobalClass::getInvoiceNo();
    	 
    	$__student_card = array();
    	$this->view->student_card = $__student_card;
    	$db = new Registrar_Model_DbTable_DbwuRegister();
    	$this->view->invoice_num = $db->getGaneratInvoiceWU();
    	// echo $db->getGaneratInvoiceWU();
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
    public function getStudentinfoallAction(){
    	if($this->getRequest()->isPost()){
    		$_db = new Registrar_Model_DbTable_DbGetStudentInfo();
    		$_rs_student = $_db->getAllStudent();
    		print_r(Zend_Json::encode($_rs_student));
    		exit();
    	}
    }
    function getGradeAction(){
    	if($this->getRequest()->isPost()){
    		$data=$this->getRequest()->getPost();
    		$db = new Registrar_Model_DbTable_DbRegister();
    		$grade = $db->getAllGrade($data['dept_id']);
    		//print_r($grade);exit();
    		//array_unshift($makes, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
    		print_r(Zend_Json::encode($grade));
    		exit();
    	}
    }
    function getPaymentGepAction(){
    	if($this->getRequest()->isPost()){
    		$data=$this->getRequest()->getPost();
    		$db = new Registrar_Model_DbTable_DbStudentServicePayment();
    		$payment = $db->getPaymentGep($data['study_year'],$data['levele'],$data['payment_term']);
    		//print_r($grade);exit();
    		//array_unshift($makes, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
    		print_r(Zend_Json::encode($payment));
    		exit();
    	}
    }
    function getGepOldStudentAction(){
    	if($this->getRequest()->isPost()){
    		$data=$this->getRequest()->getPost();
    		$db = new Registrar_Model_DbTable_DbRegister();
    		$gep = $db->getGepOldStudent($data['student_id']);
    		//print_r($grade);exit();
    		//array_unshift($makes, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
    		print_r(Zend_Json::encode($gep));
    		exit();
    	}
    }
    
    function getPriceAction(){
    	if($this->getRequest()->isPost()){
    		$data=$this->getRequest()->getPost();
    		$db = new Registrar_Model_DbTable_DbStudentServicePayment();
    		$price = $db->getAllpriceByServiceTerm($data['service'],$data['term']);
//     		print_r($price);exit();
    		
    		//print_r($grade);exit();
    		//array_unshift($makes, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
    		print_r(Zend_Json::encode($price));
    		exit();
    	}
    }
    
}
