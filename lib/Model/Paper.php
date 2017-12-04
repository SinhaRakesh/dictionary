<?php

namespace xavoc\dictionary;

class Model_Paper extends Model_Course{

	function init(){
		parent::init();

		$this->addCondition('is_paper',true);
		$this->addCondition('display_in_menu_bar',true);
		$this->addCondition('page_name','paper');

		$this->is([
			'paper_type|to_trim|required'
		]);
	}
}