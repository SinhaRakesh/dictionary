<?php

namespace xavoc\dictionary;

class page_import extends \Page{
	public $title = "import page";

	function importlibrary($page){
		$file_id = $this->app->stickyGET('file_id');
		$file_m = $this->add('xepan\filestore\Model_File')->load($file_id);
		$path = $file_m->getPath();
		$importer = new \xepan\base\CSVImporter($path,true,',');
		$csv_data = $importer->get();

		$page->add('View_Console')->set(function($c)use($csv_data){
			$c->out('Import Started');

			$paper = $this->add('xavoc\dictionary\Model_Paper');
			$paper_data = [];
			foreach ($paper->getRows() as $data) {
				$name = strtolower(trim($data['name']));
				$paper_data[$name] = $data['id'];
			}

			$i = 1;
			foreach($csv_data as $key => $data) {
				$lib = $this->add('xavoc\dictionary\Model_Library')
						->addCondition('type','Objective');
				$lib->addCondition('name',strtolower($data['NAME']));
				$lib->tryLoadAny();
				foreach ($data as $field => $value) {
					if($field == "PAPERS") continue;
					$lib[strtolower($field)] = trim($value);
				}
				if(!$data[trim(strtolower('SLUG_URL'))]){
					$lib['slug_url'] = $data['NAME']." ".strtotime($this->app->now);
				}

				$lib->save();

				foreach(explode(",", $data['PAPERS']) as $key => $value) {
					$paper_name = strtolower(trim($value));
					if(!trim($value)) continue;

					if(isset($paper_data[$paper_name])){
						$paper_id = $paper_data[$paper_name];
					}else{
						throw new \Exception($paper_name." paper add first manually ");
						// $new_paper = $this->add('xavoc\dictionary\Model_Paper')
						// 	->addCondition('name',$paper_name)
						// 	->addCondition('paper_type','Objective')
						// 	->tryLoadAny();
						// $new_paper['slug_url'] = $this->app->normalizeName($paper_name);

						// $paper_id = $new_paper->save()->id;
						// $paper_data[$paper_name] = $paper_id;
					}
					$asso = $this->add('xavoc\dictionary\Model_LibraryCourseAssociation');
					$asso->addCondition('course_id',$paper_id);
					$asso->addCondition('library_id',$lib->id);
					$asso->tryLoadAny();
					$asso->save();
				}

				if($i%10 == 0){
					$c->out($i." Record added");
				}
				$i++;
			}
			$c->out("Total = ".($i-1)." Record added successfully");
		});
	}
	function page_index(){
		// parent::init();

		ini_set("memory_limit", "-1");
		set_time_limit(0);

		$this->app->stickyGET('import');

		$vp = $this->add('VirtualPage');
		$vp->set([$this,'importlibrary']);

		$col = $this->add('Columns');
		$col1 = $col->addColumn('6');
		$col2 = $col->addColumn('6');

		$col1->add('View')->set('Objective Import')->addClass('alert alert-info');
		$form = $col2->add('Form');
		$form->addSubmit('Download Sample File')->addClass('btn btn-primary');
		if($_GET['download_sample_csv_file']){
			$output = ['NAME','DESCRIPTION','A','B','C','D','ANSWER','display_order','SLUG_URL','KEYWORD','KEYWORD_DESCRIPTION','PAPERS'];
			$output = implode(",", $output);
	    	header("Content-type: text/csv");
	        header("Content-disposition: attachment; filename=\"sample_dictionary_library_import.csv\"");
	        header("Content-Length: " . strlen($output));
	        header("Content-Transfer-Encoding: binary");
	        print $output;
	        exit;
		}

		if($form->isSubmitted()){
			$form->js()->univ()->newWindow($form->app->url('xavoc_dictionary_import',['download_sample_csv_file'=>true]))->execute();
		}

		// $this->add('View')->setElement('iframe')->setAttr('src',$this->api->url('./execute',array('cut_page'=>1)))->setAttr('width','100%');
		
		$form = $col1->add('Form');
		$form->addField('upload','csv_file')->setModel('xepan\filestore\File');
		$form->addSubmit('Upload');
		if($form->isSubmitted()){
			$form->js()->univ()->frameURL('Importing Files',$this->app->url($vp->getURL(),['file_id'=>$form['csv_file']]))->execute();
		}
		// $form->template->loadTemplateFromString("<form method='POST' action='".$this->api->url(null,array('cut_page'=>1))."' enctype='multipart/form-data'>
		// 	<input type='file' name='library_csv_file'/>
		// 	<input type='submit' class='btn btn-primary' value='Upload'/>
		// 	</form>"
		// 	);

		// if($_FILES['library_csv_file']){
		// 	if ( $_FILES["library_csv_file"]["error"] > 0 ) {
		// 		$this->add( 'View_Error' )->set( "Error: " . $_FILES["library_csv_file"]["error"] );
		// 	}else{
		// 		$mimes = ['text/comma-separated-values', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'text/anytext'];
		// 		if(!in_array($_FILES['library_csv_file']['type'],$mimes)){
		// 			$this->add('View_Error')->set('Only CSV Files allowed');
		// 			return;
		// 		}

		// 		$importer = new \xepan\base\CSVImporter($_FILES['library_csv_file']['tmp_name'],true,',');
		// 		$data = $importer->get();
				
		// 		$this->add('View')->set('All Data Imported');
		// 	}
		// }
	}
}