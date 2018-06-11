<?php
namespace F3;

class DB extends \Prefab{

	private $db;
	
	public function __construct(){
		$f3=\Base::instance();
		if($f3->DEFAULT_DB_DRIVER=='mysql') $this->db= new \DB\SQL("mysql:host={$f3->get('DB_HOST')};port={$f3->get('DB_PORT')};dbname={$f3->get('DB_NAME')}", $f3->get('DB_USER'), $f3->get('DB_PASS'));
		elseif($f3->DEFAULT_DB_DRIVER=='sqlite') $this->db= new \DB\SQL("sqlite:{$f3->get('SQLITE_PATH')}");
	}

	public function get(){
		return $this->db;
	}

}