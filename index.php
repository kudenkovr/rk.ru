<?php

echo '<pre>';

include 'core/init.php';

// print_r($rk);
$p = $rk->invoke('page');
print_r($p->getPageByUri());

echo '</pre>';


$rk->log('Complete. Executing time: ' . (time()-$start_time) . 'ms');
echo $rk->getJSLog();