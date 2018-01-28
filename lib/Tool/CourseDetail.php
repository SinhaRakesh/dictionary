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

		$this->app->template->trySet('title',$course['name']);
		if($course['keyword'])
			$this->app->template->trySet('meta_keywords',$course['keyword']);
		if($course['description'])
			$this->app->template->trySet('meta_description',$course['description']);
				
		$paper = $this->add('xavoc\dictionary\Model_Paper');
		$paper->addCondition('parent_course_id',$course->id);

		$has_paper = 1;
		if($paper->count()->getOne()){
			$list = $this->add('CompleteLister',null,'paper_list',['view\tool\coursedetail','paper_list']);
			$list->addHook('formatRow',function($l){
				$l->current_row['url'] = $this->app->url($l->model['page_name'],['slug'=>$l->model['slug_url']]);
			});
			$list->setModel($paper);
			$list->template->trySet('heading',$course['name']);

			if($paper->count()->getOne() > 20){
					$paginator = $list->add('paginator',['ipp'=>20]);
					$paginator->setRowsPerPage(20);
			}else{
				$list->template->tryDel('paginator_wrapper');
			}
		}else{
			$has_paper = 0;
		}

		$model = $this->add('xavoc\dictionary\Model_Course')
					->addCondition('parent_course_id',$course->id);

		if($model->count()->getOne()){
			foreach ($model as $m) {
				$paper = $this->add('xavoc\dictionary\Model_Paper');
				$paper->addCondition('parent_course_id',$m->id);
				if(!$paper->count()->getOne()){
					return;
				}

				$list = $this->add('CompleteLister',null,'paper_list',['view\tool\coursedetail','paper_list']);
				$list->addHook('formatRow',function($l){
					$l->current_row['url'] = $this->app->url($l->model['page_name'],['slug'=>$l->model['slug_url']]);
				});

				$list->setModel($paper);
				$list->template->trySet('heading',$m['name']);

				if($paper->count()->getOne() > 20){
					$paginator = $list->add('paginator',['ipp'=>20]);
					$paginator->setRowsPerPage(20);
				}else{
					$list->template->tryDel('paginator_wrapper');
				}
			}
			$has_paper = 1;
		}else{
			$has_paper = 0;
		}

		if(!$has_paper){
			$this->add('View')
				->set('we are uploading paper for selected subject');
		}

	}

	function defaultTemplate(){
		return ['view\tool\coursedetail'];
	}
}