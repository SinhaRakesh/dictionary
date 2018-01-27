<?php
namespace xavoc\dictionary;

class page_article extends \xepan\base\Page{
	public $title = "Article Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Article');
		$model->setOrder('id','desc');
		$crud->setModel($model,['name','description','display_order','slug_url','image_id','image','status','is_popular','keyword','keyword_description'],['name','description','display_order','slug_url','image','status','is_popular']);

		$crud->grid->addHook('formatRow',function($g){
			$g->current_row_html['image'] = '<img style="width:200px;" src="'.$g->model['image'].'" />';
		});

		$crud->grid->addFormatter('name','wrap');
		$crud->grid->removeColumn('status');
		$crud->grid->removeAttachment();

		$crud->grid->addQuickSearch(['name']);
		$crud->grid->addPaginator($ipp=50);
	}
}