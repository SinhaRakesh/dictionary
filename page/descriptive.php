<?php

namespace xavoc\dictionary;

class page_descriptive extends \xepan\base\Page{
	public $title = "Descriptive Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Descriptive');
		$crud->setModel($model);

	}
}