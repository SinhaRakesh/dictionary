<?php

namespace xavoc\dictionary;

class View_Testimonial extends \CompleteLister{
   function init(){
      parent::init();
      
      
      $model = $this->add('xavoc\dictionary\Model_Testimonial');
      $model->addCondition('status','Approved');
      $model->setOrder('id','desc');
      $this->setModel($model);
   }
   
   // function formatRow(){

      // if($this->model['page_name']){
      //    if($this->model['slug_url']){
      //       $this->current_row_html['url'] = $this->app->url($this->model['page_name'],['slug'=>$this['slug_url']]);
      //    }else
      //       $this->current_row_html['url'] = $this->app->url($this->model['page_name']);  
      // }else
      //    $this->current_row_html['url'] = "";

      // parent::formatRow();
   // }

   function defaultTemplate(){
      return ['view/testimonial'];
   }
}