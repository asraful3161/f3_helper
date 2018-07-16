<?php
namespace F3;

class Std extends \Magic{

    //$k means key, $v means value, $d means data

    protected $data;

    public function __construct($d=[]){

    	$this->data=$d;

    }

    function exists($k){
        return array_key_exists($k, $this->data);
    } 

    function set($k, $v){
        $this->data[$k]=$v;
    }

    function &get($k){
        return $this->data[$k];
    }

    function clear($k){
        unset($this->data[$k]);
    }

    public function toCamelCaseArray($capitaliseFirstChar=TRUE){

        $result=[];

        foreach($this->data as $k=>$v){

            $k=str_replace('_', '', ucwords($k, '_'));
            if(!$capitaliseFirstChar) $k = lcfirst($k);
            $result[$k]=$v;

        }

        return $result;

    }

    public function kv($k, $v){

        $result=[];

        foreach($this->data as $row){
            $result[$row[$k]]=$row[$v];
        }

        return $result;

    }

    public function chunk($v){
        return array_chunk($this->data, $v);
    }

    public function sum(){
        return array_sum($this->data);
    }

}