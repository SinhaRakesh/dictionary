<?php

class Model_Course extends Model_Base_Table{
	public $table = "course";

	function init(){
		parent::init();

		$this->addField('name');
		$this->addField('is_active')->type('boolean');

		$this->hasMany('LibraryCourseAssociation','course_id');
		
		$this->add('dynamic_model/Controller_AutoCreator');

	}
}