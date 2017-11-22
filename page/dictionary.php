<?php
namespace xavoc\dictionary;

class page_dictionary extends \xepan\base\Page{
	public $title = "Dictionary Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Dictionary');
		$crud->setModel($model);

	}
}