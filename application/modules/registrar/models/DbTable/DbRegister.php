<?php

class Registrar_Model_DbTable_DbRegister extends Zend_Db_Table_Abstract
{
    protected $_name = 'rms_student';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	function addRegister($data){
		print_r($data);exit();
		$db = $this->getAdapter();//ស្ពានភ្ជាប់ទៅកាន់Data Base
		$db->beginTransaction();//ទប់ស្កាត់មើលការErrore , មានErrore វាមិនអោយចូល
			try{
			$arr=array(
						'client_id'=>$data['send_name'],
						'payment_term'=>$data['pay_term'],
						'date_keeping'=>$data['date'],
						'amount_keeping'=>$data['amount_month'],
						'exp_date'=>$data['epx_date'],
						'invoice_number'=>$data['report'],
						
						'status'=>$data['withdraw_dollar'],
						'date'=>$data['withdraw_bath'],
						'user_id'=>$data['withdraw_riel'],
				);
// 			print_r($arr);exit();
			$id=$this->insert($arr);
				$this->_name='rms_student_payment';
				$ids = explode(',',$data['record_row']);
				//print_r($ids);exit();
				foreach ($ids as $i){
					$arr = array(
							'keeping_id'=>$id,
							'currency_type'=>$data['type_money'.$i],
							'money_inacc'=>$data['money_inacc'.$i],
							'cut_money'=>empty($data['is_spacial'.$i])?0:1,
							'commission'=>$data['commission'.$i],
							'total_amount'=>$data['total_amount'.$i],
							'recieve_amount'=>$data['recieve_amount'.$i],
							'lbltotal_return'=>$data['lbltotal_return'.$i],
							'recieve_amount'=>$data['amount_exchanged'.$i],
							'note'=>$data['note'.$i],
					);
					$this->insert($arr);
				}
				$db->commit();//if not errore it do....
			}catch (Exception $e){
				$db->rollBack();//អោយវាវិលត្រលប់ទៅដើមវីញពេលណាវាជួបErrore
			}
		}
    function getAllGrade($grade_id){
    	$db = $this->getAdapter();
    	$sql = "SELECT major_id As id,major_enname As name FROM $this->_name WHERE dept_id=".$grade_id;
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function getPaymentTerm($generat,$payment_term,$grade){
    	$db = $this->getAdapter();
    	$sql="SELECT td.tuition_fee FROM rms_tuitionfee_detail AS td,`rms_tuitionfee` AS tu
    	WHERE tu.id= td.fee_id AND td.class_id=$grade AND td.payment_term=$payment_term AND tu.generation=$generat LIMIT 1";
    	return $db->fetchRow($sql);
    }
    function getAllYears(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,CONCAT(from_academic,'-',to_academic) AS years FROM rms_tuitionfee WHERE `status`=1";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
}

