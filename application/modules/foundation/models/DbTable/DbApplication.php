<?php

class Foundation_Model_DbTable_DbApplication extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'rms_student';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
	public function getServiceType($type){
		$db = $this->getAdapter();
		$sql ="SELECT `service_id`,`title` FROM `rms_program_name` WHERE status=1 AND `type`=".$type;
		return $db->fetchAll($sql);
	}
}

