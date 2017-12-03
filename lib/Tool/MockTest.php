<?php

namespace xavoc\dictionary;

class Tool_MockTest extends \xepan\cms\View_Tool{
	public $options = [
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		$this->add('View')->set('Mock Test');
	}
}