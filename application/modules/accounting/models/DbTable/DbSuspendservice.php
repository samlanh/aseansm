<?php
class Accounting_Model_DbTable_DbSuspendservice extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_suspendservice';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function getAllYears(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,'-',to_academic) AS years FROM rms_servicefee WHERE `status`=1";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
   public function addSuspendservice($data){
   	$db = $this->getAdapter();
   	$db->beginTransaction();
   		try{
	   		$arr = array(
	   			'student_id'	=> $data['studentid'],
	   			'suspend_no'	=> $data['suspend_no'],
	   			'define_date'	=> date("Y-m-d"),
	   			'year'			=> $data['study_year'],
	   			'user_id'=>$this->getUserId()
	   				);
	   		$id = $this->insert($arr);
	   		
	   		$this->_name = 'rms_suspendservicedetail';
	   		$ids = explode(',', $data['identity']);
		   		foreach ($ids as $i){
		   		$_arr = array(
		   				'suspendservice_id'=>$id,
		   				'service_id'=> $data['service_'.$i],
		   				'date'=> $data['date_'.$i],
		   				'type_suspend'=> $data['type_'.$i],
		   				'reason'=> $data['reason_'.$i],
		   				'note'=> $data['note_'.$i],
		   				'define_date'=>date("Y-m-d"),
		   				'user_id'=>$this->getUserId()
		   				);
		   		$this->insert($_arr);
		   		}
   		}catch (Exception $e){
   			$db->rollBack();
   		}
   	$db->commit();
   }
   
   public function editSuspendService($data){
   	$db = $this->getAdapter();
   	$db->beginTransaction();
   	try{
   		$arr = array(
   				'student_id'	=> $data['studentid'],
   				'suspend_no'	=> $data['suspend_no'],
   				'define_date'	=>date("Y-m-d"),
   				'year'			=> $data['study_year'],
   				'user_id'		=>$this->getUserId()
   		);
   		$where=$this->getAdapter()->quoteInto("id=?", $data['id']);
   		$this->update($arr, $where);
   	
   		$this->_name = 'rms_suspendservicedetail';
   		$where = "suspendservice_id = ".$data['id'];
   		$this->delete($where);   		
   		
   		$ids = explode(',', $data['identity']);
   		foreach ($ids as $i){
   			$_arr = array(
   					'suspendservice_id'	=>$data['id'],
   					'service_id'		=> $data['service_'.$i],
   					'date'				=> $data['date_'.$i],
   					'type_suspend'		=> $data['type_'.$i],
   					'reason'			=> $data['reason_'.$i],
   					'note'				=> $data['note_'.$i],
   					'define_date'		=>date("Y-m-d"),
   					'user_id'			=>$this->getUserId()
   			);
   			$this->insert($_arr);
   		}
   	}catch (Exception $e){
   		$db->rollBack();
   	}
   	$db->commit();
   }
   public function getSuspendNo(){
   	$db = $this->getAdapter();
   	$sql="SELECT id  FROM rms_suspendservice ORDER BY  id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	$pre=0;
   	for($i = $acc_no;$i<5;$i++){
   		$pre.='0';
   	}
   	return $pre.$new_acc_no;
   }
   public function getAllGerneralOldStudent(){
   	$db=$this->getAdapter();
   	$sql="SELECT s.stu_id As stu_id,s.stu_code As stu_code FROM rms_student AS s,rms_student_payment AS sp
   	WHERE s.stu_id=sp.student_id  AND s.stu_type=1 AND sp.payfor_type=1";
   	return $db->fetchAll($sql);
   }
   public function getStudentSuspendService(){
   	$db = $this->getAdapter();
   	$sql="SELECT id,suspend_no,
  	 	(SELECT `stu_code` FROM  `rms_student` WHERE stu_id=student_id LIMIT 1) AS code,
   		(SELECT `stu_khname` FROM  `rms_student` WHERE stu_id=student_id LIMIT 1) as kh_name,
   		(SELECT `stu_enname` FROM  `rms_student` WHERE stu_id=student_id LIMIT 1) AS en_name,
   		(SELECT (SELECT `name_kh` FROM`rms_view` WHERE `type`=2 AND `key_code`=`sex`) FROM `rms_student` WHERE stu_id=student_id LIMIT 1),
   		define_date
   		FROM rms_suspendservice ORDER BY id DESC
   	";
   	return $db->fetchAll($sql);
   }
   public function getStudentSuspendServiceByID($id){
   	$db = $this->getAdapter();
   	$sql="SELECT * FROM rms_suspendservice WHERE id=".$id;
   	return $db->fetchRow($sql);
   }
   public function getSuspendServiceByID($id){
   	$db = $this->getAdapter();
   	$sql="SELECT * FROM rms_suspendservicedetail WHERE suspendservice_id=".$id;
   	return $db->fetchAll($sql);
   }
   
   public function getAllStudentInfo($studentid){
   	$db=$this->getAdapter();
   	$sql="select stu_enname,stu_khname,sex from rms_student where stu_id=$studentid limit 1";
   	return $db->fetchRow($sql);
   }
   public function getAllStudentCode(){
   	$db = $this->getAdapter();
   	$sql="SELECT stu_id,stu_code  FROM rms_student ORDER BY  stu_code DESC ";
   	return $db->fetchAll($sql);
   	 
   }
   
}



