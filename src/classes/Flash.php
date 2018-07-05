<?php
namespace F3;

class Flash extends \Prefab{

	protected $f3;

	public function __construct(){
		$this->f3=\Base::instance();
	}

	public function set($key, $value=NULL){

		if(is_string($key)) $this->f3->set("SESSION.flash.{$key}", $value);
		elseif(is_array($key)) $this->f3->set("SESSION.flash", $key);

	}

	public function get($key=NULL){

		if($key){

			$value=$this->f3->get("SESSION.flash.{$key}");
			$this->f3->clear("SESSION.flash.{$key}");

		}else{

			$value=$this->f3->get("SESSION.flash");
			$this->f3->clear("SESSION.flash");

		}
				
		return $value;

	}

	public function exists($key){

		return $this->f3->exists("SESSION.flash.{$key}");

	}

}