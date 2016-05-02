<?php

class Registrar_Model_DbTable_DbRegister extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_student';
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
				    $db->getProfiler()->setEnabled(true);
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
				$db->getProfiler()->setEnabled(true);
				$paymentid = $this->insert($arr);
				$this->_name='rms_student_paymentdetail';
	            //update is_start=0 and is_parent=$payment_id_ser when add old student 
				$payment_id_ser = $this->getStudentPaymentStart($id,1);
				if(empty($payment_id_ser)){
					$payment_id_ser=0;
				}
				$where="id = $payment_id_ser ";
				$arr = array(
						'is_start'=>0
				);
				$db->getProfiler()->setEnabled(true);
				$this->update($arr,$where);
				//update is_complet = 1 becuse for get balance price 
				$this->_name='rms_student_paymentdetail';
				if(!empty($data['ids'])){
					$where="id=".$data['ids'];
					$arr = array(
							'is_complete'=>1,
							'comment'=>"បង់រួច",
					);
					$db->getProfiler()->setEnabled(true);
					$this->update($arr,$where);
				}
				$complete=1;
				if($data['remaining']>0){
					$complete=0;
					$comment="មិនទាន់បង់";
				}else{
					$complete=1;
					$comment="បង់រួច";
				}
				$arr=array(
						'payment_id'=>$paymentid,
						'type'=>1,
						'service_id'=>1,
						'payment_term'=>$data['payment_term'],
						'fee'=>$data['tuitionfee'],
						'qty'=>1,
						'subtotal'=>$data['total'],
						'paidamount'=>$data['books'],
						'balance'=>$data['remaining'],
						'discount_percent'=>$data['discount'],
						'discount_fix'=>0,
						'note'=>$data['not'],
						'start_date'=>$data['start_date'],
						'validate'=>$data['end_date'],
						'references'=>'frome registration',
					 	'is_parent'		=>$payment_id_ser,
						'is_complete'	=>$complete,
						'comment'		=>$comment,
						'user_id'=>$this->getUserId(),
				);
				$db->getProfiler()->setEnabled(true);
				$this->insert($arr);
				 $db->commit();//if not errore it do....
			}catch (Exception $e){
				$db->rollBack();//អោយវាវិលត្រលប់ទៅដើមវីញពេលណាវាជួបErrore
			}
		}
		function updateRegister($data){
			$db = $this->getAdapter();//ស្ពានភ្ជាប់ទៅកាន់Data Base
			$db->beginTransaction();//ទប់ស្កាត់មើលការErrore , មានErrore វាមិនអោយចូល
	//	print_r($data);exit();
			try{
				//សំរាប់ update សិស្សចាស់ is start = 1 វិញ
				if($data["parent_id"]!=0){
					if($data["id"]!=$data["old_studens"]){
						
						$arr = array(
								'is_start'=>1
						);
						$this->_name='rms_student_paymentdetail';
						$where="payment_id = " .$data["parent_id"];
						$db->getProfiler()->setEnabled(true);
						$this->update($arr,$where);
						Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
						Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
						$db->getProfiler()->setEnabled(false); 
					}
				}
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
		  // print_r($data);exit();
				$arr=array(
						'student_id'=>$data['old_studens'],
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
				
				$payment_id_ser = $this->getStudentPaymentStart($data['old_studens'],1);
				if($data['id']!=$data['old_studens']){
				if(empty($payment_id_ser)){
					$payment_id_ser=0;
				}
				$this->_name='rms_student_paymentdetail';
				$where="payment_id = $payment_id_ser ";
				$arr = array(
						'is_start'=>0
				);
				$db->getProfiler()->setEnabled(true);
				$this->update($arr,$where);
				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
				$db->getProfiler()->setEnabled(false);
				}
				$this->_name='rms_student_paymentdetail';
				if(!empty($data['ids'])){
					$where="id=".$data['ids'];
					$arr = array(
							'is_complete'=>1,
							
							'comment'=>"បង់រួច",
					);
					$db->getProfiler()->setEnabled(true);
					$this->update($arr,$where);
				}
				$complete=1;
				if($data['remaining']>0){
					$complete=0;
					$comment="មិនទាន់បង់";
				}else{
					$complete=1;
					
					$comment="បង់រួច";
				}
				$arr=array(
						'payment_id'=>$data['pay_id'],
						'type'=>1,
						'service_id'=>1,
						'payment_term'=>$data['payment_term'],
						'fee'=>$data['tuitionfee'],
						'qty'=>1,
						'subtotal'=>$data['total'],
						'paidamount'=>$data['books'],
						'balance'=>$data['remaining'],
						'discount_percent'=>$data['discount'],
						'discount_fix'=>0,
						'note'=>$data['not'],
						'start_date'=>$data['start_date'],
						'validate'=>$data['end_date'],
						'references'=>'frome registration',
						'is_complete'	=>$complete,
						'comment'		=>$comment,
					 	'is_parent'		=>$payment_id_ser,
						'user_id'=>$this->getUserId(),
				);
				$where="payment_id=".$data['pay_id'];
				$this->update($arr, $where);
			   // exit();	
			 	$db->commit();//if not errore it do....
			}catch (Exception $e){
				$db->rollBack();//អោយវាវិលត្រលប់ទៅដើមវីញពេលណាវាជួបErrore
			}
		}
    function getAllStudentRegister($search=null){
    	$session_user=new Zend_Session_Namespace('auth');
    	$user_id=$session_user->user_id;
    	$db=$this->getAdapter();
     	$from_date = (empty($search["start_date"]))?'Y-m-01':$search["start_date"];
     	$to_date =  (empty($search["end_date"]))?'Y-m-01' :$search["end_date"];
     	$where =" AND sp.create_date BETWEEN '$from_date' AND '$to_date'";
    	$sql=" SELECT sp.id,s.stu_code,sp.receipt_number,s.stu_khname,s.stu_enname,s.sex,(SELECT en_name FROM rms_dept WHERE dept_id=s.degree)AS degree,
		       (SELECT major_enname FROM rms_major WHERE major_id=s.grade ) AS grade,
 		       sp.payment_term,sp.tuition_fee,sp.discount_percent, sp.total,sp.paid_amount,
		       sp.balance_due,sp.create_date
 			   FROM rms_student AS s,rms_student_payment AS sp WHERE s.stu_id=sp.student_id AND s.stu_type=1 AND sp.user_id=$user_id";
    	
    	if(!empty($search['adv_search'])){
    		$s_where=array();
    		$s_search=addslashes(trim($search['adv_search']));
    		$s_where[]= " stu_code LIKE '%{$s_search}%'";
    		$s_where[]=" receipt_number LIKE '%{$s_search}%'";
    		$s_where[]= " stu_khname LIKE '%{$s_search}%'";
    		$s_where[]= " stu_enname LIKE '%{$s_search}%'";
    		$s_where[]= " grade LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ', $s_where).')';
    	}
    	if(!empty($search['degree'])){
    		$where.=" AND s.degree=".$search['degree'];
    	}
    	$order=" ORDER By stu_id DESC ";
    	//print_r($sql.$where);
    	return $db->fetchAll($sql.$where.$order);
    }
    function getRegisterById($id){
    	$db=$this->getAdapter();
    	$sql=" SELECT s.stu_id,s.stu_code,sp.receipt_number,s.academic_year,s.stu_khname,s.stu_enname,s.sex,s.session,s.degree,s.grade,
		    	sp.payment_term,sp.tuition_fee,sp.discount_percent,sp.other_fee,sp.admin_fee,sp.total,sp.paid_amount,
		    	sp.balance_due,sp.amount_in_khmer,sp.note,sp.student_type,sp.time,sp.end_hour,spd.start_date,spd.validate,spd.is_start,spd.is_parent
		    	FROM rms_student AS s,rms_student_payment AS sp ,rms_student_paymentdetail AS spd
		    	WHERE s.stu_id=sp.student_id AND sp.id=spd.payment_id AND sp.id=".$id;
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
    function getBalance($serviceid,$studentid){
    	$db = $this->getAdapter();
    	$sql="select rms_student_paymentdetail.id,rms_student_paymentdetail.validate,balance AS price_fee
    	from rms_student_paymentdetail,rms_student_payment where rms_student_payment.id=rms_student_paymentdetail.payment_id
    	and rms_student_paymentdetail.service_id=$serviceid and rms_student_payment.student_id=$studentid and is_complete=0 limit 1";
    	$row=$db->fetchRow($sql);
    	if($row['price_fee'] > 0){
    	    $row['sms']='លុយជំពាក់ពីមុន';
    		return $row;
    	}
    }
   
    function getAllYearsProgramFee(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,'-',to_academic,'(',generation,')') AS years FROM rms_tuitionfee
    	        WHERE `status`=1 GROUP BY from_academic,to_academic,generation";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    
    function getAllYears(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,'-',to_academic,'(',generation,')') AS years FROM rms_tuitionfee WHERE `status`=1 
    	        GROUP BY from_academic,to_academic,generation";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    
    function getAllYearsServiceFee(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,'-',to_academic) AS years FROM rms_servicefee WHERE `status`=1";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
 
    public function getNewAccountNumber($type,$stu_type){
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
    		$sql="SELECT shortcut FROM rms_dept WHERE dept_id=$type LIMIT 1";
    		$shortcut=$db->fetchOne($sql);
    		$pre=$shortcut;
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
    ///select degree searching 
    function getDegree(){
    	$db=$this->getAdapter();
    	$sql="SELECT dept_id AS id,CONCAT(en_name,'-',kh_name) AS `name` FROM rms_dept  WHERE dept_id  IN(1,2,3,4)";
    	return $db->fetchAll($sql);
    }
}

