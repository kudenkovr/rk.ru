<?php
error_reporting(E_ALL);
mb_internal_encoding('utf-8');
session_start();


function rk_autoloader($class_name) {
	if (class_exists('RK') && !is_null(RK::self())) {
		$parts = explode('\\', $class_name);
		$path_key = strtolower(array_shift($parts));
		$name = implode(RK::self()->alias('config.namesep'), $parts);
		if (method_exists(RK::self()->load, $path_key)) {
			RK::self()->load->$path_key($name);
		}
	}
	$file_path = __DIR__ . DIRECTORY_SEPARATOR . strtolower($class_name) . '.class.php';
	if (!file_exists($file_path)) return false;
	require_once($file_path);
	return true;
}
spl_autoload_register('rk_autoloader');


$rk = new RK;
		
$rk->path->set('base', $_SERVER['DOCUMENT_ROOT']);
$rk->path->set('core', __DIR__);


$rk->load->config('paths.php', 'path');
$rk->load->config('config.ini');
$rk->load->config('mysql.ini');
$rk->load->config('info.ini', 'data');

$rk->connectDB();