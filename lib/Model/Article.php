<?php

namespace xavoc\dictionary;

class Model_Article extends Model_Library{

	function init(){
		parent::init();

		$this->addCondition('type','Article');

		$this->getElement('created_at')->system(true);
		$this->getElement('a')->destroy();
		$this->getElement('b')->destroy();
		$this->getElement('c')->destroy();
		$this->getElement('d')->destroy();
		$this->getElement('answer')->destroy();
		$this->getElement('is_word_of_day')->destroy();
		$this->getElement('sentance')->destroy();
		$this->getElement('synonyms')->destroy();
		$this->getElement('antonyms')->destroy();

		$this->addExpression('day')->set('DAY(created_at)');
		$this->addExpression('month')->set('DATE_FORMAT(created_at,"%b")');
		
	}
}