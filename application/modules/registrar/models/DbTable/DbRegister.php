<?php

class Registrar_Model_DbTable_DbRegister extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_student';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	function addRegister($data){
		$db = $this->getAdapter();//ស្ពានភ្ជាប់ទៅកាន់Data Base
		$db->beginTransaction();//ទប់ស្កាត់មើលការErrore , មានErrore វាមិនអោយចូល
			try{
			$arr=array(
						'stu_code'=>$data['stu_id'],
						'academic_year'=>$data['study_year'],
						'stu_khname'=>$data['kh_name'],
						'stu_enname'=>$data['en_name'],
						'sex'=>$data['sex'],
						'session'=>$data['session'],
						'degree'=>$data['dept'],
						'grade'=>$data['grade'],
						'user_id'=>$this->getUserId(),
				);
			  $id= $this->insert($arr);
				$this->_name='rms_student_payment';
				$arr=array(
						'student_id'=>$id,
						'receipt_number'=>$data['reciept_no'],
						'payment_term'=>$data['payment_term'],
						'tuition_fee'=>$data['tuitionfee'],
						'discount_percent'=>$data['discount'],
						'other_fee'=>$data['remark'],
						'admin_fee'=>$data['addmin_fee'],
						'total'=>$data['total'],
						'paid_amount'=>$data['books'],
						'balance_due'=>$data['remaining'],
						'note'=>$data['not'],
						'payfor_type'=>1,
						'amount_in_khmer'=>$data['char_price'],
						'user_id'=>$this->getUserId(),
				);
				$this->insert($arr);
				$db->commit();//if not errore it do....
			}catch (Exception $e){
				$db->rollBack();//អោយវាវិលត្រលប់ទៅដើមវីញពេលណាវាជួបErrore
			}
		}
		function updateRegister($data){
			$db = $this->getAdapter();//ស្ពានភ្ជាប់ទៅកាន់Data Base
			$db->beginTransaction();//ទប់ស្កាត់មើលការErrore , មានErrore វាមិនអោយចូល
			try{
				$arr=array(
						'stu_code'=>$data['stu_id'],
						'academic_year'=>$data['study_year'],
						'stu_khname'=>$data['kh_name'],
						'stu_enname'=>$data['en_name'],
						'sex'=>$data['sex'],
						'session'=>$data['session'],
						'degree'=>$data['dept'],
						'grade'=>$data['grade'],
						'stu_type'=>1,
						'user_id'=>$this->getUserId(),
				);
				$where="stu_id=".$data['id'];
				 $this->update($arr, $where);
				$this->_name='rms_student_payment';
				$arr=array(
						'student_id'=>$data['id'],
						'receipt_number'=>$data['reciept_no'],
						'payment_term'=>$data['payment_term'],
						'tuition_fee'=>$data['tuitionfee'],
						'discount_percent'=>$data['discount'],
						'other_fee'=>$data['remark'],
						'admin_fee'=>$data['addmin_fee'],
						'total'=>$data['total'],
						'paid_amount'=>$data['books'],
						'balance_due'=>$data['remaining'],
						'note'=>$data['not'],
						'amount_in_khmer'=>$data['char_price'],
						'payfor_type'=>1,
						'user_id'=>$this->getUserId(),
				);
				$where="student_id=".$data['id'];
				$this->update($arr, $where);
				$db->commit();//if not errore it do....
			}catch (Exception $e){
				$db->rollBack();//អោយវាវិលត្រលប់ទៅដើមវីញពេលណាវាជួបErrore
			}
		}
    function getAllStudentRegister(){
    	$db=$this->getAdapter();
    	$sql=" SELECT s.stu_id,s.stu_code,sp.receipt_number,s.stu_khname,s.stu_enname,s.sex,(SELECT en_name FROM rms_dept WHERE dept_id=s.degree)AS degree,
		       (SELECT major_enname FROM rms_major WHERE major_id=s.grade ) AS grade,
		       sp.payment_term,sp.tuition_fee,sp.discount_percent,sp.other_fee,sp.admin_fee,sp.total,sp.paid_amount,
		       sp.balance_due
 			   FROM rms_student AS s,rms_student_payment AS sp WHERE s.stu_id=sp.student_id AND s.stu_type=1";
    	$order=" ORDER By stu_id DESC ";
    	return $db->fetchAll($sql.$order);
    }
    function getRegisterById($id){
    	$db=$this->getAdapter();
    	$sql=" SELECT s.stu_id,s.stu_code,sp.receipt_number,s.academic_year,s.stu_khname,s.stu_enname,s.sex,s.session,s.degree,s.grade,
    	sp.payment_term,sp.tuition_fee,sp.discount_percent,sp.other_fee,sp.admin_fee,sp.total,sp.paid_amount,
    	sp.balance_due,sp.amount_in_khmer,sp.note
    	FROM rms_student AS s,rms_student_payment AS sp WHERE s.stu_id=sp.student_id AND s.stu_id=".$id;
    	return $db->fetchRow($sql);
    }
    function getAllGrade($grade_id){
    	$db = $this->getAdapter();
    	$sql = "SELECT major_id As id,major_enname As name FROM rms_major WHERE dept_id=".$grade_id;
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function getPaymentTerm($generat,$payment_term,$grade){
    	$db = $this->getAdapter();
    	$sql="SELECT id,tuition_fee FROM rms_tuitionfee_detail WHERE fee_id=$generat AND class_id=$grade AND payment_term=$payment_term LIMIT 1";
    	return $db->fetchRow($sql);
    }
    function getAllYears(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,'-',to_academic) AS years FROM rms_tuitionfee WHERE `status`=1";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function getAllYearsProgramFee(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,'-',to_academic) AS years FROM rms_tuitionfee WHERE `status`=1";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    public function getNewAccountNumber($type){
    	$db = $this->getAdapter();
    	$sql="  SELECT stu_id  FROM rms_student ORDER BY  stu_id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	//echo $new_acc_no;exit();
    	$acc_no= strlen((int)$acc_no+1);
    	if($type==1){
    		$pre='K';
    	}
    	else if($type==2){
    		$pre='P';
    	}
    	else if($type==3){
    		$pre='S';
    	}else if($type==4) {
    		$pre='H';
    	}else {
    		$pre='CH';
    	}
    	for($i = $acc_no;$i<5;$i++){
    		$pre.='0';
    	}
    	return $pre.$new_acc_no;
    }
    public function getRecieptNo(){
    	$db = $this->getAdapter();
    	$sql="SELECT id  FROM rms_student_payment ORDER BY  id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre=0;
    	for($i = $acc_no;$i<5;$i++){
    		$pre.='0';
    	}
    	return $pre.$new_acc_no;
    }
    //select GEP all old student
    function getAllGepOldStudent(){
    	$db=$this->getAdapter();
    	$sql="SELECT s.stu_id As stu_id,s.stu_code As stu_code FROM rms_student AS s,rms_student_payment AS sp 
    	      WHERE s.stu_id=sp.student_id  AND s.stu_type=2 AND sp.payfor_type=2";
    	return $db->fetchAll($sql);
    }
    //select Gep old student by id 
    function getGepOldStudent($stu_id){
    	$db=$this->getAdapter();
    	$sql="SELECT stu_id,stu_enname,stu_khname,sex,`session` As ses,degree,grade FROM rms_student 
    	       WHERE  stu_type=2 AND stu_id=$stu_id LIMIT 1";
    	return $db->fetchRow($sql);
    }
}

