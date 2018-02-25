<?php

namespace xavoc\dictionary;

class Tool_Testimonial extends \xepan\cms\View_Tool{
	public $options = [];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		
		$this->lister = $this->add('xavoc\dictionary\View_Testimonial',['options'=>$this->options]);
	}

	function getTemplate(){
		return $this->lister->template;
	}
}