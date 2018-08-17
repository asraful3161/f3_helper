<?php
namespace F3;

use Medoo\Medoo;

class DB extends \Prefab{

	private $db, $medoo;
	
	public function __construct(){

		$cfg=\Config::instance();

		if($cfg->get('db.driver')=='mysql'){

			$this->db= new \DB\SQL("mysql:host={$cfg->get('db.host')};port={$cfg->get('db.port')};dbname={$cfg->get('db.name')}", $cfg->get('db.user'), $cfg->get('db.password'));

		}elseif($cfg->get('db.driver')=='sqlite'){
			$this->db= new \DB\SQL("sqlite:{$cfg->get('db.sqlite_path')}");
		}

		$this->medoo=new Medoo([

			// required
			'database_type'=>$cfg->get('db.driver'),
			'database_name'=>$cfg->get('db.name'),
			'server'=>$cfg->get('db.host'),
			'username'=>$cfg->get('db.user'),
			'password'=>$cfg->get('db.password'),
		 
			// [optional]
			'charset' => 'utf8',
			'port' => $cfg->get('db.port'),
		 
			// [optional] Table prefix
			'prefix' => $cfg->get('db.table_prefix'),
		 
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

	public function exists($matrix, $v, $fullRowFlag=FALSE){

		$matrix=explode('.', $matrix);
		if(count($matrix)!=2) return NULL;
		list($t, $f)=$matrix; //$t for table, $f for field
		$result=$this->db->exec("SELECT * FROM `$t` WHERE `$f`='$v'");
		if($fullRowFlag) return $result;
		return $this->db->count();

	}

	public function runPropel(){

		$cfg=\Config::instance();

		$container=\Propel\Runtime\Propel::getServiceContainer();
		$container->checkVersion('2.0.0-dev');
		$container->setAdapterClass('default', 'mysql');
		$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();

		$manager->setConfiguration([

			'dsn' => "mysql:host={$cfg->get('db.host')};port={$cfg->get('db.port')};dbname={$cfg->get('db.name')}",
		 	'user' => $cfg->get('db.user'),
		 	'password' => $cfg->get('db.password'),
			'settings' =>[
		   		'charset' => 'utf8',
		    	'queries' =>[]
		  	],

		  'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
		  'model_paths' =>[0 => 'src', 1 => 'vendor']

		]);

		$manager->setName('default');
		$container->setConnectionManager('default', $manager);
		$container->setDefaultDatasource('default');
		
	}

}