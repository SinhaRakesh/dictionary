<?php
namespace xavoc\dictionary;

class Model_Favourite extends Model_Base_Table{
	public $table = "favourite";
	// public $status = ['Active','Inactive'];
	// public $actions = [
	// 			'Active'=>['view','course_association','deactive','edit','delete'],
	// 			'Inactive'=>['view','active','edit','delete']
	// ];

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Model_Contact','contact_id');
		$this->hasOne('xavoc\dictionary\Model_Library','library_id');
		$this->hasOne('xepan\base\Model_User','user_id');
		$this->addField('created_at')->defaultValue($this->app->now);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function addToBookmark($library_id){
		
		$contact = $this->add('xepan\base\Model_Contact');
		if(!$contact->loadLoggedIn()){
			return false;
		}

		$this->addCondition('contact_id',$contact->id);
		$this->addCondition('library_id',$library_id);
		$this->addCondition('user_id',$this->app->auth->model->id);
		$this->tryLoadAny();
		return $this->save();
	}
}