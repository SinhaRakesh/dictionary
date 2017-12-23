<?php

namespace xavoc\dictionary;

class Tool_Dictionary extends \xepan\cms\View_Tool{
	public $options = [
			'result_page'=>null
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		$this->app->stickyGET('search_dictionary_id');

		$this->form = $form = $this->add('Form');
		$form->add('xepan\base\Controller_FLC')
			->addContentSpot()
			->layout([
				'search'=>'Dictionary Search~c1~12',
				'FormButtons~&nbsp;'=>'c2~4'
			]);

		$search_field = $form->addField('xepan\base\DropDown','search');
		$search_field->validate_values = false;

		if($_GET[$this->name.'_src_dic']){
			$results = [];
			$model_dic = $this->add('xavoc\dictionary\Model_Dictionary');
			$model_dic->addCondition([['is_auto_added',false],['is_auto_added',null]]);
			$model_dic->addCondition(
				$model_dic->dsql()->orExpr()
				->where('name','like','%'.$_GET['q'].'%')
				->where('description','like','%'.$_GET['q'].'%')
			);
			$model_dic->setLimit(20);
			foreach ($model_dic as $cont) {
				$results[] = ['id'=>$cont->id,'text'=>$cont['name'].' <'.$cont['description'].'>'];
			}

			echo json_encode(
				[
					"results" => $results,
					"more" => false
				]
				);
			exit;
		}

		$search_field->select_menu_options = 
			[	
				'width'=>'100%',
				'tags'=>true,
				'tokenSeparators'=>[',','\n\r'],
				'ajax'=>[
					'url' => $this->api->url(null,[$this->name.'_src_dic'=>true])->getURL(),
					'dataType'=>'json'
				]
			];

		$form->addSubmit('Search')->addClass('btn btn-primary');
		
		if($sdid = $_GET['search_dictionary_id']){
			$view = $this->add('View',null,null,['view/dictionarydetail']);
			$m = $this->add('xavoc\dictionary\Model_Dictionary')
					->addCondition('id',$sdid);
			$m->tryLoadAny();

			if($m->loaded()){
				$view->setModel($m);
				$view->template->setHtml('description_detail',$m['description']);
				if(!$m['antonyms']) $view->template->tryDel('antonyms_wrapper');
				if(!$m['synonyms']) $view->template->tryDel('synonyms_wrapper');
				if(!$m['sentance']) $view->template->tryDel('sentance_wrapper');

			}else{
				$view->template->tryDel('detail_wrapper');
				$view->set('No Record Found');
			}
		}

		if($form->isSubmitted()){					
			if(!is_numeric($form['search'])){
				$new = $this->add('xavoc\dictionary\Model_Dictionary');
				$new->addCondition('name',trim($form['search']));
				$new->addCondition('is_auto_added',true);
				$new->tryLoadAny();

				$new['slug_url'] = $form['name']." = ".$this->app->now;
				$new->save();
			}

			// $form->js()->redirect($this->app->url($this->options['result_page'],['dictionary_id'=>$form['search']]))->execute();
			$this->js()->reload(['search_dictionary_id'=>$form['search']])->execute();
		}
		
	}

	// function recursiveRender(){
	// 	parent::recursiveRender();
	// 	if($this->form->isSubmitted()){
			
	// 	}
	// }

}