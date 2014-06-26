<?php

require_once './include/const.php';

$c = bootstrap();

$Task = $c->_m('Task');

$c->stash['tasks'] = $Task->tasks();

$dt = new DateTime();
$c->stash['dt'] = $dt;

$years = array();
for ($year = 2014; $year <= 2020; $year++) {
    $years []= $year;
}
$c->stash['years'] = $years;

$mons = array();
for ($mon = 1; $mon <= 12; $mon++) {
    $mons []= $mon;
}
$c->stash['mons'] = $mons;

$days = array();
for ($day = 1; $day <= 31; $day++) {
    $days []= $day;
}
$c->stash['days'] = $days;

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
