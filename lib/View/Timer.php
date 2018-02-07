<?php

namespace xavoc\dictionary;

class View_Timer extends \View{
	public $target_date;

	function init(){
		parent::init();
		
		$this->app->jui->addStaticInclude('jquery.countdown');
		$this->app->jui->addStaticInclude('countdown');
		
		$this->template->trySet('target_date',$this->target_date);
	}

	function defaultTemplate(){
		return ['view\timer'];
	}
}
