<?php

namespace xavoc\dictionary;

class Tool_LibraryList extends \xepan\cms\View_Tool{
	public $options = [
			'type'=>'Descriptive',
			'order'=>'desc',
			'paginator'=>'8',
			'detailpage'=>'detail',
			'limit'=>0,
			'heading'=>'Library',
			'description'=>''
		];
	public $s_no = 1;
	function init(){
		parent::init();
		if($this->owner instanceof \AbstractController) return;

		if(!$this->options['type']){
			$this->options['type'] = "Descriptive";
		}
		
		$slug = $this->app->stickyGET('slug');
		if($slug){
			$paper = $this->add('xavoc\dictionary\Model_Paper');
			$paper->addCondition('slug_url',$slug);
			$paper->tryLoadAny();
			if($paper->loaded()){
				$this->options['type'] = $paper['paper_type'];
			}
		}

		$model = $this->add('xavoc\dictionary\Model_'.$this->options['type']);
		$model->addCondition('status','Active');
		
		$model->setOrder('id',$this->options['order']?:'desc');
		if($this->options['limit'])
			$model->setLimit($this->options['limit']);
		
		$this->complete_lister = $cl = $this->add('CompleteLister',null,null,['view/tool/'.strtolower($this->options['type'])."list"]);
		$cl->setModel($model);
		
		if(!$model->count()->getOne())
			$cl->template->set('not_found_message','No Record Found');
		else
			$cl->template->del('not_found');

		if($this->options['paginator']){
			$paginator = $cl->add('Paginator',['ipp'=>$this->options['paginator']]);
			$paginator->setRowsPerPage($this->options['paginator']);
		}
		
		$cl->addHook('formatRow',function($g){
			$url = $this->api->url($this->options['detailpage']."/".$g->model['slug_url']);
			$url->arguments = [];
			$g->current_row['slug_url'] = $url;
			
			if(!strlen($g->model['image'])){
				$g->current_row['image'] = "websites/pathshala/www/img/latest_news/1.jpg";
			}else
				$g->current_row['image'] = $g->model['image'];
			
			$g->current_row_html['description'] = $g->model['description'];
			$g->current_row['s_no'] = $this->s_no++;
		});
		
		$cl->template->trySet('heading',$this->options['heading']);
		$cl->template->trySet('heading_description',$this->options['description']);

		// $cl->add('xepan\cms\Controller_Tool_Optionhelper',['options'=>$this->options,'model'=>$model]);
	}


	function addToolCondition_row_detailpage($value,$l){
		$url = $this->api->url($this->options['detailpage']."/".$l->model['slug_url']);
		$url->arguments = [];
		$l->current_row['slug_url'] = $url;
	}

}