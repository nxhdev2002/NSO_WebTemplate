<?php
require_once "vendor/autoload.php";
date_default_timezone_set('Asia/Bangkok');


use \Simplon\Mysql\PDOConnector;
use \Simplon\Mysql\Mysql;
$dotenv = Dotenv\Dotenv::createImmutable(getcwd());
$dotenv->load();
$pdo = new PDOConnector(
	$_ENV['database_server'],
	$_ENV['database_username'],
	$_ENV['database_password'],
	$_ENV['database_dbname']
);

$pdoConn = $pdo->connect('utf8', []); // charset, options
$dbConn = new Mysql($pdoConn);

$_URL = $_ENV['web_url'];
$_DESCRIPTION = "HVN Comeback";

