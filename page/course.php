<?php

namespace xavoc\dictionary;

class page_course extends \xepan\base\Page{
	public $title = "Course Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Course');
		$crud->setModel($model);

	}
}