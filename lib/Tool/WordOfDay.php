<?php

namespace xavoc\dictionary;

class Tool_WordOfDay extends \xepan\cms\View_Tool{
	public $options = [
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		

		$model = $this->add('xavoc\dictionary\Model_WordOfDay');
		
		$this->complete_lister = $cl = $this->add('CompleteLister',null,null,['view/tool/wordofday']);
		$cl->setModel($model);
		//not record found
		if(!$model->count()->getOne())
			$cl->template->set('not_found_message','No Record Found');
		else
			$cl->template->del('not_found');		
		$cl->add('xepan\cms\Controller_Tool_Optionhelper',['options'=>$this->options,'model'=>$model]);

	}
}