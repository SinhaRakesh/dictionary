<?php

namespace xavoc\dictionary;

class page_managequestion extends \xepan\base\Page{
	public $title = "Management";
	
	function init(){
		parent::init();
		
		$paper_id = $this->app->stickyGET('paper_id');
		$course = $this->add('xavoc\dictionary\Model_Course');
		$course->load($paper_id);

		$this->title = "Add Question of paper: ".$course['name'];
		// $form = $this->add('Form');
		// $form = $crud->form;
		$field_array = [
				'name'=>'Question Detail~c1~8',
				'display_order'=>'c2~2',
				'status'=>'c3~2',
				'a'=>'c21~3',
				'b'=>'c22~3',
				'c'=>'c23~3',
				'd'=>'c24~3',
				'answer'=>'c31~6',
				'description'=>'c11~12'
			];
		if($course['paper_type'] != "Objective"){
			unset($field_array['a']);
			unset($field_array['b']);
			unset($field_array['c']);
			unset($field_array['d']);
			unset($field_array['answer']);
		}


		$form = $this->add('Form');
		$crud = $this->add('xepan\hr\CRUD',['allow_add'=>false]);
		$crud->form->add('xepan\base\Controller_FLC')
			->makePanelsCoppalsible(true)
			->addContentSpot()
			->layout($field_array);

		$form->add('xepan\base\Controller_FLC')
			->makePanelsCoppalsible(true)
			->addContentSpot()
			->layout($field_array);

		$form->addField('name')->validate('required');
		$form->addField('Number','display_order')->set(0);
		$form->addField('DropDown','status')->setValueList(['Active'=>'Active','Inactive'=>'Inactive']);

		if($course['paper_type'] == 'Objective'){
			$form->addField('a')->validate('required');
			$form->addField('b')->validate('required');
			$form->addField('c')->validate('required');
			$form->addField('d')->validate('required');
			$form->addField('answer')->validate('required');
		}

		if($course['paper_type'] == "Descriptive")
			$form->addField('text','description');
		else
			$form->addField('xepan\base\RichText','description');

		$form->addSubmit('Add')->addClass('btn btn-primary');

		if($form->isSubmitted()){
			
			if($course['paper_type'] == "Descriptive")
				$dic = $this->add('xavoc\dictionary\Model_Descriptive');
			else
				$dic = $this->add('xavoc\dictionary\Model_Objective');

			$dic['name'] = $form['name'];
			$dic['slug_url'] = $this->app->normalizeName($form['name'])."-".$course['name'];
			$dic['display_order'] = $form['display_order'];
			$dic['description'] = $form['description'];
			if($course['paper_type'] == 'Objective'){
				$dic['a'] = $form['a'];
				$dic['b'] = $form['b'];
				$dic['c'] = $form['c'];
				$dic['d'] = $form['d'];
				$dic['answer'] = $form['answer'];
			}
			$dic->save();

			$asso = $this->add('xavoc\dictionary\Model_LibraryCourseAssociation');
			$asso['course_id'] = $paper_id;
			$asso['library_id'] = $dic->id;
			$asso->save();

			$form->js(null,[$form->js()->reload(),$crud->js()->reload()])->univ()->successMessage('Question Added Successfully')->execute();
		}

		if($course['paper_type'] == "Descriptive"){
			$record = $this->add('xavoc\dictionary\Model_Descriptive');
		}else
			$record = $this->add('xavoc\dictionary\Model_Objective');

		$join = $record->join('library_course_association.library_id');
		$join->addField('course_id');
		$record->addCondition('course_id',$paper_id);
		$record->setOrder('id','desc');
		if($course['paper_type'] == "Objective")
			$crud->setModel($record,['name','status','display_order','a','b','c','d','answer','description'],['name','answer','status','action']);
		else
			$crud->setModel($record,['name','status','display_order','description','action']);
		$crud->grid->removeAttachment();
		$crud->grid->addPaginator($ipp=10);
	}
}