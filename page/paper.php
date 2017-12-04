<?php

namespace xavoc\dictionary;

class page_paper extends \xepan\base\Page{
	public $title = "Paper Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Paper');
		$crud->setModel($model);

		if($crud->isEditing()){
			$form = $crud->form;
			$form->getElement('parent_course_id')
					->getModel()
					->addCondition([['is_paper',false],['is_paper',null]])
					;
		}
		
		$crud->grid->removeAttachment();
	}
}