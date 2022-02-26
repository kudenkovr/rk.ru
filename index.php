<?php

echo '<pre>';

include 'core/init.php';

$rk->run('page');
$rk->output();
// print_r($c);

echo '</pre>';


$rk->log('Complete. Executing time: ' . (time()-$start_time) . 'ms');
echo $rk->getJSLog();