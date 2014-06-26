<?php

require_once './include/const.php';

$c = bootstrap();

$c->stash['test'] = 'テスト';

$c->render();