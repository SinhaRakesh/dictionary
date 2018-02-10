<?php

namespace xavoc\dictionary;

class Model_Student extends \xepan\base\Model_Contact{
	
	public $type = "Student";

	function init(){
		parent::init();

		$this->getElement('created_by')->system(true);
		$this->getElement('assign_to')->system(true);
		$this->getElement('created_at')->system(true);
		$this->getElement('updated_at')->system(true);

		$this->addCondition('type','Student');
	}


	function addMockTest($mock_paper){
		if(!$this->loaded()) throw new \Exception("Add Mock Test student record not loaded");
				
		$record = $this->add('xavoc\dictionary\Model_MockTest');
		$record['paper_id'] = $mock_paper->id;
		$record['user_id'] = $this->app->auth->model->id;
		$record['student_id'] = $this->id;

		$record['started_at'] = $this->app->now;
		$record['deadline_at'] = date('Y-m-d H:i:s',strtotime("+".$mock_paper['mock_test_duration']." minutes", strtotime($this->app->now)));
		$record->save();

		return $record;
	}

	function createNewStudent($app,$contact_detail=[],$user){
		
		$user = $this->add('xepan\base\Model_User')->load($user->id);
		$email_info = $this->add('xepan\base\Model_Contact_Email');
		$email_info->addCondition('value',$user['username']);
		$email_info->tryLoadAny();

		if($email_info->loaded()){

			$contact = $this->add('xepan\base\Model_Contact')
					->load($email_info['contact_id']);

			if($contact['type'] == 'Contact'){
				// if(!$this->add('xavoc\dictionary\Model_Student')->tryLoad($contact->id)->loaded()){
				// 	$this->app->db->dsql()->table('contact')
				// 		->set('contact_id',$contact->id)
				// 		->insert();
				// }

				$contact['first_name'] = $contact_detail['first_name'];
				$contact['last_name'] = $contact_detail['last_name'];
				$contact['type'] = 'Student';
				$contact['user_id'] = $user->id;

				if(isset($contact_detail['country']))
					$contact['country_id'] = $contact_detail['country'];
				if(isset($contact_detail['state']))
					$contact['state_id'] = $contact_detail['state'];
				if(isset($contact_detail['city']))
					$contact['city'] = $contact_detail['city'];
				if(isset($contact_detail['address']))
					$contact['address'] = $contact_detail['address'];
				if(isset($contact_detail['pin_code']))
					$contact['pin_code'] = $contact_detail['pin_code'];

				$contact->save();

				if(isset($contact_detail['mobile_no']) && $contact_detail['mobile_no']){
					
					$phone = $this->add('xepan\base\Model_Contact_Phone');
					$phone->addCondition('value',$contact_detail['mobile_no']);
					$phone->addCondition('contact_id',$contact->id);
					$phone->tryLoadAny();
					if(!$phone->loaded())
						$phone['head'] = "Official";
					
					$phone->save();
				}
			}
			
		}else{

			$student = $this->add('xavoc\dictionary\Model_Student');
			$student['first_name'] = $contact_detail['first_name'];
			$student['last_name'] = $contact_detail['last_name'];
			$student['user_id'] = $user->id;

			if(isset($contact_detail['country']))
				$student['country_id'] = $contact_detail['country'];
			if(isset($contact_detail['state']))
				$student['state_id'] = $contact_detail['state'];
			if(isset($contact_detail['city']))
				$student['city'] = $contact_detail['city'];
			if(isset($contact_detail['address']))
				$student['address'] = $contact_detail['address'];
			if(isset($contact_detail['pin_code']))
				$student['pin_code'] = $contact_detail['pin_code'];

			$student->save();
			
			$email = $this->add('xepan\base\Model_Contact_Email');
			$email['contact_id'] = $student->id;
			$email['head'] = 'Official';
			$email['value'] = $user['username'];
			$email->save();

			if(isset($contact_detail['mobile_no']) && $contact_detail['mobile_no']){
				$phone = $this->add('xepan\base\Model_Contact_Phone');
				$phone['contact_id'] = $student->id;
				$phone['head'] = 'Official';
				$phone['value'] = $contact_detail['mobile_no'];
				$phone->save();
			}
		}
	}

}