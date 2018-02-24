<?php

namespace xavoc\dictionary;

class Model_Testimonial extends Model_Base_Table{
	public $table = "dictionary_testimonial";
	public $status = ['Pending','Approved','Rejected'];
	public $actions = [
			'Pending'=>['view','approved','reject','edit','delete'],
			'Approved'=>['view','edit','delete'],
			'Rejected'=>['view','approved','edit','delete']
		];

	public $acl_type = "dictionary_testimonial";

	function init(){
		parent::init();

		$this->hasOne('xavoc\dictionary\Model_Student','student_id')->sortable(true);
		$this->hasOne('xepan\base\Model_User','created_by_id')->defaultValue($this->app->auth->model->id);

		$this->addField('name')->defaultValue('testimonial');
		$this->addField('description')->type('text');
		$this->addField('status')->enum(['Pending','Approved','Rejected'])->defaultValue('Pending');
		$this->addField('created_at')->type('datetime')->defaultValue($this->app->now)->sortable(true);

		$this->add('dynamic_model/Controller_AutoCreator');

		$this->is([
			'name|to_trim|required',
			'description|to_trim|required'
		]);
	}

	function approved(){
		$this['status']='Approved';
		$this->save();
	}

	function reject(){
		$this['status'] = "Rejected";
		$this->save();
	}
}