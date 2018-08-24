<?php
namespace F3;

use Medoo\Medoo;

class DB extends \Prefab{

	private $db, $medoo, $dsn;
	
	public function __construct(){

		$f3=\Base::instance();

		if($f3->DB_DRIVER=='mysql'){

			$this->dsn="mysql:host={$f3->DB_HOST};port={$f3->DB_PORT};dbname={$f3->DB_NAME}";
			$this->db=new \DB\SQL($this->dsn, $f3->DB_USER, $f3->DB_PASS);

		}elseif($f3->DB_DRIVER=='sqlite'){

			$this->dsn="sqlite:{$f3->get('SQLITE_PATH')}";
			$this->db=new \DB\SQL($this->dsn);

		}

		$this->medoo=new Medoo([

			// required
			'database_type'=>$f3->DB_DRIVER,
			'database_name'=>$f3->DB_NAME,
			'server'=>$f3->DB_HOST,
			'username'=>$f3->DB_USER,
			'password'=>$f3->DB_PASS,
		 
			// [optional]
			'charset' => 'utf8',
			'port' => $f3->DB_PORT,
		 
			// [optional] Table prefix
			'prefix' => $f3->DB_TABLE_PREFIX,
		 
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

		$f3=\Base::instance();

		$container=\Propel\Runtime\Propel::getServiceContainer();
		$container->checkVersion('2.0.0-dev');
		$container->setAdapterClass('default', 'mysql');
		$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();

		$manager->setConfiguration([

			'dsn' => $this->dsn,
		 	'user' => $f3->DB_USER,
		 	'password' => $f3->DB_PASS,
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