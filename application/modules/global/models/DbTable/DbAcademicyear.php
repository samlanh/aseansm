<?php

class Global_Model_DbTable_DbAcademicyear extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_academicperiod';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function AddNewAcademicyear($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$_arr=array(
					'fromyear' => $_data['fromyear'],
					'toyear' => $_data['toyear'],
					'batch' => $_data['generation'],
					'study_start' => $_data['study_start'],
					'study_end' => $_data['start_end'],
					'note' => $_data['note'],
					'status' => $_data['status'],
					'quarter_start' => $_data['quarter_start'],
					'quarter_end' => $_data['quarter_end'],
					'duration' => $_data['duration'],
					'semester_start' => $_data['semester_start'],
					'semester_end' => $_data['semester_end'],
					'yearly_start'=>$_data['yearly_start'],
					'yearly_start'   => $_data['yearly_start'],
					'date' => Zend_Date::now(),
					'user_id' => $this->getUserId(),
				);
				$this->insert($_arr);
// 			}
			return $db->commit();
		}catch (Exception $e){
			$db->rollBack();
		}
	}
	public function getAcademicyearById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM rms_academicperiod WHERE id = ".$db->quote($id);
		$sql.=" LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function updateacademicyear($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
		$_arr=array(
					'fromyear' => $_data['fromyear'],
					'toyear' => $_data['toyear'],
					'batch' => $_data['generation'],
					'study_start' => $_data['study_start'],
					'study_end' => $_data['start_end'],
					'note' => $_data['note'],
					'status' => $_data['status'],
					'quarter_start' => $_data['quarter_start'],
					'quarter_end' => $_data['quarter_end'],
					'duration' => $_data['duration'],
					'semester_start' => $_data['semester_start'],
					'semester_end' => $_data['semester_end'],
					'yearly_start'=>$_data['yearly_start'],
					'yearly_start'   => $_data['yearly_start'],
					'date' => Zend_Date::now(),
					'user_id' => $this->getUserId(),
				
		);
		$where=$this->getAdapter()->quoteInto("id=?", $_data["id"]);
		
		$this->update($_arr, $where);
		return $db->commit();
		}catch (Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	
	function getAllacademicyear($search){
		$db = $this->getAdapter();
		$sql = ' SELECT id, fromyear, toyear, batch, study_start, study_end, duration, note, status FROM rms_academicperiod WHERE 1';
		$where = '';
		
		return $db->fetchAll($sql);
	}
}


