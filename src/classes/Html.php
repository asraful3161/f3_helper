<?php
namespace F3;

class Html extends \Prefab{

	public function fa($icon){
		return "<i class='fa {$icon}' aria-hidden='true'></i>";
	}

	public function csrf_field(){
		return "<input type='hidden' name='csrf_token' value=''>";
	}

}