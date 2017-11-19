<?php

class page_descriptive extends Page{
	public $title = "Descriptive Management";
	
	function init(){
		parent::init();

		$crud = $this->add('CRUD');
		$model = $this->add('Model_Descriptive');
		$crud->setModel($model);

	}
}