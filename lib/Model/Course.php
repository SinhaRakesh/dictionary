<?php

namespace xavoc\dictionary;

class Model_Course extends Model_Base_Table{
	public $table = "course";
	public $status = ['Active','Inactive'];
	public $actions = [
			'Active'=>['view','deactive','edit','delete'],
			'Inactive'=>['view','active','edit','delete']
		];
	public $acl_type = "dictionary_course";

	function init(){
		parent::init();

		$this->hasOne('xavoc\dictionary\ParentCourse','parent_course_id')->sortable(true);

		$this->addField('name');
		$this->addField('status')->enum(['Active','Inactive']);
		$this->addField('page_name')->caption('course redirect to page');
		// $this->addField('display_sequence')->type('number');

		$this->hasMany('LibraryCourseAssociation','course_id');
		
		// $this->add('dynamic_model/Controller_AutoCreator');

	}
}