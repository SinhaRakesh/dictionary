<?php

namespace xavoc\dictionary;

class Tool_New extends \xepan\cms\View_Tool{
	public $options = [
			'result_page'=>'course-detail'
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		$this->add('View')->set('hello')->addClass('btn btn-info');
	}
}