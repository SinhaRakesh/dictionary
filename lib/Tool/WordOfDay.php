<?php

namespace xavoc\dictionary;

class Tool_WordOfDay extends \xepan\cms\View_Tool{
	public $options = [
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		$slug = $_GET['slug'];

		$model = $this->add('xavoc\dictionary\Model_WordOfDay');
		if($slug)
			$model->addCondition('slug_url',$slug);
		$model->setLimit(1);
		$model->tryLoadAny();
		
		if(!$model->loaded()){
			$this->add('View')->set('word of day not found');
		}

		$this->setModel($model);

		if(!$slug)
			$this->template->set('url',$this->app->url('word-of-day',['slug'=>$model['slug_url']]));
	}

	function defaultTemplate(){
		return ['view/tool/wordofday'];
	}
}