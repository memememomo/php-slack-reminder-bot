<?php

require_once './include/const.php';

$c = bootstrap();

$TaskDaily = $c->_m('TaskDaily');
$TaskDaily->delete($c->param('id'));

$c->redirect_to('task_daily.php');
