<?php

namespace xavoc\dictionary;
class View_CourseFooter extends \CompleteLister{
		public $options = [];

	function init(){
		parent::init();
		
		
		$model = $this->add('xavoc\dictionary\Model_Course');
		
		// $model->addExpression('chield_course_count')->set(function($m,$q){
		// 	$mdl = $m->add('xavoc\dictionary\Model_Course',['table_alias'=>'childtable'])
		// 		->addCondition('parent_course_id',$m->getElement('id'));
		// 	return $q->expr('[0]',[$mdl->count()]);
		// })->type('int');

		$model->addExpression('chield_paper_count')->set(function($m,$q){
			$mdl = $m->add('xavoc\dictionary\Model_Paper',['table_alias'=>'childtablepaper'])
				->addCondition('parent_course_id',$m->getElement('id'));
			return $q->expr('[0]',[$mdl->count()]);
		})->type('int');

		// $model->addCondition('chield_course_count',0);
		$model->addCondition('chield_paper_count','>',0);
		$model->setOrder('display_sequence','desc');
		
		$this->setModel($model);
	}
	
	function formatRow(){

		if($this->model['page_name'])
			if($this->model['slug_url'])
				$this->current_row_html['url'] = $this->app->url($this->model['page_name'],['slug'=>$this['slug_url']]);
			else
				$this->current_row_html['url'] = $this->app->url($this->model['page_name']);
		else
			$this->current_row_html['url'] = "";

		parent::formatRow();
	}

	function defaultTemplate(){
		return ['view/tool/coursefooter'];
	}

}