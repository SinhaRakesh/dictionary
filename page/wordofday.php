<?php
namespace xavoc\dictionary;

class page_wordofday extends \xepan\base\Page{
	public $title = "Word Of Day";
	
	function init(){
		parent::init();
		

		$c = $this->add('xepan\base\CRUD',['allow_add'=>false,'allow_del'=>false]);
		$c->grid->add('View',null,'heading')->addClass('alert alert-success')->set("Current Word of Day");
		$model = $this->add('xavoc\dictionary\Model_Library')
					->addCondition('status','Active')
					;
		$model->debug();
		$model->setOrder('is_word_of_day','desc');
		$c->setModel($model,['name','type','is_word_of_day','duration']);
		$c->grid->addQuickSearch(['name']);
		$c->grid->addPaginator($ipp=50);

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