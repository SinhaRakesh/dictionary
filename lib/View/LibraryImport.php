<?php

namespace xavoc\dictionary;

class View_LibraryImport extends \View{
	public $type;

	function init(){
		parent::init();
		
		$import_btn = $this->add('Button')->set('Import CSV')
					->addClass('btn btn-primary');
		$import_btn->setIcon('fa fa fa-arrow-up');

		$import_btn->js('click')
			->univ()
			->frameURL(
					'Import CSV',
					$this->app->url('xavoc_dictionary_import')
				);
	}
}
