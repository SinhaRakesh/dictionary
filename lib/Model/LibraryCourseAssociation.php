<?php
namespace xavoc\dictionary;

class Model_LibraryCourseAssociation extends Model_Base_Table{
	public $table = "library_course_association";

	function init(){
		parent::init();

		$this->hasOne('xavoc\dictionary\Course','course_id');
		$this->hasOne('xavoc\dictionary\Library','library_id');
		
		
		$this->add('dynamic_model/Controller_AutoCreator');

		$this->addHook('beforeSave',$this);
	}

	function beforeSave(){

		$old = $this->add('xavoc\dictionary\Model_LibraryCourseAssociation');
		$old->addCondition('course_id',$this['course_id']);
		$old->addCondition('library_id',$this['library_id']);
		$old->addCondition('id','<>',$this['id']);
		$old->tryLoadAny();
		if($old->loaded()){
			throw $this->exception('library_id already added','ValidityCheck')
			->setField('library_id');
		}
		
	}

}