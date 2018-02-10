<?php

namespace xavoc\dictionary;

class page_student extends \Page{
	public $title = "Student Management";
	
	function init(){
		parent::init();

		$crud = $this->add('CRUD');
		$crud->setModel('xavoc\dictionary\Model_Student');

		// $this->add('xavoc\dictionary\View_CourseFooter');
		// $crud = $this->add('CRUD');
		// $model = $this->add('xavoc\dictionary\Model_Dictionary');
		// $crud->setModel($model);

	}
}