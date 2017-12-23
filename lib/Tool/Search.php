<?php

namespace xavoc\dictionary;

class Tool_Search extends \xepan\cms\View_Tool{
	public $options = [
			'result_page'=>'paper'
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		
		$this->form = $form = $this->add('Form');
		$form->add('xepan\base\Controller_FLC')
			->addContentSpot()
			->layout([
				'search'=>'Search~c1~12',
				'FormButtons~&nbsp;'=>'c2~4'
			]);

		$search_field = $form->addField('xepan\base\DropDown','search');
		$search_field->validate_values = false;

		if($_GET[$this->name.'_src_paper']){
			$results = [];
			$model_dic = $this->add('xavoc\dictionary\Model_Paper');			
			$model_dic->addCondition(
				$model_dic->dsql()->orExpr()
				->where('name','like','%'.$_GET['q'].'%')
				->where('description','like','%'.$_GET['q'].'%')
			);
			$model_dic->setLimit(50);
			foreach ($model_dic as $cont) {
				$results[] = ['id'=>$cont['slug_url'],'text'=>$cont['name'].($cont['description']?(' <'.$cont['description'].'>'):'')];
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
					'url' => $this->api->url(null,[$this->name.'_src_paper'=>true])->getURL(),
					'dataType'=>'json'
				]
			];

		$form->addSubmit('Search')->addClass('text-center btn btn-primary');
		
		// if($sdid = $_GET['search_dictionary_id']){
		// 	$view = $this->add('View',null,null,['view/dictionarydetail']);
		// 	$m = $this->add('xavoc\dictionary\Model_Dictionary')
		// 			->addCondition('id',$sdid);
		// 	$m->tryLoadAny();
		// 	if($m->loaded()){
		// 		$view->setModel($m);
		// 		$view->template->setHtml('description_detail',$m['description']);
		// 		if(!$m['antonyms']) $view->template->tryDel('antonyms_wrapper');
		// 		if(!$m['synonyms']) $view->template->tryDel('synonyms_wrapper');
		// 		if(!$m['sentance']) $view->template->tryDel('sentance_wrapper');

		// 	}else{
		// 		$view->template->tryDel('detail_wrapper');
		// 		$view->set('No Record Found');
		// 	}
		// }

		if($form->isSubmitted()){
			// if(!is_numeric($form['search'])){
			// 	$new = $this->add('xavoc\dictionary\Model_Dictionary');
			// 	$new->addCondition('name',trim($form['search']));
			// 	$new->addCondition('is_auto_added',true);
			// 	$new->tryLoadAny();
			// 	$new['slug_url'] = $form['name']." = ".$this->app->now;
			// 	$new->save();
			// }
			// $this->js()->reload(['slug_url'=>$form['search']])->execute();
			if(!$form['search']) $form->error('search','search must not be empty');

			$form->js()->redirect($this->app->url($this->options['result_page'],['slug'=>$form['search']]))->execute();
		}
		
	}
}