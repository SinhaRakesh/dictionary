<?php
namespace xavoc\dictionary;

class page_newword extends \xepan\base\Page{
	public $title = "New Word Search";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Dictionary');
		$model->addCondition('is_auto_added',true);
		$crud->setModel($model,['name','slug_url','status']);

		$crud->grid->addQuickSearch(['name']);
		$crud->grid->removeAttachment();
		$crud->grid->add('misc/Export');
		$crud->grid->removeColumn('status');
	}
}