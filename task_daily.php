<?php

require_once './include/const.php';

$c = bootstrap();

$TaskDaily = $c->_m('TaskDaily');

$c->stash['tasks'] = $TaskDaily->tasks();

$hours = array();
for ($hour = 0; $hour <= 23; $hour++) {
    $hours []= $hour;
}
$c->stash['hours'] = $hours;

$mins = array();
for ($min = 0; $min <= 59; $min++) {
    $mins []= $min;
}
$c->stash['mins'] = $mins;


$c->render();
