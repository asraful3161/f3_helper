<?php
namespace F3;
use \Delight\Auth\Auth;

class DAuth extends \Prefab{

	protected $auth, $userId, $rememberDuration=30;

	public function __construct(){

		$f3=\Base::instance();

		$this->auth=new Auth(new \PDO(
			"mysql:dbname={$f3->get('DB_NAME')};host={$f3->get('DB_HOST')};port={$f3->get('DB_PORT')};charset=utf8mb4",
			$f3->get('DB_USER'),
			$f3->get('DB_PASS')
		));

	}

	protected function remember($flag=FALSE){

		if($flag) return (int) (60 * 60 * 24 * $this->$rememberDuration); //in days
		else return NULL

	}

	public function register($email, $password, $username){

		try{

		    $this->userId=$this->auth->register(
		    	$email,
		    	$password,
		    	$username,
		    	function ($selector, $token){

		        	// send `$selector` and `$token` to the user (e.g. via email)

		    	}
			);

		    // we have signed up a new user with the ID `$userId`
		    return rv('New user created successfully!.', TRUE, ['userId'=>$this->userId]);

		}catch (\Delight\Auth\InvalidEmailException $e){

			// invalid email address
		    return rv('Sorry!, invalid email address');

		}catch (\Delight\Auth\InvalidPasswordException $e){

		    // invalid password
		    return rv('Sorry!, invalid email password');

		}catch (\Delight\Auth\UserAlreadyExistsException $e){

		    // user already exists
		    return rv('Sorry!, user already exists');

		}catch (\Delight\Auth\TooManyRequestsException $e){

		    // too many requests
		    return rv('Sorry!, too many requests');

		}

	}

	public function login($email, $password, $remember=NULL){

		try {

		    $this->auth->login($email, $password, $this->remember($remember));

		    // user is logged in
		    return rv('User is logged in successfully!.', TRUE);

		}catch(\Delight\Auth\InvalidEmailException $e){

		    // wrong email address
		    return rv('Sorry!, wrong email address');

		}catch(\Delight\Auth\InvalidPasswordException $e){

		    // wrong password
		    return rv('Sorry!, wrong password');

		}catch(\Delight\Auth\EmailNotVerifiedException $e){

		    // email not verified
		    return rv('Sorry!, email not verified');

		}catch(\Delight\Auth\TooManyRequestsException $e){

		    // too many requests
		    return rv('Sorry!, too many requests');

		}

	}

	public function confirmEmail(){

		try{

		    $this->auth->confirmEmail($selector, $token);

		    // email address has been verified
		    return rv('Email address has been verified successfully!.', TRUE);

		}catch(\Delight\Auth\InvalidSelectorTokenPairException $e){

		    // invalid token
		    return rv('Sorry!, invalid token');

		}catch(\Delight\Auth\TokenExpiredException $e){

		    // token expired
		    return rv('Sorry!, token expired');

		}catch(\Delight\Auth\UserAlreadyExistsException $e){

		    // email address already exists
		    return rv('Sorry!, email address already exists');

		}catch(\Delight\Auth\TooManyRequestsException $e){

		    // too many requests
		    return rv('Sorry!, too many requests');

		}

	}

	public function logOut(){

		$this->auth->logOut();
		$this->auth->destroySession();

	}

	public function user(){
		return $this->auth;
	}

}