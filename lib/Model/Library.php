<?php
namespace xavoc\dictionary;

class Model_Library extends Model_Base_Table{
	public $table = "library";

	public $status = ['Active','Inactive'];
	public $actions = [
				'Active'=>['view','course_association','deactive','edit','delete'],
				'Inactive'=>['view','active','edit','delete']
				];

	function init(){
		parent::init();

		$this->addField('name');
		$this->addField('description')->type('text');
		$this->addField('type')->enum(['Descriptive','Dictionary','Objective']);
		$this->addField('a');
		$this->addField('b');
		$this->addField('c');
		$this->addField('d');
		$this->addField('answer');
		$this->addField('display_order')->type('Number');
		$this->addField('created_at')->type('dateTime')->defaultValue($this->app->now)->system(true);
		$this->addField('slug_url')->system(true);
		$this->addField('is_word_of_day')->type('boolean');

		$this->add('xepan\filestore\Field_Image','image_id')->display(['form'=>'xepan\base\Upload']);
		$this->addField('status')->enum(['Active','Inactive'])->defaultValue('Active');

		$this->hasMany('LibraryCourseAssociation','library_id');

		$this->is([
			'name|to_trim|required',
			'type|to_trim|required',
		]);

		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$this['slug_url'] = $this->normalizeSlugUrl($this['name']);
	}

	function deactive(){
		$this['status']= "Inactive";
		$this->save();
	}

	function active(){
		$this['status'] = "Active";
		$this->save();
	}

	function page_course_association($page){
		$f = $page->add('Form');
		$course_field = $f->addField('xepan\base\DropDown','course');
		$course_field->addClass('multiselect-full-width')
					->setAttr(['multiple'=>'multiple']);
		
	}
}