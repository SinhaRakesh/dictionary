<?php
namespace xavoc\dictionary;

class Model_LibraryCourseAssociation extends Model_Base_Table{
	public $table = "library_course_association";

	function init(){
		parent::init();

		$this->hasOne('Course','course_id');
		$this->hasOne('Library','library_id');
		
		
		$this->add('dynamic_model/Controller_AutoCreator');

	}
}