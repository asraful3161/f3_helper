<?php
namespace F3;

abstract class Controller extends \Prefab{

	protected $index='/';

	public function index(){

		\Base::instance()->reroute($this->index);

	}


	protected function auth($role=NULL, $permission=NULL){

		if(!\F3\DAuth::instance()->user()->check()){
			$url=\F3\Url::instance();
			$url->intended($url->current());
			return \Base::instance()->error(404);
		}

	}
	

}