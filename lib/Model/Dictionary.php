<?php

class Model_Dictionary extends Model_Library{

	function init(){
		parent::init();

		$this->addCondition('type','Dictionary');

		$this->getElement('created_at')->system(true);
		$this->getElement('a')->destroy();
		$this->getElement('b')->destroy();
		$this->getElement('c')->destroy();
		$this->getElement('d')->destroy();
		$this->getElement('answer')->destroy();
	}
}