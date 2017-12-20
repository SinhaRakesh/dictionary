<?php

namespace xavoc\dictionary;

class View_Detail extends \CompleteLister{

	function init(){
		parent::init();
		
		$id = $this->app->stickyGET('dictionary_id');
		if(!$id){
			return ;
		}

		$lib = $this->add('xavoc\dictionary\Model_Library');
		$lib->tryLoad($id);
		if(!$lib->loaded()) return;

		$this->setModel($lib);
	}
}
