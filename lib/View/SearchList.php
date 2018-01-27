<?php

namespace xavoc\dictionary;

class View_SearchList extends \CompleteLister{

	public $term;
	public $type;

	function init(){
		parent::init();

	}
	
	function formatRow(){

		if($this->type == "paper"){
			$this->template->trySet('heading','Paper');
			$this->current_row_html['url'] = $this->app->url('paper',['slug'=>$this->model['slug_url']])->absolute();
			$description = $this->model['description'];
			$this->current_row_html['description'] = $description;
		}

		if($this->type == "article"){
			$this->template->trySet('heading','Article');
			$this->current_row_html['url'] = $this->app->url('article-detail',['slug'=>$this->model['slug_url']])->absolute();
			$this->current_row_html['description'] = $this->model['keyword']."<br/>".$this->model['keyword_description'];
		}

		if($this->type == "objective"){
			$this->template->trySet('heading','Objective');
			$temp = explode(',',$this->model['paper_str'])[0];
			$this->current_row_html['url'] = $this->app->url('paper',['slug'=>$temp])->absolute();
			$this->current_row_html['description'] = $this->model['description'];
		}
		
		parent::formatRow();
	}

	function defaultTemplate(){
		return ['view/tool/searchpaperlist'];
	}

}