<?php

namespace xavoc\dictionary;

class Model_MockCategory extends Model_Course{

	function init(){
		parent::init();

		$this->addCondition('is_paper',false);
		$this->addCondition('is_mock_paper',false);
		$this->addCondition('is_mock_category',true);

		$this->getElement('created_at')->system(true);
		$this->getElement('display_in_menu_bar')->system(true);
		$this->getElement('page_name')->defaultValue('mock-test')->system(true);
	}
}