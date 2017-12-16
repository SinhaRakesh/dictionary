<?php

namespace xavoc\dictionary;

class Tool_WordOfDay extends \xepan\cms\View_Tool{
	public $options = [
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		$slug = $_GET['slug'];


		$model = $this->add('xavoc\dictionary\Model_Library');
		$model->addCondition('is_word_of_day',1);
		$model->addCondition('duration',0);
		$model->tryLoadAny();
		if(!$model->loaded()){
			$model = $this->add('xavoc\dictionary\Model_Library');
			$model->addExpression('duration2')->set(function($m,$q){
				return $q->expr('IFNULL([0],0)',[$m->getElement('duration')]);
			});
			$model->addCondition([['duration2',0],['duration2','>','360']]);
			$model->setOrder('duration2','asc');
		}

		if($slug)
			$model->addCondition('slug_url',$slug);

		$model->setLimit(1);
		$model->tryLoadAny();
		
		if(!$model->loaded()){
			$this->add('View')->set('word of day not found');
		}else{
			if(!$model['word_of_day_on_date']){

				$lib = $this->add('xavoc\dictionary\Model_Library');
				$lib->addCondition('is_word_of_day',true)
					->tryLoadAny();
				if($lib->loaded()){
					$lib['is_word_of_day'] = false;
					$lib->save();
				}
				$model['word_of_day_on_date'] = $this->app->today;
				$model['is_word_of_day'] = true;
				$model->save();
			}
		}

		$this->setModel($model);

		if(!$slug)
			$this->template->set('url',$this->app->url('word-of-day',['slug'=>$model['slug_url']]));
	}

	function defaultTemplate(){
		return ['view/tool/wordofday'];
	}
}