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

	public function asset($url=''){
		return $this->to('assets/'.ltrim($url, '/'));
	}

	public function previous(){
		return $this->f3->get('HEADERS.Referer');
	}

	public function intended($url=NULL){

		if($url) \F3\Flash::instance()->set('urls.intended', $url);
		else return \F3\Flash::instance()->get('urls.intended');

	}

	public function cdn($url){

		if(preg_match("/\.css$/", $url)) return "<link rel='stylesheet' type='text/css' href='{$url}'>";
		elseif(preg_match("/\.js$/", $url)) return "<script src='{$url}'></script>";

	}

	public function unpkg($pkg=NULL){

		$url="https://unpkg.com";
		if(is_string($pkg)) return $this->cdn($url.'/'.$pkg);
		elseif(is_array($pkg)){
			$html='';
			foreach($pkg as $row) $html.=$this->cdn($url.'/'.$row);
			return $html;
		}

	}

	public function jsdelivr($cdn=NULL, $type='npm'){

		$url="https://cdn.jsdelivr.net";
		if(is_string($cdn)) return $this->cdn($url.'/'.$type.'/'.$cdn);
		elseif(is_array($cdn)){
			$url.="/combine/{$type}/".implode(",{$type}/", $cdn);
			return $this->cdn($url);
		}

	}

	public function active($url){
		if(is_string($url) && $this->f3->PATH=='/'.ltrim($url, '/')) return 'active';
		elseif(is_array($url)){

			$path=$this->f3->PATH;

			foreach($url as $row){
				if($path=='/'.ltrim($row, '/')) return 'active';
			}

		} return '';
	}

	public function isApi(){

		if(strrpos($this->f3->PATH, 'api')===1) return TRUE;
		return FALSE;

	}

	public function path(){
		return $this->f3->PATH;
	}

}