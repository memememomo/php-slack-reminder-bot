<?php
/**
 * コンテキストクラス
 *
 * HTTPリクエスト、ビジネスロジック、DBの繋ぎこみを行うクラス
 * HTTPレスポンスの処理も行う
 */

require_once('Request.php');


class Context {
    /**
     * @var DB
     */
    public $db;

    /**
     * @var Session
     */
    public $session;

    /**
     * @var array Smartyに渡す変数を格納する
     */
    public $stash;

    /**
     * @var string Modelモジュールファイルがあるディレクトリパス
     */
    public $model_dir;

    /**
     * @var string includeディレクトリのパス
     */
    public $include_dir;

    /**
     * @var Request
     */
    public $req;

    /**
     * @var Renderer
     */
    public $renderer;

    /**
     * @var Array
     */
    public $config;

    /**
     * コンストラクタ
     */
    public function __construct() {
        $this->req = new Request();
    }

    /**
     * 設定ファイルを読み込む
     *
     */
    public function load_config()
    {
        if (is_null($this->config)) {
            $this->config = include dirname(__FILE__).'/config.php';
        }

        return $this->config;
    }

    /**
     * include/以下のモジュールを読み込み
     *
     * @param string $name 読み込むファイル名
     */
    public function require_root($name) {
        require_once(implode('/', array($this->include_dir, $name)));
    }

    /**
     * Modelモジュールの読み込み
     *
     * @param string $name 読み込むファイル名
     */
    public function require_model($name) {
        require_once(implode('/', array($this->model_dir, $name)));
    }

    /**
     * Modelの生成
     *
     * @param strint $name Model名
     */
    public function _m($name) {
        $filename = $name . '.php';
        $this->require_model($filename);

        $classname = 'Model_' . $name;
        $Model = new $classname();

        $Model->c = $this;
        $Model->db = $this->db;

        return $Model;
    }

    /**
     * URLを生成
     *
     * @param string $path   パス
     * @param bool $fullpath http://から出力するか
     * @param bool $ssl_flg  httpsにするか
     * @param bool $ssl_off  httpにするか
     * @return string        生成したURL
     */
    public function url_for($path = '/', $fullpath = true, $ssl_flg = false, $ssl_off = false) {

        // Absolute URL
        if ( preg_match('/^\w+\:\/\//', $path) ) {
            return $path;
        }

        // URL
        $url = '';

        // Absolute Path
        if ( preg_match('/^\//', $path) ) {
            $url = $path;
        }

        // Relative
        else {
            // Base
            $base = $this->req->getBaseUrl();
            $url .= $base . '/' . $path;
        }


        if ( $fullpath ) {

            // 現在のページがSSLで、
            // かつ明示的にオフにする設定がなければ次のページもSSLを使う
            if ( $this->req->isSsl() && !$ssl_off && !defined('DEBUG_MODE_DISABLE_SSL') ) {
                $ssl_flg = true;
            }

            // デバッグモードだったら、強制的にオフ
            if ( defined('DEBUG_MODE_DISABLE_SSL') ) {
                $ssl_flg = false;
            }

            // プロトコル設定
            $proto = '';
            if ( $ssl_flg ) {
                $proto = 'https://';
            }
            else {
                $proto = 'http://';
            }

            // ドメイン
            $domain = $proto . $this->req->getHost();

            // URLをフルパスに設定する
            $url = $domain . $url;
        }

        return $url;
    }

    /**
     * ログインしているかどうかチェックして、
     * ログインしていなかった場合、指定URLにリダイレクト
     *
     * @param  string $url リダイレクトするURL
     * @return bool
     */
    public function checkLoginOrRedirect($url) {
        if ( !$this->session->checkLogout() ) {
            $this->redirect($url);
            exit;
        }

        return true;
    }

    /**
     * ログアウトしているかどうかチェックして、
     * ログアウトしていなかった場合、指定URLにリダイレクト
     *
     * @param  string $url リダイレクトするURL
     * @return int
     */
    public function checkLogoutOrRedirect($url) {
        if ( $this->session->checkLogin() ) {
            $this->redirect($url);
            exit;
        }

        return 1;
    }

    /**
     * ページを描画する
     *
     * @param string $templatefile Smartyテンプレートファイル名
     * @param string $tmpldir      Smartyテンプレートファイルパス
     */
    public function render($templatefile = '', $tmpldir = '') {
        $this->stash['_c'] = $this;
        return $this->renderer->render($this->stash, $templatefile, $tmpldir);
    }

	/**
	 * レンダリングした結果を取得する
	 *
	 * @param string $templatefile
	 * @param string $tmpldir
	 */
	public function render_partial($templatefile = '', $tmpldir = '', $data = array()) {
		$this->stash['_c'] = $this;
		return $this->renderer->render_partial($this->stash, $templatefile, $tmpldir);
	}

	/**
     * リダイレクト処理
     *
     * @param $url    リダイレクトするURL
	 * @param $status ステータスコード
     */
    public function redirect_to($url, $status = '302') {
        $to = $this->url_for($url, true, $this->req->isSsl());
		header("Status: $status");
        header("Location: $to");
        exit;
    }

	/**
     * HTTPリクエストのパラメータを配列に格納
     * $_GETと$_POSTをまとめる
     *
     * @return array HTTPリクエストの引数を配列に格納したもの
     */
    public function param($k = null) {
        $FORM = array();
        foreach ($_POST as $key => $value) {
            $FORM[$key] = $value;
        }
        foreach ($_GET as $key => $value) {
            $FORM[$key] = $value;
        }

        if ($k) {
            return $FORM[$k];
        }
        else {
            return $FORM;
        }
    }
}
