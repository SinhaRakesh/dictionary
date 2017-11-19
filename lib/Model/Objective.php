<?php

class Model_Objective extends Model_Library{

	function init(){
		parent::init();

		$this->addCondition('type','Objective');
	}
}