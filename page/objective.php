<?php

class page_objective extends Page{
	public $title = "Objective Management";
	
	function init(){
		parent::init();

		$crud = $this->add('CRUD');
		$model = $this->add('Model_Objective');
		$crud->setModel($model);

	}
}