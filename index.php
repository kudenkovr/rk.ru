<?php

echo '<pre>';

include 'core/init.php';

$c = $rk->run('page');

echo '</pre>';


$rk->log('Complete. Executing time: ' . (time()-$start_time) . 'ms');
echo $rk->getJSLog();