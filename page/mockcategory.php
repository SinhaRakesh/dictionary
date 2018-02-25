<?php

namespace xavoc\dictionary;

class page_mockcategory extends \xepan\base\Page{
	public $title = "Mock Category Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_MockCategory');
		$model->setOrder('id','desc');
		$crud->setModel($model,['name','status','display_sequence','slug_url','description','image_id','keyword']);

		$crud->grid->addHook('formatRow',function($g){
			$g->current_row_html['image'] = '<img style="width:100px;" src="'.$g->model['image'].'" />';
		});
		
		$crud->grid->removeAttachment();
		$crud->grid->addPaginator($ipp=10);
		$crud->grid->addQuickSearch(['name']);
		$crud->grid->removeColumn('keyword');

		$crud->grid->addFormatter('name','Wrap');
		$crud->grid->addFormatter('slug_url','Wrap');
	}
}