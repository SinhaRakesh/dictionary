<?php
namespace xavoc\dictionary;

class Model_Objective extends Model_Library{

	function init(){
		parent::init();

		$this->addCondition('type','Objective');

		$this->getElement('description')->display(array('form'=>'xepan\base\RichText'));
	}
}