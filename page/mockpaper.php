<?php

namespace xavoc\dictionary;

class page_mockpaper extends \xepan\base\Page{
	public $title = "Mock Paper Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_MockPaper');
		$model->setOrder('id','desc');
		$crud->setModel($model);

		$crud->grid->addHook('formatRow',function($g){
			$g->current_row_html['image'] = '<img style="width:100px;" src="'.$g->model['image'].'" />';
		});

		if($crud->isEditing()){
			$form = $crud->form;
			$form->getElement('parent_course_id')
					->getModel()
					->addCondition('is_mock_category',true)
					->addCondition([['is_mock_paper',false],['is_mock_paper',null]])
					;
		}
		
		$crud->grid->removeAttachment();
		$crud->grid->addPaginator($ipp=25);
		$crud->grid->addQuickSearch(['name']);
		$crud->grid->removeColumn('keyword');
	}
}