<?php

namespace xavoc\dictionary;

class page_descriptive extends \xepan\base\Page{
	public $title = "Descriptive Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Descriptive');
		$model->getElement('course_str')->caption('Paper');
		$model->setOrder('id','desc');
		$crud->setModel($model,['name','description','display_order','status','action'],['name','status','action','course_str']);
			
		$crud->grid->removeAttachment();
		$crud->grid->addPaginator($ipp=10);
		$crud->grid->addQuickSearch(['name']);

		$crud->grid->addFormatter('name','Wrap');
		$crud->grid->removeColumn('status');
		// $crud->grid->addFormatter('slug_url','Wrap');


	}
}