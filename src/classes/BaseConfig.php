<?php
namespace F3;

abstract class BaseConfig extends \Prefab{

	protected $env;

	public function __construct(){

		$this->env=parse_ini_file('app/config/env.ini');
		\Base::instance()->set('CONFIGS', $this->register());

	}

	abstract protected function register();
/*
	protected function env($key, $default=NULL){

		$env=\Base::instance()->get($key);
		return $env?$env:$default;

	}
*/
	protected function env($key, $default=NULL){

		return isset($this->env[$key])?$this->env[$key]:$default;

	}

	public function get($key){

		return \Base::instance()->get("CONFIGS.{$key}");

	}

/*	
	public function get($keys){

		$keys=explode('.', $keys);

		foreach($keys as $value){
			if(empty($result) && isset($this->config[$value])){
				$result=$this->config[$value];
			}elseif(isset($result[$value])){
				$result=$result[$value];
			}else{
				$result=NULL;
				break;
			}
		}

		return $result;

	}

	public function get($keys){

		$impoExpo=implode("']['", explode('.', $keys));
		$result=eval("return \$this->config['{$impoExpo}'];");
		return $result?$result:NULL;

	}


	public function fGet($func, $keys){

		if(is_callable([$this, $func])){

			$keys=explode('.', $keys);
			$result=$this->$func();

			foreach($keys as $value){
				if(isset($result[$value])){
					$result=$result[$value];
				}else{
					$result=NULL;
					break;
				}
			}

			return $result;

		} return NULL;

	}

*/

}