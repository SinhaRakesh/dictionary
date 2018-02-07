<?php

namespace xavoc\dictionary;

class Model_Paper extends Model_Course{

	function init(){
		parent::init();

		$this->addExpression('question_count')->set(function($m,$q){
			$asso = $m->add('xavoc\dictionary\Model_LibraryCourseAssociation');
			$asso->addCondition('course_id',$m->getElement('id'));
			$asso->addCondition('library_id','>',0);
			return $q->expr('[0]',[$asso->count()]);
		});

		$this->addCondition('is_paper',true);
		$this->addCondition('display_in_menu_bar',true);
		$this->addCondition('page_name','paper');

		$this->getElement('created_at')->system(true);
		$this->is([
			'paper_type|to_trim|required',
			'slug_url|to_trim|required',
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