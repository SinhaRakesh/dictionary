<?php

namespace xavoc\dictionary;

class Model_Quote extends Model_Library{

	function init(){
		parent::init();

		$this->addCondition('type','Quote');
		
	}
}