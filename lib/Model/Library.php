<?php

class Model_Library extends Model_Base_Table{
	public $table = "library";

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
		$this->addField('is_active')->type('boolean');
		$this->addField('is_word_of_day')->type('boolean');

		$this->hasMany('LibraryCourseAssociation','library_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}