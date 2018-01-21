<?php

namespace xavoc\dictionary;

class Tool_LibraryList extends \xepan\cms\View_Tool{
	public $options = [
			'type'=>'Descriptive',
			'paper_type'=>'Descriptive',
			'order'=>'desc',
			'show_paginator'=>true,
			'paginator'=>'8',
			'detailpage'=>'detail',
			'limit'=>0,
			'heading'=>'Library',
			'description'=>'',
			'customlayout'=>'',
			'random_record'=>false,
			'condition_field'=>null,
			'condition_check_value'=>true,
		];
	public $s_no = 1;
	public $add_paper_cloud = false;
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
				$this->options['heading'] = $paper['name'];
				$this->add_paper_cloud = true;
			}

		}

		$model = $this->add('xavoc\dictionary\Model_'.$this->options['type']);
		$model->addCondition('status','Active');
		if($this->options['paper_type'] && $this->options['type'] == "Paper"){
			$model->addCondition('paper_type',$this->options['paper_type']);
		}

		if($this->options['condition_field'] and $this->options['condition_check_value']){
			$model->addCondition($this->options['condition_field'],$this->options['condition_check_value']);
		}

		if($this->options['random_record']){
			// todo
		}else
			$model->setOrder('id',$this->options['order']?:'desc');

		if($this->options['limit'])
			$model->setLimit($this->options['limit']);
		
		$template = $this->options['type'];
		if($this->options['customlayout']) 
			$template = $this->options['customlayout'];

		if($this->options['type'] == "Descriptive" && $this->add_paper_cloud)
			$template .= "detail";
		else
			$template .= "list";
		
		$this->complete_lister = $cl = $this->add('CompleteLister',null,null,['view/tool/'.strtolower($template)]);
		$cl->setModel($model);
		
		
		if(!$model->count()->getOne())
			$cl->template->set('not_found_message','No Record Found');
		else
			$cl->template->del('not_found');

		if($this->options['paginator']){
			$paginator = $cl->add('Paginator',['ipp'=>$this->options['paginator']]);
			$paginator->setRowsPerPage($this->options['paginator']);
		}else{
			$cl->template->tryDel('paginator_wrapper');
		}
		
		$cl->addHook('formatRow',function($g){
			$url = $this->api->url($this->options['detailpage'],['slug'=>$g->model['slug_url']]);
						
			$g->current_row['slug_url'] = $url;
			
			if(!strlen($g->model['image'])){
				$g->current_row['image'] = "websites/".$this->app->current_website_name."/www/img/latest_news/1.jpg";
			}else
				$g->current_row['image'] = $g->model['image'];
			
			$g->current_row_html['description'] = $g->model['description'];
			$g->current_row['s_no'] = $this->s_no++;
		});
		
		$cl->template->trySet('heading',$this->options['heading']);
		$hdetail = $this->options['description'];

		if($model['type'] == "Objective"){
			$hdetail = '<div class="row description-detail">';
			$hdetail .= '<div class="col-md-3">';
			$hdetail .= '</div>';
			$hdetail .= '<div class="col-md-6 heading-middle">';
			$hdetail .= '<p>प्रश्नो के उत्तर के लिए उत्तर पर क्लिक करें </p>';
			$hdetail .= '</div>';
			$hdetail .= '<div class="col-md-3 heading-left">';
			$hdetail .= 'published on: '.$model['created_at'];
			$hdetail .= '</div>';
			$hdetail .= '</div>';

			$cl->template->trySetHtml('heading_description',$hdetail);

		}else{
			$cl->template->trySet('heading_description',$this->options['description']);
		}
		// $cl->add('xepan\cms\Controller_Tool_Optionhelper',['options'=>$this->options,'model'=>$model]);
		if($this->add_paper_cloud){
			$type = 'Objective';
			if($paper['paper_type'] == "Objective")
				$type = "Descriptive";
			
			$cl->add('xavoc\dictionary\View_PaperCloud',['type'=>$type],'paper_cloud');
		}
	}


	function addToolCondition_row_detailpage($value,$l){
		$url = $this->api->url($this->options['detailpage']."/".$l->model['slug_url']);
		$url->arguments = [];
		$l->current_row['slug_url'] = $url;
	}

}