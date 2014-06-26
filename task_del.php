<?php

require_once './include/const.php';

$c = bootstrap();

$Task = $c->_m('Task');
$Task->delete($c->param('id'));

$c->redirect_to('task.php');
