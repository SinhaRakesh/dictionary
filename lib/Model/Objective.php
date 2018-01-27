<?php
namespace xavoc\dictionary;

class Model_Objective extends \xavoc\dictionary\Model_Library{

	function init(){
		parent::init();

		$this->addCondition('type','Objective');

		$this->getElement('description')->display(array('form'=>'xepan\base\RichText'));
		

		$this->addHook('beforeSave',$this);
	}

	function beforeSave(){
		if(!$this['slug_url']) 
			$this['slug_url'] = $this->app->normalizeName($this['name']);
	}

}