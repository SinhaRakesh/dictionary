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
		
		$this->addField('type')->enum(['Descriptive','Dictionary','Objective','Article']);
		$this->addField('a');
		$this->addField('b');
		$this->addField('c');
		$this->addField('d');
		$this->addField('answer');
		$this->addField('display_order')->type('Number');
		$this->addField('created_at')->type('dateTime')->defaultValue($this->app->now)->system(true);
		$this->addField('slug_url');
		$this->addField('is_word_of_day')->type('boolean')->defaultValue(0);
		$this->addField('sentance');
		$this->addField('synonyms');
		$this->addField('antonyms');

		$this->addField('word_of_day_on_date')->type('date');
		// $this->addField('created_at')->type('DatePicker');
		$this->addField('is_auto_added')->type('boolean')->defaultValue(false);

		$this->add('xepan\filestore\Field_Image','image_id')->display(['form'=>'xepan\base\Upload']);
		$this->addField('status')->enum(['Active','Inactive'])->defaultValue('Active');

		$this->hasMany('LibraryCourseAssociation','library_id');

		$this->addExpression('duration')->set(function($m,$q){
			return $q->expr('DATEDIFF([0],[1])',["'".$this->app->today."'",$m->getElement('word_of_day_on_date')]);
		});

		$this->is([
			'name|to_trim|required',
			'type|to_trim|required',
			'slug_url|to_trim|required',
		]);

		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){

		$old = $this->add('xavoc\dictionary\Model_Library');
		$old->addCondition('name',$this['name']);
		$old->addCondition('type',$this['type']);
		$old->addCondition('id','<>',$this->id);
		$old->tryLoadAny();
		if($old->loaded()){
			throw $this->exception('name already exists','ValidityCheck')
			->setField('name');
		}

		$old = $this->add('xavoc\dictionary\Model_Library');
		$old->addCondition('slug_url',$this['slug_url']);
		$old->addCondition('type',$this['type']);
		$old->addCondition('id','<>',$this->id);
		$old->tryLoadAny();
		if($old->loaded()){
			throw $this->exception('slug_url already exists','ValidityCheck')
			->setField('slug_url');
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

	function page_course_association($page){
		$f = $page->add('Form');
		$course_field = $f->addField('xepan\base\DropDown','course');
		$course_field->addClass('multiselect-full-width')
					->setAttr(['multiple'=>'multiple']);
		
	}
}