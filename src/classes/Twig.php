<?php

namespace F3;

class Twig  extends \Prefab{

	protected $twig;

	public function __construct(){

		$f3=\Base::instance();
		
		$this->twig=new \Twig_Environment(
			new \Twig_Loader_Filesystem($f3->get('UI')),
			['cache'=>'tmp/cache', 'debug'=>$f3->get('TWIG_DEBUG')]
		);

		$this->twig->addFunction(new \Twig_Function('jsdelivr', function($cdn=NULL, $type='npm'){

			return jsdelivr($cdn, $type);

		}));

		$this->twig->addFunction(new \Twig_Function('url', function($url=NULL){

			return url($url);

		}));

		$this->twig->addFunction(new \Twig_Function('cdn', function($url){

			return cdn($url);

		}));

		$this->twig->addFunction(new \Twig_Function('fa', function($icon){

			return fa($icon);

		}));

		$this->twig->addFunction(new \Twig_Function('f3', function(){

			return \Base::instance();

		}));

		$this->twig->addGlobal('f3', \Base::instance());
		$this->twig->addGlobal('url', \F3\Url::instance());
		$this->twig->addGlobal('flash', \F3\Flash::instance());
		$this->twig->addGlobal('input', \F3\Input::instance());
		$this->twig->addGlobal('error', \F3\Input::instance()->error());
		$this->twig->addGlobal('auth', \F3\DAuth::instance());

	}

	public function render($file, $data=[]){
		return $this->twig->render($file, $data);
	}

	public function get(){
		return $this->twig;
	}

}