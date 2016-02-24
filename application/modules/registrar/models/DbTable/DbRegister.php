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
				if($data['student_type']==3){//old
					$id=$data['old_studens'];
				}else {
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
			    	$id= $this->insert($arr);
				}
				
				$this->_name='rms_student_payment';
				$arr=array(
						'student_id'=>$id,
						'receipt_number'=>$data['reciept_no'],
            			'time'=>$data['time'],
// 						'end_hour'=>$data['to_time'],
						'payment_term'=>$data['payment_term'],
						'tuition_fee'=>$data['tuitionfee'],
						'discount_percent'=>$data['discount'],
						'other_fee'=>$data['remark'],
						'admin_fee'=>$data['addmin_fee'],
						'total'=>$data['total'],
						'paid_amount'=>$data['books'],
						'balance_due'=>$data['remaining'],
						'note'=>$data['not'],
						'student_type'=>$data['student_type'],
						'create_date'=>	date('Y-m-d'),
						'payfor_type'=>1,
						'amount_in_khmer'=>$data['char_price'],
						'user_id'=>$this->getUserId(),
				);
				
				$paymentid = $this->insert($arr);
				
				$this->_name='rms_student_paymentdetail';
				$arr=array(
						'payment_id'=>$paymentid,
						'type'=>1,
						'payment_term'=>$data['payment_term'],
						'fee'=>$data['tuitionfee'],
						'qty'=>1,
						'amount'=>$data['total'],
						'discount_percent'=>$data['discount'],
						'discount_fix'=>0,
						'note'=>$data['not'],
						'references'=>'frome registration',
						'create_date'=>date('Y-m-d'),
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
				if($data['student_type']==3){//old stu
					
				}else{  
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
				}
				
				$this->_name='rms_student_payment';
				$arr=array(
						'student_id'=>$data['id'],
						'receipt_number'=>$data['reciept_no'],
						'time'=>$data['time'],
// 						'end_hour'=>$data['to_time'],
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
				$where="id=".$data['pay_id'];
				$this->update($arr, $where);
				
				
				$this->_name='rms_student_paymentdetail';
				$arr=array(
						'payment_id'=>$data['pay_id'],
						'type'=>1,
						'payment_term'=>$data['payment_term'],
						'fee'=>$data['tuitionfee'],
						'qty'=>1,
						'amount'=>$data['total'],
						'discount_percent'=>$data['discount'],
						'discount_fix'=>0,
						'note'=>$data['not'],
						'references'=>'frome registration',
						'create_date'=>	date('Y-m-d'),
						'user_id'=>$this->getUserId(),
				);
				$where="payment_id=".$data['pay_id'];
				$this->update($arr, $where);
				
				$db->commit();//if not errore it do....
			}catch (Exception $e){
				$db->rollBack();//អោយវាវិលត្រលប់ទៅដើមវីញពេលណាវាជួបErrore
			}
		}
    function getAllStudentRegister($search=null){
    	
    	$db=$this->getAdapter();
     	$from_date = (empty($search["start_date"]))?'Y-m-01':$search["start_date"];
     	$to_date =  (empty($search["end_date"]))?'Y-m-01' :$search["end_date"];
     	$where =" AND sp.create_date BETWEEN '$from_date' AND '$to_date'";
    	$sql=" SELECT sp.id,s.stu_code,sp.receipt_number,s.stu_khname,s.stu_enname,s.sex,(SELECT en_name FROM rms_dept WHERE dept_id=s.degree)AS degree,
		       (SELECT major_enname FROM rms_major WHERE major_id=s.grade ) AS grade,
 		       sp.payment_term,sp.tuition_fee,sp.discount_percent, sp.total,sp.paid_amount,
		       sp.balance_due,sp.create_date
 			   FROM rms_student AS s,rms_student_payment AS sp WHERE s.stu_id=sp.student_id AND s.stu_type=1";
    	
    	if(!empty($search['adv_search'])){
    		$s_where=array();
    		$s_search=$search['adv_search'];
    		$s_where[]= " stu_code LIKE '%{$s_search}%'";
    		$s_where[]=" receipt_number LIKE '%{$s_search}%'";
    		$s_where[]= " stu_khname LIKE '%{$s_search}%'";
    		$s_where[]= " stu_enname LIKE '%{$s_search}%'";
    		$s_where[]= " grade LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ', $s_where).')';
    	}
    	$order=" ORDER By stu_id DESC ";
    	//print_r($sql.$where);
    	return $db->fetchAll($sql.$where.$order);
    }
    function getRegisterById($id){
    	$db=$this->getAdapter();
    	$sql=" SELECT s.stu_id,s.stu_code,sp.receipt_number,s.academic_year,s.stu_khname,s.stu_enname,s.sex,s.session,s.degree,s.grade,
    	sp.payment_term,sp.tuition_fee,sp.discount_percent,sp.other_fee,sp.admin_fee,sp.total,sp.paid_amount,
    	sp.balance_due,sp.amount_in_khmer,sp.note,sp.student_type,sp.time,sp.end_hour
    	FROM rms_student AS s,rms_student_payment AS sp WHERE s.stu_id=sp.student_id AND sp.id=".$id;
    	return $db->fetchRow($sql);
    }
    function getAllGrade($grade_id){
    	$db = $this->getAdapter();
    	$sql = "SELECT major_id As id,major_enname As name FROM rms_major WHERE dept_id=".$grade_id;
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function getPaymentTerm($generat,$payment_term,$grade,$time){
    	$db = $this->getAdapter();
    	$sql="SELECT tfd.id,tfd.tuition_fee FROM rms_tuitionfee AS tf,rms_tuitionfee_detail AS tfd WHERE tf.id = fee_id
              AND tf.time=$time AND tfd.fee_id=$generat AND tfd.class_id=$grade AND tfd.payment_term=$payment_term LIMIT 1";
    	return $db->fetchRow($sql);
    }
   
    function getAllYearsProgramFee(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,'-',to_academic) AS years FROM rms_tuitionfee WHERE `status`=1";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    
    function getAllYearsServiceFee(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,'-',to_academic) AS years FROM rms_servicefee WHERE `status`=1";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
<<<<<<< .mine
    public function getNewAccountNumber($type,$stu_type){
=======
    
    public function getNewAccountNumber($type){
>>>>>>> .r436
    	$db = $this->getAdapter();
    	$sql="  SELECT COUNT(stu_id)  FROM rms_student WHERE stu_type=$stu_type";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$new_acc_no=100+$new_acc_no;
    	// echo $new_acc_no;exit();
    	$acc_no= strlen((int)$acc_no+1);
    	if($type==1 || $type==2 || $type==3 || $type==4){
    		$pre='';
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
//     function getAllGepOldStudent(){
//     	$db=$this->getAdapter();
//     	$sql="SELECT s.stu_id As stu_id,s.stu_code As stu_code FROM rms_student AS s,rms_student_payment AS sp 
//     	      WHERE s.stu_id=sp.student_id  AND s.stu_type=2 AND sp.payfor_type=2";
//     	return $db->fetchAll($sql);
//     }
    //select Gep old student by id 
    function getGepOldStudent($stu_id){
    	$db=$this->getAdapter();
    	$sql="SELECT stu_id,stu_enname,stu_khname,sex,`session` As ses,degree,grade FROM rms_student 
    	       WHERE  stu_type=2 AND stu_id=$stu_id LIMIT 1";
    	return $db->fetchRow($sql);
    }
    //select all Gerneral old student
    function getAllGerneralOldStudent(){
    	$db=$this->getAdapter();
    	$sql="SELECT s.stu_id As stu_id,s.stu_code As stu_code FROM rms_student AS s,rms_student_payment AS sp
    	WHERE s.stu_id=sp.student_id  AND s.stu_type=1 AND sp.payfor_type=1";
    	return $db->fetchAll($sql);
    }
    //select general  old student by id
    function getGeneralOldStudentById($stu_id){
    	$db=$this->getAdapter();
    	$sql="SELECT stu_id,stu_enname,stu_khname,sex,`session` As ses,degree,grade FROM rms_student
    	WHERE  stu_type=1 AND stu_id=$stu_id LIMIT 1";
    	return $db->fetchRow($sql);
    }
}

