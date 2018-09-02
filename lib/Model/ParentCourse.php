<?php

namespace xavoc\dictionary;

class Model_ParentCourse extends \xavoc\dictionary\Model_Course{
	var $table_alias = "pCousre";
	public $title_field = "effective_name";

}