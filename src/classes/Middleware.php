<?php
namespace F3;

class Middleware extends \Prefab{

	protected $f3;

	public function __construct(){

		$this->f3=\Base::instance();

	}

	public function set($key, $action){

		$this->f3->set("MIDDLEWARES.$key", $action);
		return $this;

	}

	public function get($key, $args=[]){
		$action=$this->f3->get("MIDDLEWARES.$key");
		if(is_callable($action)) return call_user_func_array($action, $args);
		return FALSE;
	}

}