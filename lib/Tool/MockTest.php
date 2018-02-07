<?php

namespace xavoc\dictionary;

class Tool_MockTest extends \xepan\cms\View_Tool{

	public $options = ['login_page'=>'login'];

	function init(){
		parent::init();
		
		$this->app->stickyGET('last_no');
		$course = $this->app->stickyGET('course');
		$paper_slug = $this->app->stickyGET('paper');

		$this->session = $this->add('xavoc\dictionary\Model_TestSession');

		if($this->owner instanceof \AbstractController) return;
		
		if(!$this->app->auth->model->id){
			$this->app->redirect($this->options['login_page']);
		}

		$this->paper_model = $paper_model = $this->add('xavoc\dictionary\Model_Paper');
		// $paper_model->addCondition('slug_url',$paper_slug);
		$paper_model->tryLoadAny();
		
		$this->add('View',null,'header')->setElement('h2')->set('Mock Test ');
		$question_set = $paper_model->getQuestions();
		$range = $question_set->count()->getOne();

		$total_question = $range;

		$question_set->getElement('name')->caption('Question');
		$this->lister = $lister = $this->add('xepan\base\Grid',['paginator_class'=>'Paginator'],'question_lister',['view\grid\mock']);
		$this->lister->setModel($question_set,['name','a','b','c','d']);

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
				$form->js(true,$this->lister->js()->reload([$paginator->skip_var=>($paginator->skip+$paginator->ipp)]))->execute();
			}

			$g->current_row_html['answer_form'] = $form->getHtml();
		});

		if($range){
			$this->lister->addPaginator(1,['range'=>$range,'template'=>'paginatormock']);

			$target_date = date('Y/m/d H:i:s',strtotime("+30 seconds", strtotime($this->app->now)));
			$this->add('xavoc\dictionary\View_Timer',['target_date'=>"$target_date"],'timer');
			
			$submit_btn = $this->add('Button',null,'paper_submit_button')->set('SUBMIT PAPER')->addClass('btn btn-warning btn-block mock-submit-button');
			if($submit_btn->isClicked()){
				$submit_btn->js(true)->univ()->successMessage('Paper Submitted Successfully')->execute();
			}			
		}
	}

	function recursiveRender(){
		parent::recursiveRender();
	}

	function defaultTemplate(){
		return ['view\tool\mock'];
	}

}