<?php

require_once './include/const.php';

$c = bootstrap();

if (!$c->param('text')) {
    echo "文言が設定されていません。";
    exit;
}

$values = array();
$values['text'] = $c->param('text');
$values['remind_hour'] = $c->param('hour');
$values['remind_min'] = $c->param('min');

$Task = $c->_m('TaskDaily');

$Task->create($values);

$c->redirect_to('task_daily.php');
