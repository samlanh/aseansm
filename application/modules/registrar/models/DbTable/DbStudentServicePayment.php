<?php

class Registrar_Model_DbTable_DbStudentServicePayment extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_student_payment';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	function addStudentServicePayment($data){
		//print_r($data);exit();
		$db = $this->getAdapter();//ស្ពានភ្ជាប់ទៅកាន់Data Base
		$db->beginTransaction();//ទប់ស្កាត់មើលការErrore , មានErrore វាមិនអោយចូល
			try{
			$arr=array(
					'student_id'=>$data['studentid'],
					'receipt_number'=>$data['reciept_no'],
					'year'=>$data['study_year'],
					'total_payment'=>$data['grand_total'],
					'receive_amount'=>$data['total_received'],
					'paid_amount'=>$data['total_received']-$data['total_return'],
					'return_amount'=>$data['total_return'],
					'balance_due'=>$data['total_balance'],
					'amount_in_khmer'=>$data['char_price'],
					'note'=>$data['not'],
					'create_date'=>date("d-m-Y"),
					'user_id'=>$this->getUserId()
				);
			
			  $id = $this->insert($arr);
			  
				$this->_name='rms_student_paymentdetail';
				$ids = explode(',', $data['identity']);
				$disc = 0;
				$total = 0;
    			foreach ($ids as $i){
    				$disc=$disc+$data['discount_'.$i];
    				$total=$total+$data['total_'.$i];
    				$_arr = array(
    						'payment_id'	=>$id,
    						'service_id'	=>$data['service_'.$i],
    						'payment_term'	=>$data['term_'.$i],
    						'fee'			=>$data['price_'.$i],
    						'qty'			=>$data['qty_'.$i],
    						'amount'		=>$data['total_'.$i],
    						'discount_fix'	=>$data['discount_'.$i],
    						'note'			=>$data['remark'.$i],
    						'subtotal'		=>$data['subtotal_'.$i],
    						'type'			=>2,
    				);
    				$this->insert($_arr);
    			}
    			
    			$this->_name='rms_student_payment';
    			$datadisc = array('discount_fix'=>$disc,
    						'grand_total'=>$total);
    			$where=$this->getAdapter()->quoteInto("id=?", $id);
    			$this->update($datadisc, $where);
    			
    			$db->commit();
			}catch (Exception $e){
				echo $e->getMessage();
				$db->rollBack();//អោយវាវិលត្រលប់ទៅដើមវីញពេលណាវាជួបErrore
			}
		}
	function updateStudentServicePayment($data){
		$db = $this->getAdapter();//ស្ពានភ្ជាប់ទៅកាន់Data Base
		$db->beginTransaction();//ទប់ស្កាត់មើលការErrore , មានErrore វាមិនអោយចូល
			try{
			$arr=array(
					'student_id'=>$data['studentid'],
					'receipt_number'=>$data['reciept_no'],
					'year'=>$data['study_year'],
					'total_payment'=>$data['grand_total'],
					'receive_amount'=>$data['total_received'],
					'paid_amount'=>$data['total_received']-$data['total_return'],
					'return_amount'=>$data['total_return'],
					'balance_due'=>$data['total_balance'],
					'amount_in_khmer'=>$data['char_price'],
					'note'=>$data['not'],
					'create_date'=>date("d-m-Y"),
					'user_id'=>$this->getUserId()
				);
				$where=$this->getAdapter()->quoteInto("id=?", $data['id']);
			 	$this->update($arr, $where);
			  
				$this->_name='rms_student_paymentdetail';
				$where = "payment_id = ".$data['id'];
				$this->delete($where);
				
				$ids = explode(',', $data['identity']);
				
				$disc = 0;
				$total = 0;
				
    			foreach ($ids as $i){
    				
    				$disc=$disc+$data['discount_'.$i];
    				$total=$total+$data['total_'.$i];
    				
    				$_arr = array(
    						'payment_id'	=>$data['id'],
    						'service_id'	=>$data['service_'.$i],
    						'payment_term'	=>$data['term_'.$i],
    						'fee'			=>$data['price_'.$i],
    						'qty'			=>$data['qty_'.$i],
    						'amount'		=>$data['total_'.$i],
    						'discount_fix'	=>$data['discount_'.$i],
    						'note'			=>$data['remark'.$i],
    						'subtotal'		=>$data['subtotal_'.$i],
    						'type'			=>2,
    				);
    				$this->insert($_arr);
    			}
    			
    			$this->_name='rms_student_payment';
    			$datadisc = array('discount_fix'=>$disc,
    					'grand_total'=>$total);
    			$where=$this->getAdapter()->quoteInto("id=?", $data['id']);
    			$this->update($datadisc, $where);
    			
    			$db->commit();
    			return true;
			}catch (Exception $e){
				echo $e->getMessage();
				$db->rollBack();//អោយវាវិលត្រលប់ទៅដើមវីញពេលណាវាជួបErrore
			}
		}
    function getAllStudenTServicePayment(){
    	$db=$this->getAdapter();
    	$sql="select id,receipt_number,
		(select CONCAT(from_academic,' - ',to_academic) from rms_tuitionfee where rms_tuitionfee.id=rms_student_payment.year limit 1)AS year,
    	(select CONCAT(stu_khname,' - ',stu_enname) from rms_student where rms_student.stu_id=rms_student_payment.student_id limit 1)AS name,
    	(select name_kh from rms_view where rms_view.type=2 and rms_view.key_code=(select sex from rms_student where rms_student.stu_id=rms_student_payment.student_id limit 1) limit 1)AS sex,
    	
    	grand_total,discount_fix,total_payment,paid_amount,balance_due,return_amount
    	
    	from rms_student_payment where 1
    	";
    	$order=" ORDER BY id DESC ";
    	
//     	$order=" ORDER By stu_id DESC ";
    	return $db->fetchAll($sql.$order);
    }
    function getStudentServicePaymentByID($id){
    	$db=$this->getAdapter();
    	$sql="select * from rms_student_payment where id=".$id;
    	return $db->fetchRow($sql);
    }
    
    function getStudentServicePaymentDetailByID($id){
    	$db=$this->getAdapter();
    	$sql="select * from rms_student_paymentdetail where payment_id=".$id;
    	return $db->fetchAll($sql);
    }
    
    function getAllGrade($grade_id){
    	$db = $this->getAdapter();
    	$sql = "SELECT major_id As id,major_enname As name FROM rms_major WHERE dept_id=".$grade_id;
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function getPaymentTerm($generat,$payment_term,$grade){
    	$db = $this->getAdapter();
    	$sql="SELECT td.tuition_fee FROM rms_tuitionfee_detail AS td,`rms_tuitionfee` AS tu
    	WHERE tu.id= td.fee_id AND td.class_id=$grade AND td.payment_term=$payment_term AND tu.generation=$generat LIMIT 1";
    	return $db->fetchRow($sql);
    }
    function getPaymentGep($study_year,$levele,$payment_term){
    	$db = $this->getAdapter();
    	$sql="SELECT id,tuition_fee FROM rms_tuitionfee_detail WHERE fee_id=$study_year AND class_id=$levele AND payment_term=$payment_term LIMIT 1";
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
    	$sql = "SELECT id,CONCAT(start_year,'-',end_year) AS years FROM mrs_program_fee WHERE `status`=1";
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
    	}else {
    		$pre='H';
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
    
    public function getAllStudentCode(){
    	$db = $this->getAdapter();
    	$sql="SELECT stu_id,stu_code  FROM rms_student ORDER BY  stu_code DESC ";
    	return $db->fetchAll($sql);
    	
    }
    public function getAllpriceByServiceTerm($serviceid,$termid){
    	$db=$this->getAdapter();
    	$sql="select price_fee from rms_servicefee_detail where rms_servicefee_detail.service_id=$serviceid and rms_servicefee_detail.payment_term=$termid limit 1";
    	return $db->fetchRow($sql);
    }
    public function getAllStudentInfo($studentid){
    	$db=$this->getAdapter();
    	$sql="select stu_enname,stu_khname,sex from rms_student where stu_id=$studentid limit 1";
    	return $db->fetchRow($sql);
    }
    
}

