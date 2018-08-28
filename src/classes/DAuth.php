<?php
namespace F3;
use \Delight\Auth\Auth;
use \Delight\Auth\Role;
use \F3\Std;

class DAuth extends \Prefab{

	protected
		$auth,
		$userId,
		$rememberDuration=30,
		$verified;

	public function __construct(){

		$f3=\Base::instance();
		$this->auth=new Auth(\F3\DB::instance()->medoo()->pdo);

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

		        	email(new Std([
		        		'to'=>$args->email,
		        		'subject'=>'Email verification link',
		        		'message'=>url("auth/verify_email?selector={$selector}&token={$token}")
		        	]))->send();

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

	public function loginWithUsername(Std $args){

		try {

		    $this->auth->loginWithUsername(
		    	$args->email,
		    	$args->password,
		    	$this->remember($args->remember)
		    );

		    // user is logged in
		    $intendedUrl=\F3\Url::instance()->intended();
		    if($intendedUrl) \F3\Redirect::instance()->toUrl($intendedUrl);

		    return rv('User is logged in successfully!.', TRUE);

		}catch(\Delight\Auth\InvalidPasswordException $e){

		    // wrong password
		    return rv('Sorry!, wrong password.');

		}catch(\Delight\Auth\EmailNotVerifiedException $e){

		    // email not verified
		    return rv('Sorry!, email not verified.');

		}catch(\Delight\Auth\TooManyRequestsException $e){

		    // too many requests
		    return rv('Sorry!, too many requests.');

		}catch(\Delight\Auth\UnknownUsernameException $e){

			return rv('Sorry!, inserted username does not exists.');

		}catch(\Delight\Auth\AmbiguousUsernameException $e){

			return rv('Sorry!, invalid username.');

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

	public function resendConfirmationEmail($email){

		try{

		    $this->auth->resendConfirmationForEmail($email, function($selector, $token){

	        	email(new Std([
	        		'to'=>$email,
	        		'subject'=>'Email verification link',
	        		'message'=>url("auth/verify_email?selector={$selector}&token={$token}")
	        	]))->send();
	        	
		    });

		}catch(\Delight\Auth\ConfirmationRequestNotFound $e){

			return rv('Sorry!, no earlier request found that could be re-sent.');

		}catch(\Delight\Auth\TooManyRequestsException $e){

			return rv('There have been too many requests, try again later.');

		}

	}

	public function forgetPassword(Std $args){

		try{

		    $this->auth->forgotPassword($args->email, function ($selector, $token) use($args){

		        // send `$selector` and `$token` to the user (e.g. via email)
		        email(new Std([
		        	'to'=>$args->email,
		        	'subject'=>'Password reset link.',
		        	'message'=>url("auth/reset?selector={$selector}&token={$token}")
		        ]))->send();

		    });

		    return rv('Success!, a password reset link send to your email inbox.', TRUE);

		}catch(\Delight\Auth\InvalidEmailException $e){

		    // invalid email address
		    return rv('Sorry!, invalid email address');

		}catch(\Delight\Auth\EmailNotVerifiedException $e){

		    // email not verified
		    return rv('Sorry!, email not verified');

		}catch(\Delight\Auth\ResetDisabledException $e){

		    // password reset is disabled
		    return rv('Sorry!, password reset is disabled');

		}catch(\Delight\Auth\TooManyRequestsException $e){

		    // too many requests
		    return rv('Sorry!, too many requests');

		}

	}

	public function user(){
		return $this->auth;
	}

	public function id(){
		return $this->auth->getUserId();
	}

	public function guest(){

		return $this->auth->check()?FALSE:TRUE;

	}

	public function logOut(){

		$this->auth->logOut();
		$this->auth->destroySession();

	}

	/*
	public function roleV($roles){
		$roleMap=Role::getMap();
		if(is_string($roles)) return array_search($roles, $roleMap);
		elseif(is_array($roles)){
			$roleV=[];
			foreach($roles as $role){
				if($value=array_search($role, $roleMap)) array_push($roleV, $value);
			}
			return $roleV;
		}
	}
	*/

	public function check($role=NULL){

		if($role && $this->auth->check()) return $this->hasRole($role);
		return $this->auth->check();
		
	}

	
	public function verify(){

		$this->verified=FALSE;
		$this->verified=$this->auth->check();
		return $this;

	}

	public function byRole($name){
		$this->verified=FALSE;
		if($this->hasRole($name)) $this->verified=TRUE;
		return $this;
	}

	public function byPermit($names){
		$this->verified=FALSE;
		if($this->hasPermit($names)) $this->verified=TRUE;
		return $this;
	}

	public function byAnyPermit($names){
		$this->verified=FALSE;
		if($this->hasAnyPermit($names)) $this->verified=TRUE;
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

	public function getRoleName(){

		$userId=$this->auth->getUserId();

		return medoo()->get('role',
		[//Joining
			"[><]users"=>['id'=>'role_id']
		],//Select column
		'name',
		[//Conditions
			'users.id'=>$userId
		]);

	}

	public function hasRole($name){

		$userId=$this->auth->getUserId();

		return medoo()->has('users',
		[//Joining
			"[><]role"=>['role_id'=>'id']
		],[//Conditions
			'users.id'=>$userId,
			'role.name'=>$name
		]);

	}

	protected function checkPermits(array $names){

		$userId=$this->auth->getUserId();

		return medoo()->count('permit',[//Joining
			'[><]role_permit'=>['id'=>'permit_id'],
			'[><]role'=>['role_permit.role_id'=>'id'],
			'[><]users'=>['role.id'=>'role_id']
		],//Columns
		'permit.name',
		[//conditions
			'users.id'=>$userId,
			'permit.name'=>$names
		]);

	}

	public function getPermits(){

		$userId=$this->auth->getUserId();

		return medoo()->select('permit',[//Joining
			'[><]role_permit'=>['id'=>'permit_id'],
			'[><]role'=>['role_permit.role_id'=>'id'],
			'[><]users'=>['role.id'=>'role_id']
		],//Columns
		'permit.name',
		[//conditions
			'users.id'=>$userId
		]);

	}

	public function hasAnyPermit($names){
		$result=$this->checkPermits($names);
		return count($result) > 0;
	}

	public function hasPermit($names){
		$result=$this->checkPermits($names);
		return count($result)==count($names);
	}

	/*
	public function hasRole($role){

		if($this->auth->hasRole($this->roleV($role))) $this->verified=TRUE;
		else $this->verified=FALSE;
		return $this;

	}

	public function hasAnyRole($roles){

		if(call_user_func_array([$this->auth, "hasAnyRole"], $this->roleV($roles))) $this->verified=TRUE;
		else $this->verified=FALSE;
		return $this;

	}

	public function hasRoles($roles){

		if(call_user_func_array([$this->auth, "hasAllRoles"], $this->roleV($roles))) $this->verified=TRUE;
		else $this->verified=FALSE;
		return $this;

	}
	*/

}