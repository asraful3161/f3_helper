<?php
namespace F3;

class Middleware extends \Prefab{

	protected $list;

	public function __construct(){

		$this->list=new \F3\Std;

	}

	public function set($key, $action){

		$this->list->set($key, $action);
		return $this;

	}

	public function get($key, $args=[]){
		$action=$this->list->get($key);
		if(is_callable($action)) return call_user_func_array($action, $args);
		return FALSE;
	}

}