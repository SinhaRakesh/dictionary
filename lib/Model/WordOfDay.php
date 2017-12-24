<?php

namespace xavoc\dictionary;

class Model_WordOfDay extends Model_Library{

	function init(){
		parent::init();

		$this->addCondition('is_word_of_day',true);

		$this->getElement('description')->display(array('form'=>'xepan\base\RichText'));
	}
}