<?php

namespace xavoc\dictionary;

class Tool_VideoSection extends \xepan\cms\View_Tool{

	public $options = [];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		$course = $this->add('xavoc\dictionary\Model_Course');
		$course->addCondition('is_paper',false);
		$this->template->trySet('course_count',$course->count()->getOne());


		$student = $this->add('xavoc\dictionary\Model_Student');
		$this->template->trySet('student_count',(($student->count()->getOne()?:35)+1000));

		// paper_count
		$paper = $this->add('xavoc\dictionary\Model_Course');
		$paper->addCondition('is_paper',true);
		$this->template->trySet('paper_count',$paper->count()->getOne());

		// mock_test_count
		$this->template->trySet('mock_test_count',$paper->count()->getOne());

	}

	function defaultTemplate(){
		return ['view/tool/videosection'];
	}

}