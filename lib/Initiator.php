<?php

namespace xavoc\dictionary;

class Initiator extends \Controller_Addon {
    
    public $addon_name = 'xavoc_dictionary';

    function setup_admin(){

        $this->routePages('xavoc_dictionary');
        $this->addLocation(array('template'=>'templates','js'=>'templates/js','css'=>'templates/css'))
        ->setBaseURL('../shared/apps/xavoc/dictionary/');

        $m = $this->app->top_menu->addMenu('Pathshala');
        $m->addItem(['Course','icon'=>'fa fa-cog'],'xavoc_dictionary_course');
        $m->addItem(['Paper','icon'=>'fa fa-cog'],'xavoc_dictionary_paper');
        $m->addItem(['Descriptive','icon'=>'fa fa-cog'],'xavoc_dictionary_descriptive');
        $m->addItem(['Objective','icon'=>'fa fa-cog'],'xavoc_dictionary_objective');
        $m->addItem(['Article','icon'=>'fa fa-cog'],'xavoc_dictionary_article');
        

        $m = $this->app->top_menu->addMenu('Dictionary');
        $m->addItem(['Word of day','icon'=>'fa fa-cog'],'xavoc_dictionary_wordofday');
        $m->addItem(['Dictionary','icon'=>'fa fa-cog'],'xavoc_dictionary_dictionary');
        $m->addItem(['New Word From Client','icon'=>'fa fa-cog'],'xavoc_dictionary_newword');
        $m->addItem(['Part Of Speech','icon'=>'fa fa-cog'],'xavoc_dictionary_partofspeech');
        
        $mock = $this->app->top_menu->addMenu('Mock');
        $mock->addItem(['Mock Category','icon'=>'fa fa-cog'],'xavoc_dictionary_mockcategory');
        $mock->addItem(['Mock Paper','icon'=>'fa fa-cog'],'xavoc_dictionary_mockpaper');
        $mock->addItem(['Student','icon'=>'fa fa-cog'],'xavoc_dictionary_student');
        $mock->addItem(['User Account','icon'=>'fa fa-cog'],'xepan_hr_user');
        $mock->addItem(['Testimonial','icon'=>'fa fa-cog'],'xavoc_dictionary_testimonial');
        $mock->addItem(['Quote','icon'=>'fa fa-cog'],'xavoc_dictionary_quote');

        return $this;
    }

    function setup_pre_frontend(){
        $this->routePages('xavoc_dictionary');
        $this->addLocation(array('template'=>'templates','js'=>'templates/js','css'=>'templates/css'))
        ->setBaseURL('./shared/apps/xavoc/dictionary/');

        return $this;
    }


    function setup_frontend(){
        $this->routePages('xavoc_dictionary');
        $this->addLocation(array('template'=>'templates','js'=>'templates/js','css'=>'templates/css'))
        ->setBaseURL('./shared/apps/xavoc/ispmanager/');
        $this->app->stickyGET('course');
        $this->app->stickyGET('paper');

        $this->app->addHook('login_panel_user_loggedin',function($app,$user){
            $contact = $this->add('xepan\base\Model_Contact');
            if($contact->loadLoggedIn('Student')){
                if(!$_GET['paper']){
                    $this->app->redirect($this->app->url('mock-category'));
                }
                $this->app->redirect($this->app->url('mock-test',['course'=>$_GET['course'],'paper'=>$_GET['paper']]));
            }
        });

        $stu = $this->add('xavoc\dictionary\Model_Student');
        $this->app->addHook('userCreated',[$stu,'createNewStudent']);

        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_LibraryList','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_Descriptive','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_Course','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_CourseDetail','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_WordOfDay','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_MockTest','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_Dictionary','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_Search','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_WordList','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_LibraryDetail','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_SearchResult','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_MockCategory','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_MemberAccount','Dictionary');
        $this->app->exportFrontEndTool('xavoc\dictionary\Tool_Testimonial','Dictionary');
                
        return $this;
    }

}
