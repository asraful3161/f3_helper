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

	protected function view($data=[]){

		$backtrace=debug_backtrace()[1];
		$callerClass=strtolower(str_replace('Controller', '', $backtrace['class']));
		$callerAction=$backtrace['function'];
		$ext='html';

		echo \F3\Twig::instance()->render("{$callerClass}/{$callerAction}.{$ext}", $data);

	}
	

}