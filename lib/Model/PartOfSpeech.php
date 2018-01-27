<?php
namespace xavoc\dictionary;

class Model_PartOfSpeech extends Model_Base_Table{
	public $table = "part_of_speech";

	public $status = ['Active','Inactive'];
	public $actions = [
				'Active'=>['view','deactive','edit','delete'],
				'Inactive'=>['view','active','edit','delete']
				];

	function init(){
		parent::init();
		
		
		$this->addField('name');
		$this->addField('status')->enum(['Active','Inactive'])->defaultValue('Active');
		$this->addField('type')->defaultValue('PartOfSpeech')->system(true);

		$this->is([
			'name|to_trim|required'
		]);

		$this->addHook('beforeSave',$this);
		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){

		$old = $this->add('xavoc\dictionary\Model_PartOfSpeech');
		$old->addCondition('name',$this['name']);
		$old->addCondition('id','<>',$this->id);
		$old->tryLoadAny();
		if($old->loaded()){
			throw $this->exception('name already exists','ValidityCheck')
			->setField('name');
		}

	}

	function deactive(){
		$this['status']= "Inactive";
		$this->save();
	}

	function active(){
		$this['status'] = "Active";
		$this->save();
	}
}