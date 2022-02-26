<?php
include 'core/init.php';

// $rk->router->routeFile('routes.php');
echo $rk->get('data.test');

$rk->log('Complete. Executing time: ' . (time()-$start_time) . 'ms');
echo $rk->getJSLog();