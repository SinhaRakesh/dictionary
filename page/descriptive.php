<?php

namespace xavoc\dictionary;

class page_descriptive extends \xepan\base\Page{
	public $title = "Descriptive Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Descriptive');
		$model->setOrder('id','desc');
		$crud->setModel($model,['name','description','display_order','status','action'],['name','slug_url','status','action']);
		
		$crud->grid->removeAttachment();
		$crud->grid->addPaginator($ipp=25);
		$crud->grid->addQuickSearch(['name']);
	}
}