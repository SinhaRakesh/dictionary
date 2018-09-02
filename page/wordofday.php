<?php
namespace xavoc\dictionary;

class page_wordofday extends \xepan\base\Page{
	public $title = "Word Of Day";
	
	function init(){
		parent::init();
		

		$tab = $this->add('Tabs');
		$t1 = $tab->addTab('Current & History');
		$t2 = $tab->addTab('Upcoming');

		$c = $t1->add('xepan\base\CRUD',['allow_add'=>false,'allow_del'=>false]);
		$model = $this->add('xavoc\dictionary\Model_Dictionary')
					->addCondition('status','Active')
					;
		$model->addCondition('duration','>=',0);
		$model->addCondition('is_word_of_day',true);
		$model->setOrder('is_word_of_day','desc');

		$c->setModel($model,['part_of_speech_id','name','speech','description','slug_url','sentance','synonyms','antonyms','is_word_of_day','duration','word_of_day_on_date','image_id'],['name','type','is_word_of_day','duration','word_of_day_on_date']);
		$c->grid->addQuickSearch(['name']);
		$c->grid->addPaginator($ipp=10);

		$c2 = $t2->add('xepan\base\CRUD',['allow_add'=>false,'allow_del'=>false]);
		$model = $this->add('xavoc\dictionary\Model_Dictionary')
					->addCondition('status','Active')
					;
		$model->addCondition('is_word_of_day',true);
		$model->addExpression('duration2')->set(function($m,$q){
				return $q->expr('IFNULL([0],0)',[$m->getElement('duration')]);
			});
		$model->addCondition('is_word_of_day',1);
		$model->addCondition([['duration2',0],['duration2','>',360]]);
		$model->setOrder('duration2','asc');

		$c2->setModel($model,['part_of_speech_id','name','speech','description','slug_url','sentance','synonyms','antonyms','is_word_of_day','duration','word_of_day_on_date','image_id'],['name','type','is_word_of_day','duration','word_of_day_on_date']);
		$c2->grid->addQuickSearch(['name']);
		$c2->grid->addPaginator($ipp=10);

		// $col = $this->add('Columns');
		// $col1 = $col->addColumn('4');
		// $col2 = $col->addColumn('4');
		// $col3 = $col->addColumn('4');

		// $col1->add('View')->setElement('h3')->set('Objective');
		// $crud = $col1->add('xepan\hr\CRUD',['allow_del'=>false,'allow_add'=>false]);
		// $model = $this->add('xavoc\dictionary\Model_Objective')
		// 		->addCondition('status','Active')
		// 		;
		// $crud->setModel($model,['name']);
		// $crud->grid->removeAttachment();
		// $crud->grid->addPaginator($ipp=30);

		// $col2->add('View')->setElement('h3')->set('Descriptive');
		// $crud = $col2->add('xepan\hr\CRUD',['allow_del'=>false,'allow_add'=>false]);
		// $model = $this->add('xavoc\dictionary\Model_Descriptive')
		// 		->addCondition('status','Active')
		// 		;
		// $crud->setModel($model,['name']);
		// $crud->grid->removeAttachment();
		// $crud->grid->addPaginator($ipp=30);

		// $col3->add('View')->setElement('h3')->set('Dictionary');
		// $crud = $col3->add('xepan\hr\CRUD',['allow_del'=>false,'allow_add'=>false]);
		// $model = $this->add('xavoc\dictionary\Model_Dictionary')
		// 		->addCondition('status','Active')
		// 		;
		// $crud->setModel($model,['name']);
		// $crud->grid->removeAttachment();
		// $crud->grid->addPaginator($ipp=30);
	}
}