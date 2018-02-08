<?php

namespace xavoc\dictionary;

class Model_MockTestRecord extends Model_Base_Table{
	public $table = "mock_test_record";
	function init(){
		parent::init();

		$this->hasOne('xavoc\dictionary\MockTest','mock_test_id');
		$this->hasOne('xavoc\dictionary\Library','question_id');
		$this->addField('answer');
		$this->addField('original_answer');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}