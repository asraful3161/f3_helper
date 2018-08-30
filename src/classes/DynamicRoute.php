<?php
namespace F3;

Class DynamicRoute extends \Prefab{

	protected $controller, $action;

	public function __construct(){

		$f3=\Base::instance();
		$calledRoute=explode('->', $f3->get('ROUTES.'.$f3->PATTERN.'.0.'.$f3->VERB.'.0'));
		$this->controller=$calledRoute[0];
		$this->action=$calledRoute[1];

	}

	public function render(){

		$f3=\Base::instance();
		$controller=$f3->get('PARAMS.Controller');
		$action=$f3->get('PARAMS.Action');
		$prefix=$f3->VERB=='GET'?'':strtolower($f3->VERB).'_';

		//$class=str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $controller))).'Controller';
		$class=str_replace(['_', '-'], '', ucwords($controller, '_-')).'Controller';

		if(class_exists($class)){

			if($action) $method=$prefix.strtolower(str_replace('-', '_', $action));
			else $method=$prefix.'index';

			if(method_exists($class::instance(), $method)){
				$this->controller=$class;
				$this->action=$method;
				$class::instance()->beforeRoute();
				$class::instance()->$method();
			}else $f3->error(404);

		}else $f3->error(404);

	}

	public function getController(){

		return $this->controller;

	}

	public function getAction(){

		return $this->action;

	}

}