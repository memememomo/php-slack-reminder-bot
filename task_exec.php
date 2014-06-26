<?php

require_once './include/const.php';

$c = bootstrap();

if (!$c->param('text')) {
    echo "文言が設定されていません。";
    exit;
}

$values = array();
$values['text'] = $c->param('text');
$values['remind_at'] = sprintf("%04d-%02d-%02d %02d:%02d:00", $c->param('year'), $c->param('mon'), $c->param('day'), $c->param('hour'), $c->param('min'));

$Task = $c->_m('Task');

$Task->create($values);

$c->redirect_to('task.php');
