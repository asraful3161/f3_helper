<?php
namespace F3;

class Flash extends \Prefab{

	public function set($key, $value=NULL){

		if(is_string($key)) \Base::instance()->set("SESSION.flash.{$key}", $value);
		elseif(is_array($key)) \Base::instance()->set("SESSION.flash", $key);

	}

	public function get($key=NULL){

		if($key){

			$value=\Base::instance()->get("SESSION.flash.{$key}");
			\Base::instance()->clear("SESSION.flash.{$key}");

		}else{

			$value=\Base::instance()->get("SESSION.flash");
			\Base::instance()->clear("SESSION.flash");

		}
				
		return $value;

	}

	public function exists($key){

		return \Base::instance()->exists("SESSION.flash.{$key}");

	}

}