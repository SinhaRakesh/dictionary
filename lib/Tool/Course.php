<?php

namespace xavoc\dictionary;

class Tool_Course extends \xepan\cms\View_Tool{
	public $options = [
			'template'=>'coursemenu',
			'is_show_in_footer'=>false,
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		if($this->options['is_show_in_footer']){
			$this->lister = $this->add('xavoc\dictionary\View_CourseFooter',['options'=>$this->options]);
		}else
			$this->lister = $this->add('xavoc\dictionary\View_CourseLister',['options'=>$this->options]);
	}

	function getTemplate(){
		return $this->lister->template;
	}
}