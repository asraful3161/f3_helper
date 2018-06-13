<?php
namespace F3;

use Medoo\Medoo;

class DB extends \Prefab{

	private $db, $medoo;
	
	public function __construct(){
		$f3=\Base::instance();
		if($f3->DB_DRIVER=='mysql') $this->db= new \DB\SQL("mysql:host={$f3->get('DB_HOST')};port={$f3->get('DB_PORT')};dbname={$f3->get('DB_NAME')}", $f3->get('DB_USER'), $f3->get('DB_PASS'));
		elseif($f3->DB_DRIVER=='sqlite') $this->db= new \DB\SQL("sqlite:{$f3->get('SQLITE_PATH')}");

		$this->medoo=new Medoo([

			// required
			'database_type'=>$f3->get('DB_DRIVER'),
			'database_name'=>$f3->get('DB_NAME'),
			'server'=>$f3->get('DB_HOST'),
			'username'=>$f3->get('DB_USER'),
			'password'=>$f3->get('DB_PASS'),
		 
			// [optional]
			'charset' => 'utf8',
			'port' => $f3->get('DB_PORT'),
		 
			// [optional] Table prefix
			'prefix' => $f3->get('DB_TABLE_PREFIX'),
		 
			// [optional] Enable logging (Logging is disabled by default for better performance)
			//'logging' => true,
		 
			// [optional] MySQL socket (shouldn't be used with server and port)
			//'socket' => '/tmp/mysql.sock',
		 
			// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php

			/*
			'option' => [
				PDO::ATTR_CASE => PDO::CASE_NATURAL
			],
			*/
		 
			// [optional] Medoo will execute those commands after connected to the database for initialization

			/*
			'command' => [
				'SET SQL_MODE=ANSI_QUOTES'
			]
			*/

		]);

	}

	public function get(){
		return $this->db;
	}

	public function medoo(){
		return $this->medoo;
	}

}