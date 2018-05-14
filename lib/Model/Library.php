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

		$this->hasOne('xavoc\dictionary\Model_PartOfSpeech','part_of_speech_id');

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
		$this->addField('sentance')->type('text');
		$this->addField('synonyms');
		$this->addField('antonyms');

		$this->addField('word_of_day_on_date')->type('date');
		// $this->addField('created_at')->type('DatePicker');
		$this->addField('is_auto_added')->type('boolean')->defaultValue(false);
		$this->addField('is_popular')->type('boolean')->defaultValue(false);

		$this->add('xepan\filestore\Field_Image','image_id')->display(['form'=>'xepan\base\Upload']);
		$this->addField('status')->enum(['Active','Inactive'])->defaultValue('Active');

		$this->hasMany('xavoc\dictionary\LibraryCourseAssociation','library_id');

		$this->addExpression('duration')->set(function($m,$q){
			return $q->expr('DATEDIFF([0],[1])',["'".$this->app->today."'",$m->getElement('word_of_day_on_date')]);
		});

		$this->addExpression('course_association')->set(function($m,$q){
			$asso = $m->add('xavoc\dictionary\Model_LibraryCourseAssociation');
			$asso->addCondition('library_id',$m->getElement('id'));
			return $q->expr('[0]',[$asso->count()]);
		});

		$this->addExpression('course_str')->set(function($m,$q){
			$asso = $m->add('xavoc\dictionary\Model_LibraryCourseAssociation');
			$asso->addCondition('library_id',$m->getElement('id'));
			return $asso->_dsql()
					->del('fields')
					->field($q->expr('group_concat([0] SEPARATOR ",")',[$asso->getElement('course')]));
		});

		$this->addField('keyword')->type('text');
		$this->addField('keyword_description')->type('text');

		$this->is([
			'name|to_trim|required',
			'type|to_trim|required',
		]);

		$this->addHook('beforeSave',$this);
		// $this->add('dynamic_model/Controller_AutoCreator');
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
			throw $this->exception($this['slug_url'].' slug_url already exists','ValidityCheck')
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
		
		$asso = $this->add('xavoc\dictionary\Model_LibraryCourseAssociation');
		$asso->addCondition('library_id',$this->id);
		$crud = $page->add('CRUD');
		$crud->setModel($asso);
		
	}
}