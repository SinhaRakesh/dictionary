<?php

namespace xavoc\dictionary;

class Model_Student extends \xepan\base\Model_Contact{
	
	function init(){
		parent::init();

		$this->addCondition('type','Student');
	}
}