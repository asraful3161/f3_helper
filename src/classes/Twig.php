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

			$url="https://cdn.jsdelivr.net";
			if(is_string($cdn)) return cdn($url.'/'.$type.'/'.$cdn);
			elseif(is_array($cdn)){
				$url.="/combine/{$type}/".implode(",{$type}/", $cdn);
				return cdn($url);
			}

		}));

	}

	public function render($file, $data=[]){

		echo $this->twig->render($file, $data);

	}

}