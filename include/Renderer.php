<?php

require_once(SMARTY_DIR.'Smarty.class.php');

class Renderer {

    public $tmpl_dir = null;

    public function render_partial($data=null, $templatefile=null, $tmpldir=null) {

        $smarty = new Smarty();

        if ( !$tmpldir ) {
            $tmpldir = $this->smarty_dir;
        }

        if ( !$tmpldir ) { $tmpldir = $this->tmpl_dir; }
        $smarty->template_dir = $tmpldir."templates/";
        $smarty->compile_dir  = $tmpldir."templates_c/";
        $smarty->config_dir   = $tmpldir."configs/";
        $smarty->cache_dir    = $tmpldir."cache/";
        $smarty->caching      = false;
        $smarty->default_modifiers = array('escape');

        // PHPのファイル名から対応するテンプレートを自動判別
        if ( !$templatefile ) {
            $tmplname =  basename($_SERVER['SCRIPT_NAME'],".php").".tmpl";

            $tdir = opendir($tmpldir."templates/") or die("ディレクトリのオープンに失敗しました。");

            while ($fname = readdir($tdir)) {
                if (strcmp($fname, $tmplname) == 0) {
                    $templatefile = $tmplname;
                }
            }
        }

        if ( !$templatefile ) {
            die("テンプレートファイルが存在しません。");
        }

        // テンプレート引数を設定
        if ( is_array($data) ) {
            foreach($data as $key => $val) {
                $smarty->assign($key, $val);
            }
        }

        $output = $smarty->fetch($templatefile);

        return $output;
    }

    public function render($data=NULL, $templatefile = NULL, $tmpldir=NULL) {
        header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
        header("Last-Modified: ". gmdate("D, d M Y H:i:s"). " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-type: text/html; charset=UTF-8");
        echo $this->render_partial($data, $templatefile, $templ);
    }
}
