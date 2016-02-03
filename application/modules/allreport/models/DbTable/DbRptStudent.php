<?php

class Allreport_Model_DbTable_DbRptStudent extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_student';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    public function getAllStudent($search){
    	$db = $this->getAdapter();
    	$sql ='select stu_id,CONCAT(stu_enname," - ",stu_khname)AS name,nationality,tel,email,stu_code,home_num,street_num,village_name,
    		  commune_name,district_name,CONCAT(father_enname," - ",father_khname)AS father_name,father_nation,father_phone,
    		  CONCAT(mother_enname," - ",mother_khname)AS mother_name,mother_nation,mother_phone,
    		  CONCAT(guardian_enname," - ",guardian_khname)AS guardian_name,guardian_nation,guardian_document,guardian_tel,guardian_email,
    		  
    		  (select occu_enname from rms_occupation where rms_occupation.occupation_id=rms_student.father_job limit 1)AS father_job,
    		  (select occu_enname from rms_occupation where rms_occupation.occupation_id=rms_student.mother_job limit 1)AS mother_job,
    		  (select occu_enname from rms_occupation where rms_occupation.occupation_id=rms_student.guardian_job limit 1)AS guardian_job,
    		  (select name_en from rms_view where rms_view.type=4 and rms_view.key_code=rms_student.session limit 1)AS session,
    		  (select major_enname from rms_major where rms_major.major_id=rms_student.grade limit 1)AS grade,
    		  (select en_name from rms_dept where rms_dept.dept_id=rms_student.degree limit 1)AS degree,
    		  (select province_en_name from rms_province where rms_province.province_id = rms_student.province_id limit 1)AS province,	   	
    		  (select name_en from rms_view where rms_view.type=2 and rms_view.key_code=rms_student.sex limit 1)AS sex
    		  from rms_student ';
    	$where=' where 1';
    	if(empty($search)){
    		return $db->fetchAll($sql);
    	}
    	
//     	if(!empty($search['txtsearch'])){
//     		$where.=" AND g.group_code LIKE '%".$search['txtsearch']."%'";
//     	}

    	$searchs = $search['txtsearch'];
    	if($search['searchby']==0){
    		$where.='';
    	}
    	if($search['searchby']==1){
    		$where.= " AND stu_code  LIKE  '%".$searchs."%'";
    	}
    	if($search['searchby']==2){
    		$where.= " AND CONCAT(stu_enname,stu_khname) LIKE '%".$searchs."%'" ;
    	}
    	if($search['searchby']==3){
    		$where.= " AND (select en_name from rms_dept where rms_dept.dept_id=rms_student.degree limit 1)  LIKE '%".$searchs."%'" ;
    	}
    	if($search['searchby']==4){
    		$where.= " AND (select major_enname from rms_major where rms_major.major_id=rms_student.grade limit 1)  LIKE  '%".$searchs."%'";
    	}
    	if($search['searchby']==5){
    		$where.= " AND (select name_en from rms_view where rms_view.type=4 and rms_view.key_code=rms_student.session limit 1)  LIKE  '%".$searchs."%'";
    	}
    	
    	return $db->fetchAll($sql.$where);
    	 
    }
   
    
       
}