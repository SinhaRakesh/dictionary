<?php

class Model_Descriptive extends Model_Library{

	function init(){
		parent::init();

		$this->addCondition('type','Descriptive');

		$this->getElement('created_at')->system(true);
		$this->getElement('a')->destroy();
		$this->getElement('b')->destroy();
		$this->getElement('c')->destroy();
		$this->getElement('d')->destroy();
		$this->getElement('answer')->destroy();
	}
}