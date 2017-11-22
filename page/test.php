<?php

namespace xavoc\dictionary;

class page_test extends \Page{
	public $title = "test";
	
	function init(){
		parent::init();

		$crud = $this->add('CRUD');
		$model = $this->add('xavoc\dictionary\Model_Dictionary');
		$crud->setModel($model);

	}
}