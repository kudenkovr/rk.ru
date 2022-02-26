<?php
$_[] = [
	'rule' => '/',
	'action' => 'page/index',
];
$_[] = [
	'rule' => 'blog',
	'action' => 'page/blog',
];
$_[] = [
	'rule' => 'blog/.+',
	'action' => 'page/blog',
];
$_[] = [
	'rule' => '[^/].+',
	'action' => 'page/index',
];
$_[] = [
	'rule' => '.*',
	'action' => 'page/404',
];