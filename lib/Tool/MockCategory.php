<?php

namespace xavoc\dictionary;

class Tool_MockCategory extends \xepan\cms\View_Tool{

	public $options = ['mock-test-page'=>'mock-test'];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;

		$this->app->forget('running_mock_test_id');

		$this->js(true)->_css('gallery');
		$this->app->jquery->addStaticInclude('jquery.mixitup.min');

		$category = $this->add('xavoc\dictionary\Model_MockCategory');
		$category->addCondition('status','Active');
		$category->setOrder(['display_sequence desc','id desc']);

		$mockpaper = $category->add('xavoc\dictionary\Model_MockPaper');
		// $mockpaper->addExpression('course_slug')->set($mockpaper->refSql('parent_course_id')->fieldQuery('slug_url'));
		$mockpaper->getElement('parent_course_id')->getModel()->title_field = "slug_url";

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

			$g->current_row['link'] = $this->app->url($this->options['mock-test-page'],['course'=>$g->model['parent_course'],'paper'=>$g->model['slug_url']]);

			$g->current_row_html['description'] = $g->model['description'];
		});
	}
}