<?php
namespace F3;

abstract class Registry extends \Prefab{

	public function run(){
		$this->register();
	}

	abstract protected function register();

}