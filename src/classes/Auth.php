<?php
namespace F3;

class Auth extends \Prefab{

	private $auth, $sess_id; //sess_id is session id as a alias to create a random hash on instantiation.
	
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

		foreach($attr as $key=>$row){

			if(!$row) return rv("{$key} field not provided.", FALSE);

		}

		$attr['password']=\Bcrypt::instance()->hash($attr['password']);

		medoo()->insert('users', $attr);

		if(medoo()->id()){

			$signup_id=medoo()->id();

			$user=medoo()->get('users', ['id', 'name','email'], ['id'=>$signup_id]);

			f3('SESSION.auth', [
				'status'=>TRUE,
				'user'=>$user
			]);

			return rv(
				"New user created successfully!.",
				TRUE,
				['user'=>$user]
			);

		}

		return rv("Sorry!, unable to save user in database.", FALSE);

	}

	public function check(){

		if($auth) return TRUE;
		return FALSE;

	}

}