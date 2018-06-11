<?php
namespace F3;

class Jig extends \Prefab{

	private $jig;
	
	public function __construct($dir=NULL){

		if(!$dir) $dir=\Base::instance()->DEFAULT_JIG_PATH;

		$this->jig=new \DB\Jig($dir);
	}

	public function get(){
		return $this->jig;
	}

}