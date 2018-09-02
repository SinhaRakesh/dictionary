<?php

namespace xavoc\dictionary;

class page_paper extends \xepan\base\Page{
	public $title = "Paper Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Paper');
		$model->getElement('mock_test_duration')->system(true);
		$model->addCondition('is_paper',true);
		$model->addCondition('is_mock_paper',false);
		$model->addCondition('is_mock_category',false);

		$model->setOrder('id','desc');
		$crud->setModel($model);

		if($crud->isEditing()){
			$form = $crud->form;
			$pc_field = $form->getElement('parent_course_id');
			$pc_model = $pc_field->getModel();
			$pc_model->addCondition([['is_mock_paper',false],['is_mock_paper',null]]);
			$pc_model->addCondition([['is_paper',false],['is_paper',null]]);
		}

		$crud->grid->addHook('formatRow',function($g){
			$g->current_row_html['image'] = '<img style="width:100px;" src="'.$g->model['image'].'" />';
		});

		if($crud->isEditing()){
			$form = $crud->form;
			$form->getElement('parent_course_id')
					->getModel()
					->addCondition([['is_paper',false],['is_paper',null]])
					;
		}
		
		$crud->grid->removeAttachment();
		$crud->grid->addPaginator($ipp=10);
		$crud->grid->addQuickSearch(['name']);
		$crud->grid->removeColumn('keyword');
		$crud->grid->removeColumn('status');
		
		$crud->grid->addFormatter('name','Wrap');
		$crud->grid->addFormatter('parent_course','Wrap');
		$crud->grid->addFormatter('slug_url','Wrap');
		$crud->grid->addFormatter('description','Wrap');
	}
}