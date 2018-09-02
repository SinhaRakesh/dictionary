<?php

namespace xavoc\dictionary;

class page_course extends \xepan\base\Page{
	public $title = "Course Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Course');
		$model->addCondition([['is_mock_paper',false],['is_mock_paper',null]]);
		$model->addCondition([['is_paper',false],['is_paper',null]]);
		$crud->setModel($model,['parent_course_id','name','page_name','display_sequence','slug_url','display_in_menu_bar','description','keyword'],['name','parent_course','slug_url','status','action','display_sequence']);
		if($crud->isEditing()){
			$form = $crud->form;
			$pc_field = $form->getElement('parent_course_id');
			$pc_model = $pc_field->getModel();
			$pc_model->addCondition([['is_mock_paper',false],['is_mock_paper',null]]);
			$pc_model->addCondition([['is_paper',false],['is_paper',null]]);

		// 	$cat_model = $this->add('xavoc\dictionary\Model_Course');
		// 	$cat_model->addCondition([['is_mock_paper',false],['is_mock_paper',null]]);
		// 	$cat_model->addCondition([['is_paper',false],['is_paper',null]]);


		// 	$cat_field = $form->addField('DropDown','Category');
		// 	$cat_field->setModel($cat_model);

		// 	$cat_field->js('change',$pc_field->js()->reload(null,null,[$this->app->url(null,['cut_object'=>$pc_field->name]),'pc_id'=>$cat_field->js()->val()]));
		// 	if($_GET['pc_id']){
		// 		$pc_field->getModel()->addCondition('parent_course_id',$_GET['pc_id']);
		// 	}
		}


		$crud->grid->removeAttachment();

		$crud->grid->addPaginator($ipp=10);
		$crud->grid->addQuickSearch(['name']);
	}
}