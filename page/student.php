<?php

namespace xavoc\dictionary;

class page_student extends \Page{
	public $title = "Student Management";
	
	function init(){
		parent::init();

		$crud = $this->add('CRUD');
		$crud->setModel('xavoc\dictionary\Model_Student',['user','name','emails_str','contacts_str','image_id','image']);
		$crud->grid->addPaginator(10);
		$crud->grid->addHook('formatRow',function($g){
			$g->current_row_html['image'] = '<img style="width:100px;" src="'.$g->model['image'].'" />';
		});

		$crud->grid->add('misc\Export');
		// $this->add('xavoc\dictionary\View_CourseFooter');
		// $crud = $this->add('CRUD');
		// $model = $this->add('xavoc\dictionary\Model_Dictionary');
		// $crud->setModel($model);

	}
}