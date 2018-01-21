<?php

namespace xavoc\dictionary;

class Tool_MockTest extends \xepan\cms\View_Tool{

	public $options = [];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		$this->add('View')->setElement('h2')->set('Mock Test');

		$model = $this->add('xavoc\dictionary\Model_Library');
		$grid = $this->add('Grid');
		// $model->setLimit(1);
		$grid->setModel($model,['name']);
		$grid->addPaginator(1,['range'=>0]);
		
	}
}