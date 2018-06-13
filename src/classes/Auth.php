<?php
namespace F3;

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
			'password'=>NULL
		];

		$attr=array_merge($attr, $args);

		foreach($attr as $row){

			if(!$row) return [
				'msg'=>"{$row} field not provided.",
				'status'=>FALSE
			];

		}

		$attr['password']=\Bcrypt::instance()->hash($attr['password']);

		/*

		$result=db()->exec("
			INSERT INTO `users`(`name`, `username`, `email`, `password`)
			VALUES('$attr->name','$attr->username', '$attr->email', '$attr->password')
		");

		*/

		$result=medoo()->insert('users', $attr);

		if($result){

			dd($result);

			return [
				'msg'=>"New user created successfully!.",
				'status'=>TRUE
			];
		}

		return [
			'msg'=>"Sorry!, unable to save user in database.",
			'status'=>FALSE
		];

	}

	public function check(){

		if($auth) return TRUE;
		return FALSE;

	}

}