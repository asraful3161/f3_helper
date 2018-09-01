<?php
namespace F3;

class Html extends \Prefab{

	public function fa($icon){
		return "<i class='fa {$icon}' aria-hidden='true'></i>";
	}

	public function titleCase($str){
		if(is_string($str)) return \F3\StrOps::instance()->get($str)->toTitle();
		return NULL;
	}

}