<?php
namespace F3;
use \Delight\Auth\Auth;
use \F3\Std;

class DAuth extends \Prefab{

	protected
		$auth,
		$userId,
		$rememberDuration=30,
		$verified;

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
		else return NULL;

	}

	public function register(Std $args){

		try{

		    $this->userId=$this->auth->register(
		    	$args->email,
		    	$args->password,
		    	$args->username,
		    	function($selector, $token) use($args){

		        	// send `$selector` and `$token` to the user (e.g. via email)
		        	$smtp=new \SMTP(
		        		'smtp.mailtrap.io',
		        		2525,
		        		'PLAIN',
		        		'9771ea9c359571',
		        		'e7161257b39e93'
		        	);

					$smtp->set('From', 'asraful3161@gmail.com');
					$smtp->set('To', $args->email);
					$smtp->set('Subject', 'Email verification link');
					$smtp->send(url("auth/verify_email?selector={$selector}&token={$token}"));

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

	public function login(Std $args){

		try {

		    $this->auth->login($args->email, $args->password, $this->remember($args->remember));

		    // user is logged in
		    $intendedUrl=\F3\Url::instance()->intended();
		    if($intendedUrl) \F3\Redirect::instance()->toUrl($intendedUrl);

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

	public function confirmEmail(Std $args){

		try{

		    $this->auth->confirmEmail($args->selector, $args->token);

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

	public function verify($role=NULL, $permission=NULL){
		$this->verified=$this->auth->check();
		return $this;
	}

	public function execute($ifSuccess=NULL, $ifFail=NULL){

		if($this->verified){

			if(is_callable($ifSuccess)) return call_user_func($ifSuccess);
			return TRUE;

		}else{

			$url=\F3\Url::instance();
			$url->intended($url->current());
			
			if(is_callable($ifFail)) return call_user_func($ifFail);
			return FALSE;

		}

	}

	public function user(){
		return $this->auth;
	}

	public function check(){

		return $this->auth->check();

	}

	public function guest(){

		return $this->auth->check()?FALSE:TRUE;

	}

}