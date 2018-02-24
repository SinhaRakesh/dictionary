<?php

namespace xavoc\dictionary;

class Tool_MockTest extends \xepan\cms\View_Tool{

	public $options = ['login_page'=>'login/?layout=login_view'];

	function init(){
		parent::init();
		
		$this->app->stickyGET('last_no');
		$course = $this->app->stickyGET('course');
		$paper_slug = $this->app->stickyGET('paper');

		$this->session = $this->add('xavoc\dictionary\Model_TestSession');

		if($this->owner instanceof \AbstractController) return;
		
		if(!$this->app->auth->model->id){
			$this->app->redirect($this->options['login_page']);
		}else{
			$this->student = $student = $this->add('xavoc\dictionary\Model_Student');
			// todo removed
			$student->tryLoadAny();
			// if(!$student->loadLoggedIn('Student')){
			// 	$this->add('View')->addClass('alert alert-info')->set('You are not the registered user');
			// 	return;
			// }
		}

		// if model is loaded
		// then check model has finished or expired
		if($testid = $this->app->recall('running_mock_test_id',false)){

			$mt = $this->add('xavoc\dictionary\Model_MockTest');
			$mt->addExpression('paper_slug')->set($mt->refSQL('paper_id')->fieldQuery('slug_url'));
			$mt->load($testid);

			// if has finished time or current time is expired
			if((strtotime($this->app->now) > strtotime($mt['deadline_at'])) || $mt['finished_at']){	
				$this->testFinished($mt);
				return;
			}

			$this->paper_model = $paper_model = $this->add('xavoc\dictionary\Model_MockPaper');
			$paper_model->load($mt['paper_id']);

			$dates = $this->app->my_date_diff($mt['deadline_at'],$this->app->now);
			$target_date = date('Y/m/d H:i:s',strtotime("+".$dates['seconds_total']." seconds", strtotime($this->app->now)));
							
		}else{
			$this->paper_model = $paper_model = $this->add('xavoc\dictionary\Model_MockPaper');
			$paper_model->addCondition('slug_url',$paper_slug);
			$paper_model->tryLoadAny();
			// create test record
			if(isset($student) && $student->loaded()){
				$mock_test = $student->addMockTest($paper_model);
				$this->app->memorize('running_mock_test_id',$mock_test->id);
			}
			$target_date = date('Y/m/d H:i:s',strtotime("+".$paper_model['mock_test_duration']." minutes", strtotime($this->app->now)));
		}


		$this->add('View',null,'header')->setElement('h2')->set('Mock Test of '.$paper_model['name']);
		$question_set = $paper_model->getQuestions();
		$range = $question_set->count()->getOne();

		$this->total_question = $total_question = $range;

		$question_set->getElement('name')->caption('Question');
		$this->lister = $lister = $this->add('xepan\base\Grid',['paginator_class'=>'Paginator'],'question_lister',['view\grid\mock']);
		$this->lister->setModel($question_set,['name','a','b','c','d']);

		if($range){
			$this->lister->addPaginator(1,['range'=>$range,'template'=>'paginatormock']);
			$this->add('xavoc\dictionary\View_Timer',['target_date'=>"$target_date"],'timer');
			
			$this->submit_btn = $submit_btn = $this->add('Button',null,'paper_submit_button')->set('SUBMIT PAPER')->addClass('btn btn-warning btn-block mock-submit-button');
			if($submit_btn->isClicked()){
				
				$m_test = $this->add('xavoc\dictionary\Model_MockTest');
				$m_test->load($this->app->recall('running_mock_test_id'));
				$m_test->updateRecordFromSession();
				$m_test['finished_at'] = $this->app->now;
				$m_test->save();

				$this->app->redirect($this->app->url());
				$submit_btn->js()->univ()->redirect()->execute();
				// $submit_btn->js(true)->univ()->successMessage('Paper Submitted Successfully')->execute();
			}
		}

		$lister->template->tryDel('Pannel');
		$lister->addHook('formatRow',function($g){
			$form = $g->add('Form',null,'answer_form');
			$form->addField('radio','answer')->setValueList(['a'=>$g->model['a'],'b'=>$g->model['b'],'c'=>$g->model['c'],'d'=>$g->model['d']]);
			$form->addSubmit('Submit')->addClass('btn btn-primary');

			if($form->isSubmitted()){
				
				if($form['answer']){
					$this->session->addCondition('paperid',$this->paper_model->id);
					$this->session->addCondition('userid',$this->app->auth->model->id);
					$this->session->addCondition('questionid',$g->model->id);
					$this->session->tryLoadAny();

					if(!$this->session->loaded()){
						$this->session['paperid'] = $this->paper_model->id;
						$this->session['userid'] = $this->app->auth->model->id;
						$this->session['questionid'] = $g->model->id;
					}

					$this->session['answer'] = $form['answer'];
					$this->session->save();
				}

				$paginator = $this->lister->paginator;
				if($this->total_question == ($paginator->skip + $paginator->ipp)){
					$form->js(true,$this->submit_btn->js()->trigger('click'))->execute();
				}else{
					$form->js(true,$this->lister->js()->reload([$paginator->skip_var=>($paginator->skip+$paginator->ipp)]))->execute();
				}
			}

			$g->current_row_html['answer_form'] = $form->getHtml();
		});

	}

	function recursiveRender(){
		parent::recursiveRender();
	}

	function defaultTemplate(){
		return ['view\tool\mock'];
	}


	function testFinished($test_model){
		$v = $this->add('View')->addClass('alert alert-info');
		$v->setHtml('Thank You <br/>Start Time: '.$test_model['started_at'].'<br/> Finished Time: '.$test_model['finished_at'].'<br/> DeadLine Time: '.$test_model['deadline_at']);

		$restart_btn = $this->add('Button')->set('Restart Test')->addClass('btn btn-success');
		if($restart_btn->isClicked()){
			$this->app->forget('running_mock_test_id');
			$this->app->redirect($this->app->url());
		}
	}
}