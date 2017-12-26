<?php

namespace xavoc\dictionary;

class Model_Dictionary extends Model_Library{
	
	function init(){
		parent::init();

		$this->addCondition('type','Dictionary');

		$this->getElement('created_at')->system(true);
		$this->getElement('a')->destroy();
		$this->getElement('b')->destroy();
		$this->getElement('c')->destroy();
		$this->getElement('d')->destroy();
		$this->getElement('answer')->destroy();
		
		$this->addHook('beforeSave',$this);
	}

	function beforeSave(){

		$old = $this->add('xavoc\dictionary\Model_Dictionary');
		$old->addCondition('part_of_speech_id',$this['part_of_speech_id']);
		$old->addCondition('name',$this['name']);
		$old->addCondition('id','<>',$this->id);
		$old->tryLoadAny();
		if($old->loaded()){
			throw $this->exception('name already exists','ValidityCheck')->setField('name');
		}

	}

}