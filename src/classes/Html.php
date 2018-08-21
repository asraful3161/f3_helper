<?php
namespace F3;

class Html extends \Prefab{

	public function fa($icon){
		return "<i class='fa {$icon}' aria-hidden='true'></i>";
	}

	public function csrf_field(){
		
		$f3=\Base::instance();
		new \Session(NULL,'CSRF');
		$f3->copy('CSRF','SESSION.csrf');
		return "<input type='hidden' name='csrf_token' value='{$f3->CSRF}'/>";

	}

}