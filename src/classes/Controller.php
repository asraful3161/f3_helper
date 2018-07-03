<?php
namespace F3;

abstract class Controller extends \Prefab{

	protected $index='/';

	public function index(){

		\Base::instance()->reroute($this->index);

	}


	protected function auth($role=NULL, $permission=NULL){

		$f3=\Base::instance();

		if(empty($f3->get('SESSION.auth'))) $f3->reroute('/');

	}

}