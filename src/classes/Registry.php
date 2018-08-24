<?php
namespace F3;

abstract class Registry extends \Prefab{

	public function __construct(){
		
		$this->register();

	}

	abstract protected function register();

}