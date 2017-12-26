<?php
namespace xavoc\dictionary;

class page_objective extends \xepan\base\Page{
	public $title = "Objective Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Objective');
		$crud->setModel($model,['name','description','a','b','c','d','answer','display_order','status'],['name','answer','display_order','status']);
		
		$crud->grid->removeColumn('status');
		$crud->grid->addQuickSearch(['name']);
		$crud->grid->removeAttachment();

		$crud->grid->addPaginator($ipp=25);

		
	}
}