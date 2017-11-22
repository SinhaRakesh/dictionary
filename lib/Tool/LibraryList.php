<?php

namespace xavoc\dictionary;

class Tool_LibraryList extends \xepan\cms\View_Tool{
	public $options = [
			'type'=>'Descriptive',
			'order'=>'desc',
			'paginator'=>'8',
			'detailpage'=>'detail'
		];

	function init(){
		parent::init();
		if($this->owner instanceof \AbstractController) return;

		if(!$this->options['type']) throw new \Exception("must define the type of listing");
		
		$model = $this->add('xavoc\dictionary\Model_'.$this->options['type']);
		$model->addCondition('status','Active');
		$model->setOrder('id',$this->options['order']?:'desc');

		$this->complete_lister = $cl = $this->add('CompleteLister',null,null,['view/tool/'.strtolower($this->options['type'])."list"]);
		$cl->setModel($model);
		//not record found
		if(!$model->count()->getOne())
			$cl->template->set('not_found_message','No Record Found');
		else
			$cl->template->del('not_found');

		if($this->options['paginator']){
			$paginator = $cl->add('Paginator',['ipp'=>$this->options['paginator']]);
			$paginator->setRowsPerPage($this->options['paginator']);
		}

		$cl->add('xepan\cms\Controller_Tool_Optionhelper',['options'=>$this->options,'model'=>$model]);
	}


	function addToolCondition_row_detailpage($value,$l){
		$url = $this->api->url($this->options['detailpage']."/".$l->model['slug_url']);
		$url->arguments = [];
		$l->current_row['slug_url'] = $url;
	}
}