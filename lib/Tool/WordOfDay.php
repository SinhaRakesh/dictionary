<?php

namespace xavoc\dictionary;

class Tool_WordOfDay extends \xepan\cms\View_Tool{
	public $options = [
			'show_image'=>false,
			'detail_page'=>'word-of-day'
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		$slug = $_GET['slug'];


		$model = $this->add('xavoc\dictionary\Model_Dictionary');
		$model->addCondition('is_word_of_day',1);
		$model->addCondition('duration',0);
		$model->tryLoadAny();
		if(!$model->loaded()){
			$model = $this->add('xavoc\dictionary\Model_Dictionary');
			$model->addExpression('duration2')->set(function($m,$q){
				return $q->expr('IFNULL([0],0)',[$m->getElement('duration')]);
			});
			$model->addCondition([['duration2',0],['duration2','>','360']]);
			$model->setOrder('duration2','asc');
		}

		if($slug){
			$model = $this->add('xavoc\dictionary\Model_Dictionary');
			$model->addCondition('slug_url',$slug);
		}

		$model->setLimit(1);
		$model->tryLoadAny();
		
		if(!$model->loaded()){
			$this->add('View',null,'not_found')->set('word of day not found');
		}else{
			if(!$model['word_of_day_on_date']){

				$lib = $this->add('xavoc\dictionary\Model_Dictionary');
				$lib->addCondition('is_word_of_day',true)
					->tryLoadAny();
				if($lib->loaded()){
					$lib['is_word_of_day'] = false;
					$lib->save();
				}
				$model['word_of_day_on_date'] = $this->app->today;
				$model['is_word_of_day'] = true;
				$model->save();
			}
		}
		
		$this->setModel($model);
		$this->template->trySetHtml('description_detail',$model['description']);

		$this->template->set('url',$this->app->url($this->options['detail_page'],['slug'=>$model['slug_url']]));
		$this->template->set('word_of_day_date',date('l, F d, Y',strtotime($model['word_of_day_on_date'])));

		if(!$this->options['show_image']){
			$this->template->tryDel('img_wrapper');
			$this->template->tryDel('recent_words');
			$this->template->tryDel('previous_wordofday');
			
		}else{

			$this->template->tryDel('read_more_button');
			if($model['image'])
				$this->template->set('image_url',$model['image']);
			else
				$this->template->set('image_url',"websites/".$this->app->current_website_name."/www/img/word_of_day_default.jpg");

			if(!$model['sentance']){	
				$view->template->tryDel('sentance_wrapper');
			}else{
				$list = explode(':',$model['sentance']);
				$shtml = "";
				foreach ($list as $key => $name) {
					$shtml .= '<div style="margin-left:20px;padding: 10px;font-size: 16px;"><span class="fa">'.($key+1).'. </span>'.$name.'</div>';
				}
				if(count($list)){
					$this->add('View',null,'sentancelist')->setHtml($shtml);
				}
				
			}


			$l = $this->add('CompleteLister',null,'recent_words',['view/tool/wordofday','recent_words']);
			$m = $this->add('xavoc\dictionary\Model_Dictionary');
			$m->addCondition('id','<>',$model['id']);
			$m->setOrder('id','desc');
			$m->setLimit(20);
			$l->setModel($m);

			$l->addHook('formatRow',function($g){
				$g->current_row['slug_url'] = $this->app->url('englishword',['slug'=>$g->model['slug_url']]);
			});
			
			$model = $this->add('xavoc\dictionary\Model_Library');
			$model->addCondition('word_of_day_on_date','<>',$this->app->today);
			$model->addCondition('word_of_day_on_date','<>',null);
			$model->setOrder('word_of_day_on_date','desc');
			$model->setLimit(6);

			$list = $this->add('CompleteLister',null,'previous_wordofday',['view/tool/wordofday','previous_wordofday']);
			$list->setModel($model);
			$list->addHook('formatRow',function($l){
				if($l->model['image'])
					$l->current_row_html['image_url'] = $l->model['image'];
				else
					$l->current_row_html['image_url'] = "websites/".$this->app->current_website_name."/www/img/word_of_day_default.jpg";
				
				$l->current_row_html['slug_url'] = $this->app->url(null,['slug'=>$l->model['slug_url']]);
			});
			if(!$model->count()->getOne()){
				$list->template->tryDel('previous_heading');
			}

		}
	}

	function defaultTemplate(){
		return ['view/tool/wordofday'];
	}
}