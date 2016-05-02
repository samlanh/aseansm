<?php
class Registrar_StudentnearlyendserviceController extends Zend_Controller_Action {
	
    public function init()
    {    	
    	header('content-type: text/html; charset=utf8');
	}
	public function indexAction(){
		try{
			if($this->getRequest()->isPost()){
				$data=$this->getRequest()->getPost();
				$search = array(
						'txtsearch' => $data['txtsearch'],
						'start_date'=> $data['from_date'],
						'end_date'	=>$data['to_date']
				);
			}else{
				$search=array(
						'txtsearch' =>'',
						'start_date'=> date('Y-m-d'),
						'end_date'	=>date('Y-m-d'),
				);
			}
			$db = new Registrar_Model_DbTable_DbRptStudentNearlyEndService();
			$abc = $this->view->row = $db->getAllStudentNearlyEndService($search);
			
			$this->view->search = $search;
			
		}catch(Exception $e){
			Application_Form_FrmMessage::message("APPLICATION_ERROR");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			echo $e->getMessage();
		}
	}
	public function addAction(){
		$this->_redirect('registrar/studentnearlyendservice/index');
	}
	
}