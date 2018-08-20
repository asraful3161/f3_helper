<?php
namespace F3;

abstract class Controller extends \Prefab{

	protected $index='/';

	public function index(){

		\Base::instance()->reroute($this->index);

	}

	protected function view($data=[]){

		$backtrace=debug_backtrace()[1];
		$callerClass=strtolower(str_replace('Controller', '', $backtrace['class']));
		$callerAction=$backtrace['function'];
		$ext='html';

		echo \F3\Twig::instance()->render("{$callerClass}/{$callerAction}.{$ext}", $data);

	}

	protected function middleware($name, $args=[]){

		if($args){

			if(isset($args['only'])){
				if(in_array(action(), $args['only'])) middleware()->get($name);
			}

			if(isset($args['except'])){
				if(!in_array(action(), $args['except'])) middleware()->get($name);
			}

		}else middleware()->get($name);

	}	

}