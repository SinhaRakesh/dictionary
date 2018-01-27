<?php

namespace xavoc\dictionary;

class Tool_WordList extends \xepan\cms\View_Tool{
	public $options = [
			'result_page'=>'englishword'
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		$this->addClass('wordlist');
		$m = $this->add('xavoc\dictionary\Model_Dictionary');
		$m->addCondition('status','Active');
		$m->addCondition('name','LIKE',strtolower($_GET['letter'])."%");
		
		$l = $this->add('CompleteLister',null,null,['view/tool/wordlist']);
		$l->setModel($m);
		$l->addHook('formatRow',function($g){
			$g->current_row_html['url'] = $this->app->url($this->options['result_page'],['word'=>trim($g->model['name'])]);
		});

		if($m->count()->getOne() > 45){
			$paginator = $l->add('Paginator',['ipp'=>45]);
			$paginator->setRowsPerPage(45);
		}else{
			$l->template->tryDel('paginator_wrapper');
		}

	}
}