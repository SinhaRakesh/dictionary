<?php

namespace xavoc\dictionary;

class Tool_MocTest extends \xepan\cms\View_Tool{
	public $options = [
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
	}
}