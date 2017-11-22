<?php

/**
extending model for common or extended so furthure use it
*/

namespace xavoc\dictionary;

class Model_Base_Table extends \xepan\base\Model_Table{
	function init(){
		parent::init();
		
	}

	function normalizeSlugUrl($name){
		return strtolower(str_replace("_", "-", $this->app->normalizeName($name)));
	}
	
}