<?php
namespace xavoc\dictionary;

class page_partofspeech extends \xepan\base\Page{
	public $title = "Part Of Speech Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_PartOfSpeech');
		$crud->setModel($model);

		$crud->grid->addQuickSearch(['name']);
	}
}