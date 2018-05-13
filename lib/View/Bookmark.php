<?php

namespace xavoc\dictionary;

class View_Bookmark extends \View{
	public $library_id;
	public $check_login = true;
	function init(){
		parent::init();

		if($this->check_login && !$this->app->auth->model->loaded()){
			return;
		}
		if(!$this->library_id){
			$this->add('View')->set('Library not define')->addClass('alert alert-warning');
			return;
		}

		$form = $this->add('Form',['name'=>'book_mark'.$this->library_id]);
		$form->addSubmit('Bookmark Now');
		if($form->isSubmitted()){
			$fav = $this->add('xavoc\dictionary\Model_Favourite');
			if($fav->addToBookmark($this->library_id)){
				$form->js()->univ()->successMessage('Book marked successfully')->execute();
			}

			$form->js()->univ()->errorMessage('user record is not loaded')->execute();
		}
	}
}
