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

		$this->twig->addFunction(new \Twig_Function('jsdelivr', function($cdn=NULL, $type='npm'){

			return jsdelivr($cdn, $type);

		}));

		$this->twig->addFunction(new \Twig_Function('url', function($args=''){

			return url($args);

		}));

		$this->twig->addFunction(new \Twig_Function('cdn', function($url){

			return cdn($url);

		}));

		$this->twig->addFunction(new \Twig_Function('fa', function($icon){

			return fa($icon);

		}));

	}

	public function render($file, $data=[]){

		echo $this->twig->render($file, $data);

	}

}