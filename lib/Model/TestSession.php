<?php

namespace xavoc\dictionary;

class Model_TestSession extends \Model{

	function init(){
		parent::init();

		$this->setSource('Session');

		$this->addField('paperid');
		$this->addField('userid');
		$this->addField('questionid');
		$this->addField('answer');
		
	}
}