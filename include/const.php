<?php

ini_set('date.timezone', 'Asia/Tokyo');

$dirname = dirname(__FILE__);

// Database関連
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_HOST", "localhost");
define("DB_NAME", "slack_reminder");

define("TMPL_DIR", $dirname . '/../');

// Smartyのディレクトリ
define("SMARTY_DIR", $dirname.'/smarty/');

// ライブラリへのパスをセット
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . $dirname);

// システム共通関連
session_cache_limiter('none');
error_reporting(E_ALL ^ E_NOTICE);
ini_set( "display_errors", "Off");

// モジュール読み込み
require_once($dirname.'/Context.php');
require_once($dirname.'/DB.php');
require_once($dirname.'/Session.php');
require_once($dirname.'/Request.php');
require_once($dirname.'/Renderer.php');


function bootstrap() {
    $c = new Context();

    $c->req = new Request();

    $renderer = new Renderer();
    $renderer->tmpl_dir = TMPL_DIR;
    $c->renderer = $renderer;

    $db = new DB();
    try {
        $db->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
    catch (Exception $e) {
        error_log("接続エラー");
    }
    $c->db = $db;

    $c->session = new Session();

    $c->model_dir = dirname(__FILE__) . '/model';

    $c->stash = array();

    return $c;
}
