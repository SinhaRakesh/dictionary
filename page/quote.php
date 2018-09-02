<?php

namespace xavoc\dictionary;

class page_quote extends \Page{
	public $title = "quote";
	
	function init(){
		parent::init();

		$quote = $this->add('xavoc\dictionary\Model_Quote');
		$quote->getElement('description')->display(array('form'=>'xepan\base\RichText'));

		$quote->setOrder('name','asc');

		$crud = $this->add('xepan\base\CRUD');
		$crud->setModel($quote,['name','description','status']);

		$crud->grid->addPaginator(25);
		$crud->grid->addQuickSearch(['name','description']);
	}
}