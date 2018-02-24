<?php

namespace xavoc\dictionary;

class page_course extends \xepan\base\Page{
	public $title = "Course Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Course');
		$model->addCondition('is_mock_paper',false);
		$model->addCondition([['is_paper',false],['is_paper',null]]);
		$crud->setModel($model,['parent_course_id','name','page_name','display_sequence','slug_url','display_in_menu_bar','description','keyword'],['name','parent_course','slug_url','status','action','display_sequence']);

		$crud->grid->removeAttachment();

		$crud->grid->addPaginator($ipp=10);
		$crud->grid->addQuickSearch(['name']);
	}
}