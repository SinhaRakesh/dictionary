<?php

class page_dictionary extends Page{
	public $title = "Dictionary Management";
	
	function init(){
		parent::init();

		$crud = $this->add('CRUD');
		$model = $this->add('Model_Dictionary');
		$crud->setModel($model);

	}
}