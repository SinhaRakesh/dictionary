<?php

namespace xavoc\dictionary;
class View_CourseLister extends \CompleteLister{
		public $options = [];

	function init(){
		parent::init();
		
		
		$model = $this->add('xavoc\dictionary\Model_Course');
		$model->addCondition([['parent_course_id',0],['parent_course_id',NULL]])
			->addCondition('status','Active')
			->addCondition('display_in_menu_bar',true)
			->addCondition([['is_paper',false],['is_paper',null]])
			;
		$model->setOrder('display_sequence','desc');
		
		$this->setModel($model);		
		$this->add('xepan\cms\Controller_Tool_Optionhelper',['options'=>$this->options,'model'=>$model]);
	}
	
	function formatRow(){

		$sub_cat = $this->add('xavoc\dictionary\Model_Course',['name'=>'model_child_'.$this->model->id]);
		$sub_cat->addCondition('parent_course_id',$this->model->id);
		$sub_cat->addCondition('status',"Active");
		$sub_cat->addCondition('display_in_menu_bar',true);
		$sub_cat->addCondition([['is_paper',false],['is_paper',null]]);
		$sub_cat->setOrder('display_sequence','desc');
		
		if($sub_cat->count()->getOne() > 0){
			$sub_c = $this->add('xavoc\dictionary\View_CourseLister',['options'=>$this->options],'nested_course',['view\tool\/'.$this->options['template'],'category_list']);
			$sub_c->setModel($sub_cat);
			// $sub_c->template->set('submenu_class','dropdown-menu');
			// $this->current_row_html['submenu_class'] = "dropdown-submenu";
			$this->current_row_html['nested_course'] = $sub_c->getHTML();
		}else{
			$this->current_row_html['nested_course'] = "";
		}

		if($this->model['page_name'])
			if($this->model['slug_url'])
				$this->current_row_html['url'] = $this->app->url($this->model['page_name'],['slug'=>$this['slug_url']]);
			else
				$this->current_row_html['url'] = $this->app->url($this->model['page_name']);
		else
			$this->current_row_html['url'] = "";

		parent::formatRow();
	}

	function defaultTemplate(){
		return ['view/tool/'.$this->options['template']];
	}

}