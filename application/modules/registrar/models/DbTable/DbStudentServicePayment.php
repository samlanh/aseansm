<?php

class Registrar_Model_DbTable_DbStudentServicePayment extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_student_payment';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    
    function getStudentPaymentStart($studentid,$service_id){
    	$db = $this->getAdapter();
    	$sql="select spd.id from rms_student_payment AS sp,rms_student_paymentdetail AS spd where
    		 sp.id=spd.payment_id and is_start=1 and service_id= $service_id and sp.student_id=$studentid limit 1 ";
//     	echo $sql;exit();
    	return $db->fetchOne($sql);
    }
    
	function addStudentServicePayment($data){
		//print_r($data);exit();
		$db = $this->getAdapter();//ស្ពានភ្ជាប់ទៅកាន់Data Base
		$db->beginTransaction();//ទប់ស្កាត់មើលការErrore , មានErrore វាមិនអោយចូល
			try{
			$arr=array(
					'student_id'		=>$data['studentid'],
					'receipt_number'	=>$data['reciept_no'],
					'year'				=>$data['study_year'],
					'total_payment'		=>$data['grand_total'],
					'receive_amount'	=>$data['total_received'],
					'paid_amount'		=>$data['total_received']-$data['total_return'],
					'return_amount'		=>$data['total_return'],
					'balance_due'		=>$data['total_balance'],
					'amount_in_khmer'	=>$data['char_price'],
					'note'				=>$data['not'],
					'time'				=>$data['time'],
					'create_date'		=>date("d-m-Y"),
					'user_id'			=>$this->getUserId()
				);
			
			  $id = $this->insert($arr);
			  
				$this->_name='rms_student_paymentdetail';
				$ids = explode(',', $data['identity']);
				$disc = 0;
				$total = 0;
    			foreach ($ids as $i){
    				
    				$payment_id = $this->getStudentPaymentStart($data['studentid'], $data['service_'.$i]);
    				if(empty($payment_id)){
    					$payment_id=0;
    				}
    				$where="id = $payment_id ";
    				$arr = array(
    						'is_start'=>0
    				);
    				$this->update($arr,$where);
    				
    				$disc=$disc+$data['discount_'.$i];
    				$total=$total+($data['price_'.$i]*$data['qty_'.$i]);
    				$complete=1;
    				if($data['subtotal_'.$i]-$data['paidamount_'.$i]>0){
    					$complete=0;
    					$status="មិនទាន់បង់";
    				}else{
	    				$complete=1;
	    				$status="បង់រួច";
    				}
    				$_arr = array(
    						'payment_id'	=>$id,
    						'service_id'	=>$data['service_'.$i],
    						'payment_term'	=>$data['term_'.$i],
    						'fee'			=>$data['price_'.$i],
    						'qty'			=>$data['qty_'.$i],
    						'paidamount'	=>$data['paidamount_'.$i],
    						'balance'		=>$data['subtotal_'.$i]-$data['paidamount_'.$i],
    						'validate'		=>$data['validate_'.$i],
    						'start_date'	=>$data['date_start_'.$i],
    						'discount_fix'	=>$data['discount_'.$i],
    						'note'			=>$data['remark'.$i],
    						'subtotal'		=>$data['subtotal_'.$i],
    						'type'			=>2,
    						'is_start'		=>1,
    						'is_parent'		=>$payment_id,
    						'is_complete'	=>$complete,
    						'comment'		=>$status,
    				);
    				$id_record = $this->insert($_arr);
    				
    				if(!empty($data['old_service_'.$i])){
    					$arr = array(
    						'is_complete'=>1,
    						'comment'	 =>"បង់រួច"
    					);
    					$where='id ='.$data['old_service_'.$i];
    					$this->update($arr, $where);
    				}
    			}
    			$this->_name='rms_student_payment';
    			$datadisc = array('discount_fix'=>$disc,
    						'total'=>$total);
    			$where=$this->getAdapter()->quoteInto("id=?", $id);
    			$this->update($datadisc, $where);
    			$db->commit();
			}catch (Exception $e){
				echo $e->getMessage();
				$db->rollBack();//អោយវាវិលត្រលប់ទៅដើមវីញពេលណាវាជួបErrore
			}
		}
		
	function getServicePaymentDetail($id) {
		$db = $this->getAdapter();
		$sql="select * from rms_student_payment AS sp,rms_student_paymentdetail AS spd where 
    		 sp.id=spd.payment_id and sp.id=$id";
		//echo $sql;exit();
		return $db->fetchAll($sql);
	}
		
	function updateStudentServicePayment($data){
		$db = $this->getAdapter();//ស្ពានភ្ជាប់ទៅកាន់Data Base
		$db->beginTransaction();//ទប់ស្កាត់មើលការErrore , មានErrore វាមិនអោយចូល
			try{
			$arr=array(
					'student_id'		=>$data['studentid'],
					'receipt_number'	=>$data['reciept_no'],
					'year'				=>$data['study_year'],
					'total_payment'		=>$data['grand_total'],
					'receive_amount'	=>$data['total_received'],
					'paid_amount'		=>$data['total_received']-$data['total_return'],
					'return_amount'		=>$data['total_return'],
					'balance_due'		=>$data['total_balance'],
					'amount_in_khmer'	=>$data['char_price'],
					'note'				=>$data['not'],
					'time'				=>$data['time'],
					'create_date'=>date("d-m-Y"),
					'user_id'=>$this->getUserId()
				);
				$where =$this->getAdapter()->quoteInto("id=?", $data['id']);
			 	$this->update($arr,$where);
			    
				$this->_name='rms_student_paymentdetail';
				
				$rows = $this->getServicePaymentDetail($data['id']);
				
				if(!empty($rows)){
					foreach ($rows as $rs){
						$arr = array(
								'is_start'   =>1,
						);
						$where="id =".$rs['is_parent'];
						$this->update($arr, $where);
					}
				}
				
				
				$where = "payment_id = ".$data['id'];
				$this->delete($where);
				
				
				
				$ids = explode(',', $data['identity']);
				$disc = 0;
				$total = 0;
    			foreach ($ids as $i){
    				
    				$payment_id = $this->getStudentPaymentStart($data['studentid'], $data['service_'.$i]);
    				if(empty($payment_id)){
    					$payment_id=0;
    				}
    				$where="id = $payment_id ";
    				$arr = array(
    						'is_start'=>0
    				);
    				$this->update($arr,$where);
    				
    				
    				$disc=$disc+$data['discount_'.$i];
    				$total=$total+($data['price_'.$i]*$data['qty_'.$i]);
    				$complete=1;
    				if($data['subtotal_'.$i]-$data['paidamount_'.$i]>0){
    					$complete=0;
    					$status="មិនទាន់បង់";
    				}else{
	    				$complete=1;
	    				$status="បង់រួច";
    				}
    				$_arr = array(
    						'payment_id'	=>$data['id'],
    						'service_id'	=>$data['service_'.$i],
    						'payment_term'	=>$data['term_'.$i],
    						'fee'			=>$data['price_'.$i],
    						'qty'			=>$data['qty_'.$i],
    						'paidamount'	=>$data['paidamount_'.$i],
    						'balance'		=>$data['subtotal_'.$i]-$data['paidamount_'.$i],
    						'validate'		=>$data['validate_'.$i],
    						'start_date'	=>$data['date_start_'.$i],
    						'discount_fix'	=>$data['discount_'.$i],
    						'note'			=>$data['remark'.$i],
    						'subtotal'		=>$data['subtotal_'.$i],
    						'type'			=>2,
    						'is_parent'		=>$payment_id,
    						'is_complete'   =>$complete,
    						'comment'		=>$status,
    				);
    				$this->insert($_arr);
    			}
    			$this->_name='rms_student_payment';
    			$datadisc = array('discount_fix'=>$disc,
    					'total'=>$total);
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
    	$user=$this->getUserId();
    	$db=$this->getAdapter();
    	$sql="select id,receipt_number,
		(select CONCAT(from_academic,' - ',to_academic) from rms_servicefee where rms_servicefee.id=rms_student_payment.year limit 1)AS year,
    	(select CONCAT(stu_khname,' - ',stu_enname) from rms_student where rms_student.stu_id=rms_student_payment.student_id limit 1)AS name,
    	(select name_kh from rms_view where rms_view.type=2 and rms_view.key_code=(select sex from rms_student where rms_student.stu_id=rms_student_payment.student_id limit 1) limit 1)AS sex,
    	total,discount_fix,total_payment,receive_amount,balance_due,return_amount,create_date,
    	(select CONCAT(last_name,' ',first_name) from rms_users where rms_users.id=rms_student_payment.user_id) AS user

    	from rms_student_payment where 1 and rms_student_payment.user_id=".$user;
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
    
    function getAllPaymentTerm($id){
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
    public function getAllpriceByServiceTerm($studentid,$serviceid,$termid){
    	$db=$this->getAdapter();
    	
    	$sql="select rms_student_paymentdetail.id,rms_student_paymentdetail.validate,balance AS price_fee from rms_student_paymentdetail,rms_student_payment where rms_student_payment.id=rms_student_paymentdetail.payment_id and rms_student_paymentdetail.service_id=$serviceid and rms_student_payment.student_id=$studentid and is_complete=0 limit 1";                               
    	$row=$db->fetchRow($sql);
    	if($row['price_fee']>0){
    		$row['sms']='លុយជំពាក់ពីមុន';
    		return $row;
    	}
    	else{
    		$sql="select price_fee from rms_servicefee_detail where  rms_servicefee_detail.service_id=$serviceid and rms_servicefee_detail.payment_term=$termid limit 1";
    		return $db->fetchRow($sql);
    	}
    }
    
    public function getAllpriceByServiceTermEdit($serviceid,$termid){
    	$db=$this->getAdapter();
    	$sql="select price_fee from rms_servicefee_detail where  rms_servicefee_detail.service_id=$serviceid and rms_servicefee_detail.payment_term=$termid limit 1";
    	return $db->fetchRow($sql);
    }
    
    public function getAllStudentInfo($studentid){
    	$db=$this->getAdapter();
    	$sql="select stu_enname,stu_khname,sex from rms_student where stu_id=$studentid limit 1";
    	return $db->fetchRow($sql);
    }
    
    public function getStudentBalance($studentid,$serviceid,$termid){
    	$db=$this->getAdapter();
    	$sql="select stu_enname,stu_khname,sex from rms_student where stu_id=$studentid limit 1";
    	return $db->fetchRow($sql);
    }
    
    public function getAllService($year){
    	$this->_name='rms_servicefee';
    	$db=$this->getAdapter();
    	$sql="SELECT title FROM rms_program_name WHERE rms_program_name.service_id=(select service_id from rms_servicefee_detail where rms_servicefee_detail.service_feeid=$year )";
    	return $db->fetchRow($sql);
    	
    	echo $sql;
    }
    
}





