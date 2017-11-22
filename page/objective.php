<?php
namespace xavoc\dictionary;

class page_objective extends \xepan\base\Page{
	public $title = "Objective Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Objective');
		$crud->setModel($model);

	}
}