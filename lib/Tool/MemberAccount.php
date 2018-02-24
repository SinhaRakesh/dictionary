<?php

namespace xavoc\dictionary;

class Tool_MemberAccount extends \xepan\cms\View_Tool{

	public $options = ['login_page'=>'login'];

	function init(){
		parent::init();
		
		if($this->owner instanceof \AbstractController) return;

		$this->student_model = $student_model = $this->add('xavoc\dictionary\Model_Student');
		if(!$student_model->loadLoggedIn('Student')){
			$this->add('View')->set('you are not a student user')->addClass('alert alert-danger');
			return;
		}
		
		$this->student_model->getElement('country_id')->getModel()->addCondition('status','Active');
		$this->student_model->getElement('state_id')->getModel()->addCondition('status','Active');
		$this->addClass('member-panel-tab');

		$column = $this->add('Columns');
		$left_col = $column->addColumn('2');
		$center_col = $column->addColumn('8');
		$right_col = $column->addColumn('2')->setStyle('padding-top','20px');
		
		$center_col->add('View')->setHtml('<div class="row section_title" style="margin-top:20px;"><h2 class="mce-content-body" style="position: relative;">Student Panel</h2><div class="title_border"><div class="icon_side"><i class="mdi mdi-google-physical-web"></i></div></div><div></div></div>');

		$right_col->add('View')->setHtml('<img class="profile-image" src="'.$this->student_model['image'].'"><h5>'.$student_model['name']."</h5>")->addClass('text-center');
		$right_col->add('Button')->set('Logout')->addClass('btn btn-danger btn-block')->js('click')->univ()->redirect('logout');

		$this->tab = $this->add('Tabs');
		$this->history();
		$this->testimonial();
		$this->settings();
	}

	function testimonial(){
		$tab = $this->tab->addTab('Write Testimonial');
		$col = $tab->add('Columns');
		$col1 = $col->addColumn('4');
		$col2 = $col->addColumn('8');

		// Add New Testimonial
		$form = $col1->add('Form');
		$form->add('xepan\base\Controller_FLC')
			->showLables(true)
			->layout([
				'testimonial~Testimonial'=>'Write a new Testimonial~c1~12',
				'FormButtons~&nbsp;'=>'c2~12'
			]);

		$form->addField('text','testimonial')->validate('required');
		$form->addSubmit('Submit')->addClass('btn btn-primary');
		if($form->isSubmitted()){
			$t = $this->add('xavoc\dictionary\Model_Testimonial');
			$t['student_id'] = $this->student_model->id;
			$t['created_by_id'] = $this->app->auth->model->id;
			$t['description'] = $form['testimonial'];
			$t['status'] = "Pending";
			$t->save();

			$form->js(null,$form->js()->reload())->univ()->successMessage('your testimonial submitted successfully')->execute();
		}


		// List Of Testimonial
		$col2->add('View')->set('Testimonial History')->addClass('alert alert-info');
		$t = $this->add('xavoc\dictionary\Model_Testimonial');
		$t->addCondition('created_by_id',$this->app->auth->model->id);
		$t->setOrder('id','desc');
		$grid = $col2->add('xepan\base\Grid');
		$grid->setModel($t,['description','status','created_at']);
		$grid->addPaginator(5);
		$grid->template->tryDel('Pannel');
	}

	function history(){
		$model = $this->add('xavoc\dictionary\Model_MockTest');
		$model->addCondition('user_id',$this->app->auth->model->id);
		$model->addCondition('student_id',$this->student_model->id);
		$model->setOrder('id','desc');

		$tab = $this->tab->addTab('History');
		$grid = $tab->add('xepan\base\Grid');
		$grid->setModel($model);
		$grid->addPaginator(5);
		$grid->template->tryDel('Pannel');

	}

	function settings(){
		$setting_tab = $this->tab->addTab('Settings')->addClass('member-setting');

		$col = $setting_tab->add('Columns');
		$col1 = $col->addColumn(4);
		$col2 = $col->addColumn(8);
		// $col3 = $col->addColumn(5);

		$user = $this->app->auth->model;

		$change_pass_form = $col1->add('Form');
		$change_pass_form->add('xepan\base\Controller_FLC')
			->showLables(true)
			->addContentSpot()
			->layout([
				'user_name'=>'Update Your Password~c1~12',
				'old_password'=>'c1~12',
				'new_password'=>'c1~12',
				'retype_password'=>'c1~12',
				'FormButtons~&nbsp;'=>'c1~12',
			]);

		$change_pass_form->addField('user_name')->set($user['username'])->setAttr('disabled',true);
		$change_pass_form->addField('password','old_password')->validate('required');
		$change_pass_form->addField('password','new_password')->validate('required');
		$change_pass_form->addField('password','retype_password')->validate('required');
		$change_pass_form->addSubmit('Change Password')->addClass('btn btn-primary');

		if($change_pass_form->isSubmitted()){
			if( $change_pass_form['new_password'] != $change_pass_form['retype_password'])
				$change_pass_form->displayError('new_password','password not match');
			
			if(!$this->api->auth->verifyCredentials($user['username'],$change_pass_form['old_password']))
				$change_pass_form->displayError('old_password','password not match');

			if($user->updatePassword($change_pass_form['new_password'])){
				$this->app->auth->logout();
				$this->app->redirect($this->options['login_page']);
			}
			$change_pass_form->js()->univ()->errorMessage('some thing happen wrong')->execute();
		}


		// Profile Update
		$form = $col2->add('Form');
		$form->add('xepan\base\Controller_FLC')
			->showLables(true)
			->addContentSpot()
			->layout([
				'first_name'=>'Update Your Profile~c1~6',
				'last_name'=>'c2~6',
				'country_id~Country'=>'c11~6',
				'state_id~State'=>'c11~6',
				'city'=>'c11~6',
				'pin_code'=>'c11~6',
				'address'=>'c12~6',
				'image_id~Profile Image'=>'c12~6',
				'FormButtons~&nbsp;'=>'c21~12',
			]);

		$form->setModel($this->student_model,['first_name','last_name','country_id','state_id','city','address','pin_code','image_id']);
				
		$state_field = $form->getElement('state_id');
		if($_GET['country_id']){
			$state_field->getModel()->addCondition('country_id',$_GET['country_id']);
		}
		$country_field = $form->getElement('country_id');
		$country_field->js('change',$form->js()->atk4_form('reloadField','state_id',[$this->app->url(null,['cut_object'=>$state_field->name]),'country_id'=>$country_field->js()->val()]));
		$form->addSubmit('Update')->addClass('btn btn-primary');
		$form->getElement('image_id')
			->allowMultiple(1)
			->setFormatFilesTemplate('view/fileupload');

		if($form->isSubmitted()){
			$form->save();
			$form->js()->univ()->successMessage('saved')->execute();
		}

		// // Email and Phone no
		// $cp_model = $col3->add('xepan\base\Model_Contact_Email');
		// $cp_model->addCondition('contact_id',$this->student_model->id);
		// $cp_model->addCondition('is_active',true);
		// $cp_model->addCondition('is_valid',true);
		// $cp_model->addCondition('head','Official');
		
		// $crud = $col3->add('CRUD',['entity_name'=>"Email"]);
		// $crud->setModel($cp_model,['value']);
		// $crud->grid->addPaginator($ipp=5);
			
	// 	$col3->add('View')->setElement('hr');

		// $cp_model = $col3->add('xepan\base\Model_Contact_Phone');
		// $cp_model->addCondition('contact_id',$this->student_model->id);
		// $cp_model->addCondition('is_active',true);
		// $cp_model->addCondition('is_valid',true);
		// $cp_model->addCondition('head','Official');	

		// 	$crud = $col3->add('CRUD',['entity_name'=>"Phone"]);
		// 	$crud->setModel($cp_model,['value']);
		// 	$crud->grid->addPaginator($ipp=5);

	}
}