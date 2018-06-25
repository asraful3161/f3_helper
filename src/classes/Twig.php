<?php

namespace F3;

class Twig  extends \Prefab{

	protected $loader, $twig;

	public function __construct(){

		$f3=\Base::instance();
		$this->loader=new \Twig_Loader_Filesystem($f3->get('UI'));
		$this->twig=new \Twig_Environment($this->loader, [
			'cache'=>'tmp/cache',
			'debug'=>$f3->get('TWIG_DEBUG')
		]);

	}

	public function render($file, $data=[]){

		echo $this->twig->render($file, $data);

	}

}