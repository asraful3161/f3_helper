<?php
namespace F3;

class StrOps extends \Prefab{

	protected $data;

	public function get(string $data){

		$this->data=$this->splitByUpper($data);
		return $this;

	}

	public function splitByUpper($str, $delimiter='|'){

		/*
		$str=lcfirst($str);
        $lowerCase=strtolower($str);
        $result='';
        $length=strlen($str);
        for($i=0; $i < $length; $i++){
            $result.=($str[$i]===$lowerCase[$i] ? '' : $delimiter).$lowerCase[$i];
        }
        return $result;
        */

        return strtolower(preg_replace("/([A-Z])/","{$delimiter}$1", lcfirst($str)));

	}

	public function toCamel(){
		return str_replace(['-', '|', ' ', '_'], '', ucwords($this->data, '-|_ '));
	}

	public function toLowerCamel(){
		return lcfirst($this->toCamel());
	}

	public function toSnake(){
		return str_replace(['-', '|', ' '], '_', $this->data);
	}

	public function toTitle(){
		return str_replace(['-', '|', '_'], ' ', ucwords($this->data, '-|_ '));
	}

	public function toSlug(){
		return str_replace(['|', ' ', '_'], '-', $this->data);
	}

}