<?php

namespace xavoc\dictionary;

class Tool_CourseDetail extends \xepan\cms\View_Tool{

	public $options = [];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		$slug = $_GET['slug'];
		
		$course = $this->add('xavoc\dictionary\Model_Course');
		$course->addCondition('slug_url',$slug);		
		$course->tryLoadAny();
		if(!$course->loaded()){
			
			$this->add('View')->addClass('alert alert-danger')->set('no record found');
			return;
		}

		$this->template->trySet('heading',$course['name']);

		$paper = $this->add('xavoc\dictionary\Model_Paper');
		$paper->addCondition('parent_course_id',$course->id);
		$list = $this->add('Lister',null,'paper_list',['view\tool\coursedetail','paper_list']);
		$list->addHook('formatRow',function($l){
			$l->current_row['url'] = $this->app->url($l->model['page_name'],['slug'=>$l->model['slug_url']]);
		});

		$list->setModel($paper);
	}

	function defaultTemplate(){
		return ['view\tool\coursedetail'];
	}
}