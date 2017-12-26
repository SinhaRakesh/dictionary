<?php
namespace xavoc\dictionary;

class page_dictionary extends \xepan\base\Page{
	public $title = "Dictionary Management";
	
	function init(){
		parent::init();

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Dictionary');
		$model->setOrder('name','asc');
		$crud->setModel($model,['part_of_speech_id','name','speech','description','slug_url','sentance','synonyms','antonyms','is_word_of_day'],['name','part_of_speech','description','slug_url','status','action']);


		$crud->grid->addQuickSearch(['name']);
		$crud->grid->removeAttachment();

		$crud->grid->addPaginator($ipp=25);
	}
}