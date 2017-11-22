<?php

namespace xavoc\dictionary;

class Tool_Course extends \xepan\cms\View_Tool{
	public $options = [
			'template'=>'coursemenu'
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		$this->lister = $this->add('xavoc\dictionary\View_CourseLister',['options'=>$this->options]);
	}

	function getTemplate(){
		return $this->lister->template;
	}
}