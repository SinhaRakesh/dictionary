<?php

namespace xavoc\dictionary;

class View_Testimonial extends \CompleteLister{
   public $count=0;
   function init(){
      parent::init();
      
      
      $model = $this->add('xavoc\dictionary\Model_Testimonial');
      $model->addCondition('status','Approved');
      $model->setOrder('id','desc');
      $this->setModel($model);
   }
   
   function formatRow(){
      if($this->count == 1)
         $this->current_row_html['active'] = "active";
      else
         $this->current_row_html['active'] = "deactive";
      $this->count++;

      parent::formatRow();
   }

   function defaultTemplate(){
      return ['view/testimonial'];
   }
}