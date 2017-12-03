<?php
namespace xavoc\dictionary;

class page_article extends \xepan\base\Page{
	public $title = "Article Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Article');
		$crud->setModel($model);

		$crud->grid->addQuickSearch(['name']);
	}
}