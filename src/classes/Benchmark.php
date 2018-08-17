<?php
namespace F3;
class Benchmark extends \Prefab{
	protected $mt;
	public function start(){
		$this->mt=microtime(TRUE);
	}
	public function end(){
		die(round(1e3*(microtime(TRUE)-$this->mt), 2).' (msecs)');
	}
}