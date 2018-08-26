<?php
namespace F3;

Class Controller extends \Prefab{

	protected $index='/';

	public function index(){

		\F3\Redirect::instance()->to($this->index);

	}

	protected function view($data=[]){

		$backtrace=debug_backtrace()[1];
		$callerClass=strtolower(str_replace('Controller', '', $backtrace['class']));
		$callerAction=$backtrace['function'];

		echo \F3\Twig::instance()->render("{$callerClass}/{$callerAction}.html", $data);

	}

	
	protected function middleware($name, $value=NULL, array $args=[]){

		if(\Base::instance()->CLI) return TRUE;

		$action=\F3\DynamicRoute::instance()->getAction();

		if(isset($args['only'])){

			if(in_array($action, $args['only'])) middleware($name, $value);

		}elseif(isset($args['except'])){

			if(!in_array($action, $args['except'])) middleware($name, $value);

		}else middleware($name, $value);				

	}
	

	protected function validate(array $rules){

		$v=validator(\F3\Input::instance()->all());

		$v->rules($rules);

		if(!$v->validate()){
			\F3\Redirect::instance()->with('validation_alert', ['danger'=>'Validation failed.'])->withInput($v->errors())->toBack();
		}

	}

}