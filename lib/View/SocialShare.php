<?php

namespace xavoc\dictionary;

class View_SocialShare extends \View{
	public $owner_model;
	public $social_shares = ['email','twitter','facebook','googleplus','linkedin','pinterest','stumbleupon'];
	public $sharing_url;
	function init(){
		parent::init();
			
		if(!$this->sharing_url){
			$url = $this->app->url($this->app->page."/".$this->owner_model['slug_url']);
			$url->arguments = ['xepan_landing_content_id'=>$this->owner_model->id];
			$url->absolute();
			$this->sharing_url = $url;
		}
		$text = "Word Of Day: ".$this->owner_model['name'];

		$this->js(true)->jsSocials(
						[
							'shares'=>$this->social_shares,
							'url'=>$this->sharing_url,
							'text'=>$text
						]);
	}

	function recursiveRender(){
		parent::recursiveRender();

		$this->js(true)->_load('socialshare/jssocials');
		$this->js(true)->_css('socialshare/jssocials');
		$this->js(true)->_css('socialshare/jssocials-theme-flat'); //classic,minima,plain
	}
}
