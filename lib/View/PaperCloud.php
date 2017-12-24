<?php

namespace xavoc\dictionary;
class View_PaperCloud extends \CompleteLister{
	public $options = [];
	public $type = "Objective";

	function init(){
		parent::init();
		
		
		$model = $this->add('xavoc\dictionary\Model_Paper');
		$model->addCondition('status','Active');
		$model->addCondition('paper_type',$this->type);

		$model->setOrder('display_sequence','desc');
		$model->setLimit(8);

		$this->setModel($model);
	}
	
	function formatRow(){

		if($this->model['page_name'])
			if($this->model['slug_url'])
				$this->current_row_html['url'] = $this->app->url($this->model['page_name'],['slug'=>$this['slug_url']]);
			else
				$this->current_row_html['url'] = $this->app->url($this->model['page_name']);
		else
			$this->current_row_html['url'] = "";

		parent::formatRow();
	}

	function defaultTemplate(){
		return ['view/tool/papercloud'];
	}

}