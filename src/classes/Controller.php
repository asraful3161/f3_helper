<?php
namespace F3;

class Controller extends \Prefab{

	protected function auth($role=NULL, $permission=NULL){

		$f3=\Base::instance();

		if(empty($f3->get('SESSION.auth'))) $f3->reroute('/');

	}

}