<?php
include 'core/init.php';

$rk->run('page');
$rk->output();


$rk->log('Complete. Executing time: ' . (time()-$start_time) . 'ms');
echo $rk->getJSLog();