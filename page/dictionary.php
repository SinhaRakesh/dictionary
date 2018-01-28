<?php
namespace xavoc\dictionary;

class page_dictionary extends \xepan\base\Page{
	public $title = "Dictionary Management";
	
	function init(){
		parent::init();

		$form = $this->add('Form');
		$form->add('xepan\base\Controller_FLC')
			->showLables(true)
			->addContentSpot()
			->makePanelsCoppalsible(true)
			->layout([
					'name'=>'Add Dictionary~c1~3',
					'slug_url'=>'c2~3',
					'part_of_speech_id'=>'c3~3',
					'status'=>'c4~3',
					'synonyms'=>'c5~12',
					'antonyms'=>'c6~12',
					'description'=>'c11~6',
					'sentance'=>'c12~6',
					'is_word_of_day~'=>'c13~6',
					'is_popular~'=>'c14~6',
					'FormButtons~'=>'c15~6'
				]);
		$form->addSubmit('Add')->addClass('btn btn-primary');

		$crud = $this->add('xepan\hr\CRUD');
		$model = $this->add('xavoc\dictionary\Model_Dictionary');
		$model->setOrder('id','desc');
		$crud->setModel($model,['part_of_speech_id','name','speech','description','slug_url','sentance','synonyms','antonyms','is_word_of_day','is_popular'],['name','part_of_speech','description','slug_url','status','action']);

		$crud->grid->addQuickSearch(['name']);
		$crud->grid->removeAttachment();

		$crud->grid->addPaginator($ipp=25);

		$form->setModel($model,['part_of_speech_id','name','status','description','slug_url','sentance','synonyms','antonyms','is_word_of_day','is_popular']);
		if($form->issubmitted()){
			$form->update();
			$form->js(null,[$form->js()->reload(),$crud->js()->reload()])->univ()->successMessage('Add Successfully')->execute();
		}
	}
}