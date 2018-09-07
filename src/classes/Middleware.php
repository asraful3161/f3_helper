<?php
namespace F3;

class Middleware extends \Prefab{

	final public function call($name, $args=NULL){
		//To call multiple middleware as name arguments.
		if(is_string($name)){

			$method=lcfirst(str_replace(['_', '-'], '', ucwords($name, '_-')));

			if(method_exists($this, $method)){
				if($args) return $this->$method($args);
				return $this->$method();
			}

			\Base::instance()->error(404);

		}elseif(is_array($name)){

			foreach($name as $key=>$value){
				if(is_string($key)) $this->call($key, $value);
				else $this->call($value);
			}

		}
	}

}