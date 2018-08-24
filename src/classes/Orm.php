<?php
namespace F3;

class Orm extends \Prefab{

	protected $namespace, $model, $query;

	public function __construct(){

		$this->namespace="\\Orm\\";

	}

	public function get($model){

		$this->model=$this->namespace.str_replace(['_', '-'], '', ucwords($model, '_-'));
		$this->query=$this->model.'Query';
		return $this;

	}

	public function bind($pk=NULL){

		if($pk){
			$result=$this->query::create()->findPK($pk);
			if($result) return $result;
		}

		return new $this->model;

	}

	public function bindInput($pk=NULL, $keys=[]){

		$model=$this->bind($pk);
		$model->fromArray(\F3\Input::instance()->forModel($keys));
		return $model;

	}

	public function query(){
		return $this->query::create();
	}

	public function paginate($default=10){
		return $this->query::create()->paginate(\F3\Input::instance()->get('page', 1), $default);
	}

	public function findPK($pk){
		return $this->query::create()->findPK($pk);
	}

	public function pluck($key, $value){
		return $this->query::create()->find()->toKeyValue($key, $value);
	}

}