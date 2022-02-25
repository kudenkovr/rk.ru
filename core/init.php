<?php
error_reporting(E_ALL);
mb_internal_encoding('utf-8');
session_start();

$start_time = time();


function rk_autoloader($class_name) {
	$file_path = __DIR__ . DIRECTORY_SEPARATOR . strtolower($class_name) . '.class.php';
	if (!file_exists($file_path)) return false;
	require_once($file_path);
	return true;
}
spl_autoload_register('rk_autoloader');


$rk = new RK;
		
$rk->path->set('base', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
$rk->path->set('core', __DIR__);
$rk->path->set('config', $rk->path->core . 'config/');

$rk->loadConfig('config_paths.php', 'path');
$rk->loadConfig('config.ini'); // 'config' is default property
$rk->loadConfig('mysql.ini'); // 'config' is default property
$rk->loadConfig('info.ini', 'data');

$rk->connectDB();