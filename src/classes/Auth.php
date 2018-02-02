<?php
namespace F3_helper;

class Auth extends \Prefab{

	private $auth;
	
	public function user(){

	}

	public function signIn($indentifier, $password){

	}

	public function signOut(){

	}

	public function signUp($args=[]){

		$attr=[
			'name'=>NULL,
			'username'=>NULL,
			'email'=>NULL,
			'password'=>NULL,
		];

		$attr=(Object) array_merge($attr, $args);

		foreach($attr as $row){
			if(!$row) return FALSE;
		}

		$result=db()->exec("
			INSERT INTO `users`(`name`, `username`, `email`, `password`, `status`, 'email_verified')
			VALUES('$attr->name','$attr->username', '$attr->email', '$attr->password', '0', '0')
		");

		if($result) return TRUE;
		return FALSE;

	}

	public function check(){

		if($auth) return TRUE;
		return FALSE;

	}

}