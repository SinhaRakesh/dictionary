<?php

namespace xavoc\dictionary;

class Model_Descriptive extends Model_Library{

	function init(){
		parent::init();

		$this->addCondition('type','Descriptive');

		$this->getElement('created_at')->system(true);
		$this->getElement('a')->destroy();
		$this->getElement('b')->destroy();
		$this->getElement('c')->destroy();
		$this->getElement('d')->destroy();
		$this->getElement('answer')->destroy();

		// $this->getElement('description')->display(array('form'=>'xepan\base\RichText'));
	}

	function page_course_association($page){	
		$asso = $this->add('xavoc\dictionary\Model_LibraryCourseAssociation');
		$asso->addCondition('library_id',$this->id);
		$asso->getElement('course_id')
			->getModel()
			->addCondition('is_paper',true)
			->addCondition('paper_type','Descriptive')
			;

		$crud = $page->add('CRUD');
		$crud->setModel($asso);	
	}
}