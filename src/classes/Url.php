<?php
namespace F3;

class Url extends \Prefab{

	protected $f3;

	public function __construct(){
		$this->f3=\Base::instance();
	}

	public function to($url=''){
		$port='';
		if($this->f3->PORT) $port=':'.$this->f3->PORT;
		return $this->f3->SCHEME.'://'.$this->f3->HOST.$port.$this->f3->BASE.'/'.ltrim($url, '/');
	}

	/*
	public function self(){
		return $this->to($this->f3->URI);
	}
	*/

	public function current(){
		return $this->f3->REALM;
	}

}