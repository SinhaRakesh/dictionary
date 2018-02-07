<?php

namespace xavoc\dictionary;

class Tool_MockCategory extends \xepan\cms\View_Tool{

	public $options = [];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;

		$this->js(true)->_css('gallery');
		$this->app->jquery->addStaticInclude('jquery.mixitup.min');

		$category = $this->add('xavoc\dictionary\Model_MockCategory');
		$category->addCondition('status','Active');
		$category->setOrder(['display_sequence desc','id desc']);

		$mockpaper = $category->add('xavoc\dictionary\Model_MockPaper');
		$mockpaper->addCondition('status','Active');
		$mockpaper->setOrder(['display_sequence desc','id desc']);

		$v = $this->add('View',null,null,['view\tool\mockcategory']);
		$lister = $v->add('Lister',null,'category',['view\tool\mockcategory','category_list']);
		$lister->setModel($category);

		$img_lister = $v->add('Lister',null,'item_list',['view\tool\mockcategory','item']);
		$img_lister->setModel($mockpaper);

		$img_lister->addHook('formatRow',function($g){
			if($g->model['image_id']){
				$g->current_row['image_path'] = $g->model['image'];
			}else
				$g->current_row['image_path'] = './websites/'.$this->app->current_website_name."/defaultmockcategory.png";

		// 	if($this->options['show_link']){
		// 		if($g->model['custom_link']){
		// 			$g->current_row['link'] = $g->model['custom_link'];
		// 		}else{
		// 			$g->current_row['link'] = $this->app->url($this->options['detail_page']);
		// 		}
		// 	}else
		// 		$g->current_row['link_wrapper'] = "";

		});
	}
}