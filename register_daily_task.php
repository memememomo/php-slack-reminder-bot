<?php

require_once './include/const.php';

$c = bootstrap();

$Task = $c->_m('Task');
$TaskDaily = $c->_m('TaskDaily');

$dt = new DateTime();
$year = $dt->format('y');
$mon  = $dt->format('m');
$day  = $dt->format('d');

$tasks = $TaskDaily->tasks();
foreach ($tasks as $task) {
    $values = array(
        'text' => $task['text'],
        'remind_at' => sprintf("%04d-%02d-%02d %02d:%02d:00", $year, $mon, $day, $task['remind_hour'], $task['remind_min']),
    );
    $Task->create($values);
}
