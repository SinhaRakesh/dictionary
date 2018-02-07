<?php

namespace xavoc\dictionary;

class Model_MockPaper extends Model_Course{

	function init(){
		parent::init();

		$this->addCondition('is_paper',false);
		$this->addCondition('is_mock_paper',true);
		$this->addCondition('paper_type','Objective');

		$this->getElement('description')->display(array('form'=>'xepan\base\RichText'));
		$this->getElement('is_mock_category')->defaultValue(false)->system(true);
		$this->getElement('created_at')->system(true);
		$this->getElement('display_in_menu_bar')->system(true);
		$this->getElement('page_name')->defaultValue('mock-test')->system(true);
		
		$this->is([
			'parent_course_id|required'
		]);
	}


	function getQuestions(){
		$model = $this->add('xavoc\dictionary\Model_Objective');
		$aj = $model->join('library_course_association.library_id');
		$aj->addField('course_id');
		$model->addCondition('course_id',$this->id);
		$model->setOrder('display_order','asc');
		return $model;
	}

}