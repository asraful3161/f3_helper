<?php
namespace F3;

class Std extends \Magic{

    protected $data;

    public function __construct($data=[]){

    	$this->data=$data;

    }

    function exists($key) {
        return array_key_exists($key, $this->data);
    } 

    function set($key, $val) {
        $this->data[$key]=$val;
    }

    function &get($key){
        return $this->data[$key];
    }

    function clear($key){
        unset($this->data[$key]);
    }

    public function toCamelCaseArray($capitaliseFirstChar=TRUE){

        $result=[];

        foreach($this->data as $key=>$value){

            $key=str_replace('_', '', ucwords($key, '_'));
            if(!$capitaliseFirstChar) $key = lcfirst($key);
            $result[$key]=$value; 

        }

        return $result;

    }

}