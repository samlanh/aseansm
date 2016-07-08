<?php 

class Registrar_Form_FrmSearchInfor extends Zend_Dojo_Form
{
	protected $tr = null;
	protected $btn =null;//text validate
	protected $filter = null;
	protected $text =null;
	protected $validate = null;

	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		$this->btn = 'dijit.form.Button';
		//$this->validate = 'dijit.form.TextBox';
	}
	public function FrmSearchRegister($data=null){ 
		$request=Zend_Controller_Front::getInstance()->getRequest();
	
		$_title = new Zend_Dojo_Form_Element_TextBox('title');
		$_title->setAttribs(array('dojoType'=>$this->text,
				'placeholder'=>$this->tr->translate("SEARCH_FACULTY_NAME")));
		$_title->setValue($request->getParam("title"));
	    
		$generation = new Zend_Dojo_Form_Element_FilteringSelect('study_year');
		$generation->setAttribs(array(
				'dojoType'=>$this->filter,
				'class'=>'fullside',
				'required'=>false
		));
		$generation->setValue($request->getParam("study_year"));
		$db_years=new Registrar_Model_DbTable_DbRegister();
		$years=$db_years->getAllYears();
		$opt = array(''=>$this->tr->translate("SELECT_YEAR"));
		if(!empty($years))foreach($years AS $row) $opt[$row['id']]=$row['years'];
		$generation->setMultiOptions($opt);
		$_session = new Zend_Dojo_Form_Element_FilteringSelect('session');
		$_session->setAttribs(array(
				'dojoType'=>$this->filter,
				'placeholder'=>$this->tr->translate("SESSION"),
				'class'=>'fullside',
				'required'=>false
				));
		$_session->setValue($request->getParam("session"));
		$opt_ses=new Application_Model_DbTable_DbGlobal();
		$opt_sesion=$opt_ses->getSession();
		$opt_session = array(''=>$this->tr->translate("SESSION"));
		if(!empty($opt_sesion))foreach ($opt_sesion As $rs)$opt_session[$rs['key_code']]=$rs['view_name'];
		$_session->setMultiOptions($opt_session);
		
		$_time = new Zend_Dojo_Form_Element_FilteringSelect('time');
		$_time->setAttribs(array('dojoType'=>$this->filter,
				'placeholder'=>$this->tr->translate("TIME"),
				'class'=>'fullside',
				'required'=>false
				));
		$_time->setValue($request->getParam("time"));
		$opt_time = array(''=>$this->tr->translate("TIME"),
				 1=>$this->tr->translate("PART_TIME"),
				 2=>$this->tr->translate("FULL_TIME"),
				);
		$_time->setMultiOptions($opt_time);
		$_grade = new Zend_Dojo_Form_Element_FilteringSelect('grade');
		$_grade->setAttribs(array('dojoType'=>$this->filter,
				'placeholder'=>$this->tr->translate("GRADE"),
				'class'=>'fullside',
				'required'=>false
				));
		$_grade->setValue($request->getParam('grade'));
		$opt_g = array(''=>$this->tr->translate("GRADE"));
		$opt_grade=$db_years->getGradeAll();
		if(!empty($opt_grade))foreach ($opt_grade As $rows)$opt_g[$rows['id']]=$rows['name'];
		$_grade->setMultiOptions($opt_g);
		//add gep controll 
		$_grade_gep = new Zend_Dojo_Form_Element_FilteringSelect('grade_gep');
		$_grade_gep->setAttribs(array('dojoType'=>$this->filter,
				'placeholder'=>$this->tr->translate("GRADE"),
				'class'=>'fullside',
				'required'=>false
		));
		$_grade_gep->setValue($request->getParam('grade_gep'));
		$opt_gep = array(''=>$this->tr->translate("GRADE"));
		$opt_grade_gep=$db_years->getGradeGepAll();
		if(!empty($opt_grade_gep))foreach ($opt_grade_gep As $row)$opt_gep[$row['id']]=$row['name'];
		$_grade_gep->setMultiOptions($opt_gep);
		
		$sess_gep = new Zend_Dojo_Form_Element_FilteringSelect('sess_gep');
		$sess_gep->setAttribs(array('dojoType'=>$this->filter,
				'placeholder'=>$this->tr->translate("TIME"),
				'class'=>'fullside',
				'required'=>false
		));
		$sess_gep->setValue($request->getParam("sess_gep"));
		$ses_gep = array(''=>$this->tr->translate("TIME"),
				1=>$this->tr->translate("PART_TIME"),
				2=>$this->tr->translate("FULL_TIME"),
		);
		$sess_gep->setMultiOptions($ses_gep);
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status_search');
		$_status->setAttribs(array('dojoType'=>$this->filter,));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL_STATUS"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		$_status->setValue($request->getParam("status_search"));
	
		$this->addElements(array($sess_gep,$_title,$generation,$_session,$_time,$_grade,$_status,$_grade_gep));
		return $this;
	} 


}
