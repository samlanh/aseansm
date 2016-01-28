<?php

class Foundation_Model_DbTable_DbGroup extends Zend_Db_Table_Abstract
{
	
	protected $_name = 'rms_group';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
	public function getGroup(){
		$db = $this->getAdapter();
		$sql="SELECT id,group_code FROM rms_group WHERE status = 1 ";
		return $db->fetchAll($sql);
	}
	
}

