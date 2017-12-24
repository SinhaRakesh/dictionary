<?php

namespace xavoc\dictionary;

class Model_Course extends Model_Base_Table{
	public $table = "course";
	public $status = ['Active','Inactive'];
	public $actions = [
			'Active'=>['view','associate','deactive','edit','delete'],
			'Inactive'=>['view','associate','active','edit','delete']
		];
	public $acl_type = "dictionary_course";

	function init(){
		parent::init();

		$this->hasOne('xavoc\dictionary\ParentCourse','parent_course_id')->sortable(true);

		$this->addField('name');
		$this->addField('status')->enum(['Active','Inactive'])->defaultValue('Active');
		$this->addField('page_name')->caption('course redirect to page');
		$this->addField('display_sequence')->type('number');
		$this->addField('slug_url');
		$this->addField('display_in_menu_bar')->type('boolean');

		$this->addField('is_paper')->type('boolean')->defaultValue(0);
		$this->addField('paper_type')->enum(['Descriptive','Objective']);

		$this->addField('description')->type('text');
		$this->add('xepan\filestore\Field_Image','image_id')->display(['form'=>'xepan\base\Upload']);
		$this->addField('keyword')->type('text');
		$this->addField('created_at')->type('datetime')->defaultValue($this->app->now);
		$this->hasMany('LibraryCourseAssociation','course_id');
		
		$this->add('dynamic_model/Controller_AutoCreator');

		$this->addHook('beforeSave',$this);
		$this->is([
			'name|to_trim|required'
		]);
	}


	function beforeSave(){

		if($this['parent_course_id'] && !$this['slug_url']){
			throw $this->exception('slug url must not be empty','ValidityCheck')->setField('slug_url');
		}

		if($this['slug_url']){
			$this['slug_url'] = $this->app->normalizeSlugUrl($this['slug_url']);

			$old = $this->add('xavoc\dictionary\Model_Course');
			$old->addCondition('slug_url',$this['slug_url']);
			$old->addCondition('id','<>',$this->id);
			$old->tryLoadAny();
			if($old->loaded()){
				throw $this->exception('slug_url already exists, '.$this['slug_url'],'ValidityCheck')
				->setField('slug_url');
			}
		}
	}

	function page_associate($page){

		$lca = $page->add('xavoc\dictionary\Model_LibraryCourseAssociation');
		$lca->addCondition('course_id',$this->id);
		$crud = $page->add('CRUD');
		$crud->setModel($lca);

		if($crud->isEditing()){
			$form = $crud->form;
			$form->getElement('library_id')->getModel()->addCondition('type',$this['paper_type']);
		}
		$crud->grid->addPaginator($ipp=30);
		
	}

}