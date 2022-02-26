<?php
include 'core/init.php';

$rk->router->routeFile('routes.php');

$rk->log('Complete. Executing time: ' . (time()-$start_time) . 'ms');
echo $rk->getJSLog();