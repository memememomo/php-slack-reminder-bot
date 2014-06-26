<?php

class Session {
    protected static $session_started = false;
    protected static $session_id_regenerated = false;

    public function __construct() {
        if (!self::$session_started) {
            session_start();

            self::$session_started = true;
        }
    }

    public function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    public function get($name, $default = null) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return $default;
    }

    public function remove($name) {
        unset($_SESSION[$name]);
    }

    public function clear() {
        $_SESSION = array();
    }

    public function regenerate($destroy = true) {
        if (!self::$session_id_regenerated) {
            session_regenerate_id($destroy);

            self::$session_id_regenerated = true;
        }
    }

    public function setAuthenticated($bool) {
        $this->set('_authenticated', (bool)$bool);

        $this->regenerate();
    }

    public function isAuthenticated() {
        return $this->get('_authenticated', false);
    }
}