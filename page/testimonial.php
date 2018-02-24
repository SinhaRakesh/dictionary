<?php

namespace xavoc\dictionary;

class page_testimonial extends \Page{
	public $title = "testimonial";
	
	function page_index(){
		// parent::init();

		$tab = $this->add('Tabs');
		$tab->addTabUrl('./pending','Pending');
		$tab->addTabUrl('./approved','Approved');
		$tab->addTabUrl('./rejected','Rejected');

	}

	function page_pending(){
		$pm = $this->add('xavoc\dictionary\Model_Testimonial');
		$pm->addCondition('status','Pending');
		$pm->setOrder('id','desc');
		$crud = $this->add('xepan\hr\CRUD');
		$crud->setModel($pm,['student','description','created_at']);
		$crud->grid->addPaginator($ipp=10);
		$crud->grid->removeAttachment();
	}

	function page_approved(){
		$pm = $this->add('xavoc\dictionary\Model_Testimonial');
		$pm->addCondition('status','Approved');
		$pm->setOrder('id','desc');
		$crud = $this->add('xepan\hr\CRUD');
		$crud->setModel($pm,['student','description','created_at']);
		$crud->grid->addPaginator($ipp=10);
		$crud->grid->removeAttachment();
	}

	function page_rejected(){
		$pm = $this->add('xavoc\dictionary\Model_Testimonial');
		$pm->addCondition('status','Rejected');
		$pm->setOrder('id','desc');
		$crud = $this->add('xepan\hr\CRUD',['allow_add'=>false]);
		$crud->setModel($pm,['student','description','created_at']);
		$crud->grid->addPaginator($ipp=10);
		$crud->grid->removeAttachment();
	}
}