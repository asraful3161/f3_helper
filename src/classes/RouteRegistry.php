<?php
namespace F3;

class RouteRegistry extends \Prefab{

	public function run(){

		if(\Base::instance()->CLI){

			$this->cli();

		}elseif(strrpos(\Base::instance()->PATH, 'api')!==1){

			$this->web();

		}else $this->api();
		
	}

	protected function web(){

	}

	protected function api(){

	}

	protected function cli(){

	}

}