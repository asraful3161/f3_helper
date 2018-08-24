<?php
namespace F3;

class Input extends \Prefab{

	protected $data, $errorFlag;

	public function __construct(){

		$this->data=array_merge($_POST, $_GET);

	}

    public function all(){
        return $this->data;
    }

	public function only($keys=[]){

		$rv=[];

		foreach($keys as $key){

			if(isset($this->data[$key])) $rv[$key]=$this->data[$key];
			else $rv[$key]=NULL;
			
		}

		return $rv;

	}

    public function exists($key){

        if($this->errorFlag) return \F3\Flash::instance()->exists("errors.$key");
        return array_key_exists($key, $this->data);

    }

    public function set($key, $val) {
        $this->data[$key] = $val;
    }

    public function get($key, $default=NULL){
        if(empty($this->data[$key])) return $default;
        return $this->data[$key];
    }

    public function clear($key) {
        unset($this->data[$key]);
    }

    public function old($key){
    	return \F3\Flash::instance()->get("inputs.$key");
    }

    public function error(){

    	$this->errorFlag=TRUE;
    	return $this;
    	
    }

    public function msg($key){
    	$errors=\F3\Flash::instance()->get("errors.$key");
    	if(is_array($errors)) return current($errors);
    	return NULL;
    }

    public function args(){

        return \Base::instance()->get('PARAMS.args');

    }

    public function forModel(array $keys=[]){

        $rv=[];
        $str=\F3\StrOps::instance();

        if($keys){
            foreach($keys as $key){
                $camelKey=$str->get($key)->toCamel();
                $rv[$camelKey]=isset($this->data[$key])?$this->data[$key]:NULL;          
            }
        }else{
            foreach($this->data as $key=>$value){
                $camelKey=$str->get($key)->toCamel();
                $rv[$camelKey]=$value;
            }
        }
        
        return $rv;

    }

}