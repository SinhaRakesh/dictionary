<?php

namespace xavoc\dictionary;

class Model_Student extends \xepan\base\Model_Contact{
	
	function init(){
		parent::init();

		$this->getElement('created_by')->system(true);
		$this->getElement('assign_to')->system(true);
		$this->getElement('created_at')->system(true);
		$this->getElement('updated_at')->system(true);

		$this->addCondition('type','Student');
	}


	function addMockTest($mock_paper){
		if(!$this->loaded()) throw new \Exception("Add Mock Test student record not loaded");
				
		$record = $this->add('xavoc\dictionary\Model_MockTest');
		$record['paper_id'] = $mock_paper->id;
		$record['user_id'] = $this->app->auth->model->id;
		$record['student_id'] = $this->id;

		$record['started_at'] = $this->app->now;
		$record['deadline_at'] = date('Y-m-d H:i:s',strtotime("+".$mock_paper['mock_test_duration']." minutes", strtotime($this->app->now)));
		$record->save();

		return $record;		

	}
}