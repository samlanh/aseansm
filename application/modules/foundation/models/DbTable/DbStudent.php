<?php

class Foundation_Model_DbTable_DbStudent extends Zend_Db_Table_Abstract
{
	public function getAllStudent(){
		$_db = $this->getAdapter();
		$sql = "SELECT stu_enname,stu_khname,sex,batch,session FROM rms_student ";
		$orderby = " ORDER BY stu_enname ";
		return $_db->fetchAll($sql.$orderby);
	}
    
}

