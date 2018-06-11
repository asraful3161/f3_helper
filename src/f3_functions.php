<?php
//All helper functions for f3 microservices

function f3($key=NULL, $value=NULL){

	if($key && $value){

		return \Base::instance()->set($key, $value);

	}elseif($key){

		return \Base::instance()->get($key);

	}else return \Base::instance();
	
}

function url($args=''){
	$f3=\Base::instance();
	$port='';
	if($f3->PORT) $port=':'.$f3->PORT;
	return $f3->SCHEME.'://'.$f3->HOST.$port.$f3->BASE.'/'.ltrim($args, '/');
	//return $f3->REALM.'/'.ltrim($args, '/');
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

function matrix_exists($cell=null, $value=null, $full_rows=false){
	$matrix=explode('.', $cell);
	if(count($matrix)!=2) return null;
	list($table, $field)=$matrix;
	$result=db()->exec("SELECT * FROM `$table` WHERE `$field`='$value'");
	if($full_rows) return $result;
	return db()->count();
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

	Valitron\Validator::addRule('unique', function($field, $value, array $params, array $fields){

		//die(pr($params));

		if(isset($params[1])){
			$result=current(matrix_exists($params[0].'.'.$field, $value, true));
			//die(pr($result));
			if($result && $result[$field]==$value && $result['id']==$params[1]) return true;
			elseif(!$result) return true;
			return false;
		}elseif(matrix_exists($params[0].'.'.$field, $value)) return false;
		return true;

	}, 'Sorry!, {field} already exists in records');

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

function dd($var, $pretty = true){
	
    $backtrace = debug_backtrace();
    echo "\n<pre>\n";
    if (isset($backtrace[0]['file'])) {
        echo $backtrace[0]['file'] . "\n\n";
    }
    echo "Type: " . gettype($var) . "\n";
    echo "Time: " . date('c') . "\n";
    echo "---------------------------------\n\n";
    ($pretty) ? print_r($var) : var_dump($var);
    echo "</pre>\n";
    die;
}

function view(){
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

function unpkg($pkg=NULL){

	$url="https://unpkg.com";

	if(is_string($pkg)){

		if(preg_match("/\.css$/", $pkg)) return "<link rel='stylesheet' type='text/css' href='{$url}/{$pkg}'>";
		elseif(preg_match("/\.js$/", $pkg)) return "<script src='{$url}/{$pkg}'></script>";

	}elseif(is_array($pkg)){

		$html='';

		foreach($pkg as $row){

			if(preg_match("/\.css$/", $row)) $html.="<link rel='stylesheet' type='text/css' href='{$url}/{$row}'>";
			elseif(preg_match("/\.js$/", $row)) $html.="<script src='{$url}/{$row}'></script>";

		}

		return $html;

	}

}

function fa($icon){
	return "<i class='fa {$icon}' aria-hidden='true'></i>";
}

function mdb(){
	return \F3\MedooDb::instance();
}