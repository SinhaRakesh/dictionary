<?php

namespace xavoc\dictionary;
class View_CourseLister extends \CompleteLister{
		public $options = [];

	function init(){
		parent::init();
		
		
		$model = $this->add('xavoc\dictionary\Model_Course');
		$model->addCondition(
				$model->dsql()
						->orExpr()
						->where('parent_course_id',0)
						->where('parent_course_id',null))
				->addCondition('status','Active')
				;
		// $model->setOrder('display_sequence','desc');
		$this->setModel($model);

		$this->add('xepan\cms\Controller_Tool_Optionhelper',['options'=>$this->options,'model'=>$model]);
	}
	
	function formatRow(){

		$sub_cat = $this->add('xavoc\dictionary\Model_Course',['name'=>'model_child_'.$this->model->id]);
		$sub_cat->addCondition('parent_course_id',$this->model->id);
		$sub_cat->addCondition('status',"Active");
		// $sub_cat->setOrder('display_sequence','desc');
		if($sub_cat->count()->getOne() > 0){
			$sub_c =$this->add('xavoc\dictionary\View_CourseLister',['options'=>$this->options],'nested_course',['view\tool\/'.$this->options['template'],'category_list']);
			$sub_c->setModel($sub_cat);
			$this->current_row_html['nested_course']= $sub_c->getHTML();
		}else{
			$this->current_row_html['nested_course'] = "";
		}	

		parent::formatRow();
	}

	function defaultTemplate(){
		return ['view/tool/'.$this->options['template']];
	}

}