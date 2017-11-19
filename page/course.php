<?php

class page_course extends Page{
	public $title = "Course Management";
	
	function init(){
		parent::init();

		$crud = $this->add('CRUD');
		$model = $this->add('Model_Course');
		$crud->setModel($model);

	}
}