<?php
//All helper functions for f3 microservices

function f3($key=NULL, $value=NULL){

	if($key && $value){

		return \Base::instance()->set($key, $value);

	}elseif($key){

		return \Base::instance()->get($key);

	}else return \Base::instance();
	
}

/*
function url($args=''){
	$f3=\Base::instance();
	$port='';
	if($f3->PORT) $port=':'.$f3->PORT;
	return $f3->SCHEME.'://'.$f3->HOST.$port.$f3->BASE.'/'.ltrim($args, '/');
	//return $f3->REALM.'/'.ltrim($args, '/');
}
*/

function url($url=NULL){

	if($url) return \F3\Url::instance()->to($url);
	return \F3\Url::instance();

}

function db(){
	return \F3\DB::instance()->get();
}

function mapper($table){
	return new \DB\SQL\Mapper(db(), $table);
}

function json($data=null, $response_code=200){
	if(isset($data['status_code'])) $response_code=$data['status_code'];
	if(empty($data['data'])) $data['data']=null;
	header('Content-Type: application/json', true, $response_code);
	//http_response_code($response_code);
	echo json_encode($data);
}

function res_json($msg='Empty', $status_code=200, $data=null){
	if($status_code==null) $status_code=200;
	$status='error';
	if($status_code==200 || $status_code==201 || $status_code==204) $status='success';
	header('Content-Type: application/json', true, $status_code);
	echo json_encode(['msg'=>$msg, 'data'=>$data, 'status_code'=>$status_code, 'status'=>$status]);
}

function verify_method($args=[]){
	if(!in_array(\Base::instance()->VERB, $args)) die(json(['msg'=>'method not allowed', 'status_code'=>405], 405));
}

function timestamp($timestamps=null, $format='Y-m-d H:i:s'){
	if($timestamps) return date($format, strtotime($timestamps));
	return date($format);
}

function matrix_exists($cell=null, $v=null, $full_rows=false){
	return \F3\DB::instance()->exists($cell, $v, $full_rows);
}

function matrix_uid($cell=null, $encrypt=false){
	$matrix=explode('.', $cell);
	if(count($matrix)!=2) return null;
	list($table, $field)=$matrix;
	$uid=uniqid(rand(33, 127));
	if($encrypt) $uid=md5(uniqid(rand(33, 127)));
	$result=db()->exec("SELECT `id` FROM `$table` WHERE `$field`='$uid' LIMIT 1");
	if(db()->count() > 0) matrix_uid($cell, $encrypt);
	return $uid;
}

function validator($args){

	\Valitron\Validator::addRule('unique', function($field, $value, array $params, array $fields){

		//die(pr($params));

		if(isset($params[1])){
			$result=current(matrix_exists($params[0].'.'.$field, $value, true));
			//die(pr($result));
			if($result && $result[$field]==$value && $result['id']==$params[1]) return true;
			elseif(!$result) return true;
			return false;
		}elseif(matrix_exists($params[0].'.'.$field, $value)) return false;
		return true;

	}, '{field} already exists in records');

	\Valitron\Validator::addRule('exists', function($field, $value, array $params, array $fields){

		if(strpos($params[0], '.')!==false) return matrix_exists($params[0], $value);
		return matrix_exists($params[0].'.'.$field, $value);		

	}, '{field} does not exists in records');

	return new Valitron\Validator($args);
	
}

function uid(){
	return uniqid(rand(33, 173));
}


function check_acl($permission_names){

	$f3=\Base::instance();
	$auth_token=$f3->get('HEADERS.Auth-Token');
	$option['method']='POST';
	$option['content']=http_build_query(['permission_names'=>$permission_names, 'referer'=>$f3->get('REALM')]);
	if($auth_token) $option['header']='Auth-Token:'.$auth_token;
	$acl_response=json_decode(\Web::instance()->request($f3->ACL_URL, $option)['body']);
	//die(var_dump(\Web::instance()->request($f3->ACL_URL, $option)['body']));
	if(isset($acl_response->status_code) && $acl_response->status_code==200) return true;
	die(res_json('user not authorised to perform this action', 401));

}

function view($filepath=NULL, $value=[], $cType='text/html', $ext='.html'){

	if($filepath) return \View::instance()->render($filepath.$ext, $cType, $value);
	return \View::instance();

}

function preview(){
	return \Preview::instance();
}

function tpl(){
	return \Template::instance();
}

function jig($dir=NULL){
	return \F3\Jig::instance($dir)->get();
}

function jig_mapper($file){
	return new \DB\Jig\Mapper(jig(), $file);
}

function fa($icon){
	return \F3\Html::instance()->fa($icon);
}

function medoo(){
	return \F3\DB::instance()->medoo();
}

function rv($msg=NULL, $status=FALSE, $data=NULL){ //full abbreviation return_value()

	return new \F3\Std([
		'msg'=>$msg,
		'status'=>$status,
		'data'=>$data
	]);

}

function twig($file=NULL, $data=[]){

	if($file) echo \F3\Twig::instance()->render($file, $data);
	else return \F3\Twig::instance();

}

function auth(){
	return \F3\DAuth::instance();
}

function flash($key=NULL, $value=NULL){

	if($key && $value) return \F3\Flash::instance()->set($key, $value);
	elseif($key) return \F3\Flash::instance()->get($key);
	return \F3\Flash::instance();

}

function redirect($url=NULL){
	if($url) return \F3\Redirect::instance()->to($url);
	return \F3\Redirect::instance(); 
}

function xSlash($string){
	return '/'.ltrim($string, '/');
}

function input($key=NULL){

	if($key) return \F3\Input::instance()->get($key);
	return \F3\Input::instance();

}

function middleware($key=NULL, $action=NULL){

	if($key && $action) return \F3\Middleware::instance()->set($key, $action);
	elseif($key) return \F3\Middleware::instance()->get($key);
	return \F3\Middleware::instance();

}

function email($args=NULL){

	if($args) return \F3\Mail::instance()->config($args);
	return \F3\Mail::instance();

}

function std($args){
	return new \F3\Std($args);
}

function dd($args){
	dump($args);
	die;
}

function bench(){
	return \F3\Benchmark::instance();
}

function config($key=NULL){
	if($key) return \Config::instance()->get($key);
	return \Config::instance();
}