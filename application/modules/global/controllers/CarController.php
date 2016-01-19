<?php
class Global_CarController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function start(){
		return ($this->getRequest()->getParam('limit_satrt',0));
	}
	public function indexAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title'],
						'subjec_name'=>$_data['subjec_name'],
						'status' => $_data['status_search']);
			}
			else{
			
				$search = array(
						'title' => '',
						'status' => -1,
				);
			
			}
			$db = new Global_Model_DbTable_DbCar();
			$rs_rows= $db->getAllCars($search);
	
			$glClass = new Application_Model_GlobalClass();
			$rs = $glClass->getImgActive($rs_rows, BASE_URL, true);
	
			$list = new Application_Form_Frmtable();
			$collumns = array("CarID","Car Name","Driver Name","Tel","Zone","Note","Status");
			$link=array(
					'module'=>'global','controller'=>'car','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs,array('carname'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
	}
	
	function addAction()
	{
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbcar = new Global_Model_DbTable_DbCar();
				$_dbcar->addcar($_data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/car/add");
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
	}
	
	public function editAction()
	{
		
		$db=new Global_Model_DbTable_DbCar();
		$id=$this->getRequest()->getParam("id");
		$this->view->rs = $row=$db->getCarById($id);
		if($this->getRequest()->isPost())
		{
			$data = $this->getRequest()->getPost();
			$db = new Global_Model_DbTable_DbCar();
			$db->updateCar($data,$id);
			Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/global/province/index");
		}
		
		
	}
}
