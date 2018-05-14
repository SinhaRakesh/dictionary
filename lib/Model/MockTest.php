<?php

namespace xavoc\dictionary;

class Model_MockTest extends Model_Base_Table{

	public $table = "mock_test";

	function init(){
		parent::init();

		$this->hasOne('xavoc\dictionary\MockPaper','paper_id');
		$this->hasOne('xepan\base\User','user_id');
		$this->hasOne('xavoc\dictionary\Student','student_id');
		
		$this->addField('started_at')->type('datetime')->defaultValue($this->app->now);
		$this->addField('finished_at')->type('datetime');
		$this->addField('deadline_at')->type('datetime');

		$this->hasMany('xavoc\dictionary\MockTestRecord','mock_test_id',null,'Records');

		$this->addExpression('attended_question')->set($this->refSQL('Records')->count());
		$this->addExpression('right_answer')->set($this->refSQL('Records')->addCondition('is_right_answer',true)->count());

		$this->add('dynamic_model/Controller_AutoCreator');
	}


	function updateRecordFromSession(){
		
		$session = $this->add('xavoc\dictionary\Model_TestSession');
		$session->addCondition('userid',$this->app->auth->model->id);
		foreach ($session as $sm) {
			$original_answer = trim($this->add('xavoc\dictionary\Model_Library')->load($sm['questionid'])['answer']);
			$dic = $this->add('xavoc\dictionary\Model_MockTestRecord');
			$dic->addCondition('mock_test_id',$this->id);
			$dic->addCondition('question_id',$sm['questionid']);
			$dic->tryLoadAny();
			$dic['answer'] = $sm['answer'];
			$dic['original_answer'] = $original_answer;
			if($sm['answer'] ===  $original_answer)
				$dic['is_right_answer'] = true;
			$dic->save();
		}
		$session->deleteAll();
	}
}