<?php

namespace xavoc\dictionary;

class Tool_LibraryDetail extends \xepan\cms\View_Tool{
	public $options = [
			'type'=>'Article'
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		$m = $this->add('xavoc\dictionary\Model_Article');
		$m->addCondition('slug_url',$_GET['slug']);
		$m->tryLoadAny();

		$this->setModel($m);
		$this->template->trySetHTML('description_detail',$m['description']);
		if(!strlen($m['image'])){
			$this->template->trySet('image_url',"websites/".$this->app->current_website_name."/www/img/latest_news/1.jpg");
		}else
			$this->template->trySet('image_url',$m['image']);

		$pre_article = $this->add('xavoc\dictionary\Model_Article');
		$pre_article->addCondition('id','<',$m->id);
		$pre_article->tryLoadAny();
		$pre_article->setLimit(1);
		if($pre_article->loaded()){
			$this->add('View',null,'previous')->addClass('custom_hover btn btn-info')->setElement('a')->setAttr('href',$this->app->url(null,['slug'=>$pre_article['slug_url']]))->set($pre_article['name']);
		}

		$next_article = $this->add('xavoc\dictionary\Model_Article');
		$next_article->addCondition('status','Active');
		$next_article->addCondition('id','>',$m->id);
		// $next_article->addCondition('id','<>',[$m->id,$pre_article->id]);
		$next_article->setLimit(1);
		$next_article->tryLoadAny();
		if($next_article->loaded())
			$this->add('View',null,'next')->addClass('custom_hover btn btn-info')->setElement('a')->setAttr('href',$this->app->url(null,['slug'=>$next_article['slug_url']]))->set($next_article['name']);

	}

	function defaultTemplate(){
		return ['view/tool/librarydetail'];
	}
}