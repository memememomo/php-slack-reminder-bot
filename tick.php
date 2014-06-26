<?php

require_once dirname(__FILE__).'/include/const.php';

$c = bootstrap();

$Tick = $c->_m('Tick');
$Tick->run();
