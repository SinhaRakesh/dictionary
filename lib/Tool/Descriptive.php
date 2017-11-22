<?php

namespace xavoc\dictionary;

class Tool_Descriptive extends \xepan\cms\View_Tool{
	public $options = [

		];

	function init(){
		parent::init();
		if($this->owner instanceof \AbstractController) return;

		
	}
}