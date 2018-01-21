<?php

namespace xavoc\dictionary;

class Tool_Dictionary extends \xepan\cms\View_Tool{
	public $options = [
			'result_page'=>'englishword',
			'show_detail'=>true,
			'show_abcd_search_bar'=>true,
			'abcd_list_page'=>'wordlist'
		];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;
		$search_dictionary_id = $_GET['search_dictionary_id'];

		$this->addClass('path-dictinary-searchtool');
		$this->form = $form = $this->add('Form')->addClass('pathshala-dictionary-search');
		$form->add('xepan\base\Controller_FLC')
			->addContentSpot()
			->layout([
				'search~'=>'<strong style="font-size:20px;"> डिक्शनरी सर्च </strong>~c1~10',
				'FormButtons~'=>'c2~2'
			]);

		$search_field = $form->addField('xepan\base\DropDown','search');
		$search_field->validate_values = false;
		$button_set = $this->add('View')->setStyle('margin-bottom','20px;');
		$search_field->validate('required');

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
				$results[] = ['id'=>$cont->id,'text'=>$cont['name']];
			}

			echo json_encode(
				[
					"results" => $results,
					"more" => false
				]);
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

		$form->addSubmit('Search')->addClass('btn btn-sm path-btn selected');

		$name = $_GET['word'];
		if(( ($sdid = $search_dictionary_id) || $name) && $this->options['show_detail']){
			$view = $this->add('View',null,null,['view/dictionarydetail']);
			$m = $this->add('xavoc\dictionary\Model_Dictionary');
			
			if($name)
				$m->addCondition('name',$name);
			else
				$m->addCondition('id',$sdid);

			$m->tryLoadAny();
			if($m->loaded()){
				$view->setModel($m);

				if($m['part_of_speech_id']){
					$view->template->trySet('part_of_speech_text',"{ ".$m['part_of_speech']." }");
				}

				$lister = $view->add('Lister',null,'description_detail',['view/dictionarydetail','description_detail']);
				$lister->setSource(explode(',',$m['description']));
				// $view->template->setHtml('description_detail',$m['description']);

				if(!$m['antonyms']){
					$view->template->tryDel('antonyms_wrapper');
				}else{
					$t_array = explode(",", $m['antonyms']);
					$list = [];
					foreach ($t_array as $key => $value) {
						$list[$value] = $value;
					}
					$d_temp = $this->add('xavoc\dictionary\Model_Dictionary');
					$d_temp->addCondition('name',$list);
					foreach ($d_temp as $temp) {
						if(isset($list[$temp['name']]))
							$list[$temp['name']] = '<a href="'.$this->app->url(null,['word'=>$temp['name']]).'">'.$temp['name'].'</a>';
					}
					$list_string = implode(",", $list);
					$view->template->setHtml('antonyms_list',$list_string);
				}

				if(!$m['synonyms']){
					$view->template->tryDel('synonyms_wrapper');
				} else{
					$t_array = explode(",", $m['synonyms']);
					$list = [];
					foreach ($t_array as $key => $value) {
						$list[$value] = $value;
					}
					$d_temp = $this->add('xavoc\dictionary\Model_Dictionary');
					$d_temp->addCondition('name',$list);
					foreach ($d_temp as $temp) {
						if(isset($list[$temp['name']]))
							$list[$temp['name']] = '<a href="'.$this->app->url(null,['word'=>$temp['name']]).'">'.$temp['name'].'</a>';
					}
					$list_string = implode(",", $list);
					$view->template->setHtml('synonyms_list',$list_string);
				}

				if(!$m['sentance']) $view->template->tryDel('sentance_wrapper');

				$list = explode(':',$m['sentance']);
				$shtml = "";
				foreach ($list as $key => $name) {
					$shtml .= '<div style="margin-left:20px;padding: 10px;font-size: 16px;"><span class="fa">'.($key+1).'. </span>'.$name.'</div>';
				}
				if(count($list)){
					$view->add('View',null,'sentancelist')->setHtml($shtml);
				}

				// if(count($list)){
				// 	$list = $view->add('Lister',null,'sentance_list',['view/dictionarydetail','sentance_list']);
				// 	$list->setSource($list);
				// }

			}else{
				$view->template->tryDel('detail_wrapper');
				$view->set('No Record Found');
			}

			$l = $this->add('CompleteLister',null,null,['view/tool/wordofday','recent_words']);
			$model = $this->add('xavoc\dictionary\Model_Dictionary');
			$model->setOrder('id','desc');
			$model->setLimit(20);
			$l->setModel($model);

			$l->addHook('formatRow',function($g){
				$g->current_row['slug_url'] = $this->app->url('englishword',['slug'=>$g->model['slug_url']]);
			});

			$text = "<h4>Competition exam dictionary - <span>".$m['name']."</span></h4> <br/> Meaning of ".$m['name'].", ".$m['name']." meaning in Hindi, definition of ".$m['name'].", ".$m['name']." in Hindi language , What is meaning of ".$m['name'].", what ".$m['name']." means in Hindi , ".$m['name']." dictionary meaning , ".$m['name']." dictionary in Hindi meaning , ".$m['name']." meaning in Hindi and English ,  dictionary word ".$m['name']." meaning , What ".$m['name'] ." means in English-Hindi Dictionary <br/> <br><h4> प्रतियोगिता परिक्षा के लिए शब्द-ज्ञान - <span>".$m['name']."<span> </h4><br/> डिक्शनरी में अंग्रेजी शब्द का हिन्दी अर्थ ".$m['name']." शब्दकोश में अंग्रेजी शब्द का हिन्दी अर्थ , शब्द ".$m['name']." का हिन्दी मिनिंग , ".$m['name']." हिन्दी भाषा में अर्थ , अंग्रेजी शब्द ".$m['name']." का रोचक अर्थ , अंग्रेजी शब्द ".$m['name']." का हिन्दी अर्थ परिभाषा , ".$m['name']." का हिन्दीं में क्या अर्थ होता है , ".$m['name']." का क्या हिन्दी मतलब होता है , शब्द ".$m['name']." का हिन्दी अभिपाय क्या है";
			$this->add('View')->addClass('word-detail')->setHtml($text)->setStyle('text-align','justify');
		}else{
			$this->add('View')->set('इस अंग्रेज़ी हिन्दी-अंग्रेजी डिक्शनरी में आप आसानी से हिन्दी और अंग्रेज़ी शब्दों के अर्थ ढूंढ सकते हैं। हिन्दी-अंग्रेजी डिक्शनरी ( Hindi to English Dictionary ). पाठशाला उपलब्ध करायी जा रही फ्री ऑनलाइन हिंदी से अंग्रेजी स्टूडेंट डिक्शनरी सटीक अर्थ ढूढने में मदद करता है। साथ ही, आप मिलते-जुलते शब्दों को भी जान सकते है।');
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

			if($this->options['show_detail']){
				$this->app->redirect($this->app->url(null,['search_dictionary_id'=>$form['search']]))->execute();
				// $this->js()->reload(['search_dictionary_id'=>$form['search']])->execute();
			}else{
				$form->js()->redirect($this->app->url($this->options['result_page'],['dictionary_id'=>$form['search']]))->execute();
			}
		}
		
		if($this->options['show_abcd_search_bar']){
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>A</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'A']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>B</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'B']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>c</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'C']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>D</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'D']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>E</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'E']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>F</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'F']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>G</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'G']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>H</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'H']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>I</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'I']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>J</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'J']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>K</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'K']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>L</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'L']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>M</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'M']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>N</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'N']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>O</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'O']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>P</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'P']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>Q</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'Q']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>R</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'R']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>S</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'S']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>T</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'T']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>U</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'U']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>V</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'V']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>W</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'W']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>X</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'X']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>Y</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'Y']));
			$button_set->add('Button')->addClass('letter-button')->setHtml('<strong>Z</strong>')->addClass('btn btn-info custom_hover')->js('click')->univ()->redirect($this->app->url($this->options['abcd_list_page'],['letter'=>'Z']));
		}

		$this->add('View')->setHtml('h1')->addClass('heading')->setHtml('<p>लोकप्रिय शब्द</p>');
		$l = $this->add('CompleteLister',null,null,['view/tool/wordofday','recent_words']);
		$m = $this->add('xavoc\dictionary\Model_Dictionary');
		$m->addCondition('is_popular',true);
		$m->setOrder('id','desc');
		$m->setLimit(20);
		$l->setModel($m);		
		$l->addHook('formatRow',function($g){
			$g->current_row['slug_url'] = $this->app->url('englishword',['slug'=>$g->model['slug_url']]);
			$g->current_row['extra_class'] = "btn-danger";
		});


		$m = $this->add('xavoc\dictionary\Model_Dictionary');
		$m->addCondition([['is_popular',false],['is_popular',null]]);
		$m->setOrder('id','desc');
		if($m->count()->getOne()){
			$m->setLimit(20);

			$this->add('View')->setHtml('h1')->addClass('heading')->setHtml('<p>नवीनतम शब्द</p>');
			$l = $this->add('CompleteLister',null,null,['view/tool/wordofday','recent_words']);
			$l->setModel($m);
			$l->addHook('formatRow',function($g){
				$g->current_row['slug_url'] = $this->app->url('englishword',['slug'=>$g->model['slug_url']]);
				$g->current_row['extra_class'] = "btn-success";
			});
		}

	}
}