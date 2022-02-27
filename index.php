<?php
include 'core/init.php';

if ($rk->router->routeFile('routes.php')) {
	$rk->output();
}