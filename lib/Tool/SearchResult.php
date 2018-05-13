<?php

namespace xavoc\dictionary;

class Tool_SearchResult extends \xepan\cms\View_Tool{
	public $options = [];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;

		$t = $this->app->stickyGET('slug');
		if(!$_GET['slug']){
			$this->add('View')->set('search string not found')->addClass('alert alert-danger');
			return;
		}

		$this->template->trySet('heading','पाठशाला पर खोजे गए परिणाम : '.$t);
		$this->app->template->trySet('title','पाठशाला पर खोजे गए परिणाम : '.$t);
		
		// Paper Type
		$model = $this->add('xavoc\dictionary\Model_Paper');
		$model->addExpression('Relevance')
				->set('MATCH(name, description, slug_url) AGAINST ("'.$t.'" IN NATURAL LANGUAGE MODE)');
		$model->addCondition('Relevance','>',0)
				->addCondition('status','Active');
 		$model->setOrder('Relevance','Desc');
 		if($model->count()->getOne()){
			$list = $this->add('xavoc\dictionary\View_SearchList',['type'=>'paper']);
			$list->setModel($model);
			$this->template->tryDel('no_record_found');
		}else{
			// no record found
			$this->add('View',null,'no_record_found')
				->setHtml("<p style='font-size:24px;color:gray;'>There are no results for: <br/><strong style='font-width:normal;'>$t</strong>, but we are adding new words/Papers daily.</p>");
			$this->add('xavoc\dictionary\View_PaperCloud',null,'no_record_found');
		}

		// Article
		$model = $this->add('xavoc\dictionary\Model_Article');
		$model->addExpression('Relevance')
				->set('MATCH(name, description, slug_url, keyword, keyword_description) AGAINST ("'.$t.'" IN NATURAL LANGUAGE MODE)');
		$model->addCondition('Relevance','>',0)
				->addCondition('status','Active');
 		$model->setOrder('Relevance','Desc');
 		
 		if($model->count()->getOne()){
			$list = $this->add('xavoc\dictionary\View_SearchList',['type'=>'article']);
			$list->setModel($model);
		}

		// library
		$model = $this->add('xavoc\dictionary\Model_Objective');
		$model->addExpression('paper_str')->set(function($m,$q){
			$x = $m->add('xavoc\dictionary\Model_LibraryCourseAssociation',['table_alias'=>'emails_str']);
			return $x->addCondition('library_id',$q->getField('id'))
						->_dsql()->del('fields')->field($q->expr('group_concat([0] SEPARATOR ",")',[$x->getElement('course_slug_url')]));
		});
		$model->addExpression('Relevance')
				->set('MATCH(name, description, slug_url, keyword, keyword_description) AGAINST ("'.$t.'" IN NATURAL LANGUAGE MODE)');
		$model->addCondition('Relevance','>',0)
				->addCondition('status','Active');
		$model->addCondition('paper_str','<>',null);
 		$model->setOrder('Relevance','Desc');
 		if($model->count()->getOne()){
			$list = $this->add('xavoc\dictionary\View_SearchList',['type'=>'objective']);
			$list->setModel($model);
		}

	}

	function defaultTemplate(){
		return ['view/tool/searchresult'];
	}
}