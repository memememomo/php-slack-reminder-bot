<?php

require_once 'SQL/Maker.php';
require_once 'TransactionManager.php';


class DB {

    public $dbh;

    public $trans;

    public $builder;

    public function __construct() {
        $this->builder = new SQL_Maker(array('driver' => 'mysql'));
    }

    public function connect($host, $user, $pass, $dbname) {
        $this->dbh = new PDO('mysql:host=' . $host . '; dbname=' . $dbname, $user, $pass);

        if ( !$this->dbh ) { throw new Exception("DB接続失敗"); }

        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh->query("SET NAMES UTF8");
        $this->dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, true);

        $this->trans = new TransactionManager($this->dbh);

        return $this->dbh;
    }

    public function fetch_all_array($rs) {
        return $rs->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetch_array($rs) {
        return $rs->fetch(PDO::FETCH_ASSOC);
    }

    public function count($rs) {
        return $rs->rowCount();
    }

    public function latestid() {
        return $this->dbh->lastInsertId();
    }

    public function txn_scope() {
        return $this->trans->txn_scope();
    }

    public function txn_rollback() {
        return $this->trans->txn_rollback();
    }

    public function commit() {
        return $this->trans->txn_commit();
    }

    public function search($table, $where, $opts = array()) {
        list($sql, $binds) = $this->builder->select($table, array('*'), $where, $opts);
        return new DB_Iterator($this, $this->db_query_params($sql, $binds));
    }

    public function single($table, $where = array(), $opts = array()) {
        $itr = $this->search($table, $where, $opts);
        return $itr->next();
    }

    public function insert($table, $values) {
        list($sql, $binds) = $this->builder->insert($table, $values);
        return $this->db_query_params($sql, $binds);
    }

    public function update($table, $sets, $where) {
        list($sql, $binds) = $this->builder->update($table, $sets, $where);
        return $this->db_query_params($sql, $binds);
    }

    public function delete($table, $where) {
        list($sql, $binds) = $this->builder->delete($table, $where);
        return $this->db_query_params($sql, $binds);
    }

    public function db_query_params($sql, $binds  = array()) {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($binds);
        }
        catch (PDOExceptin $e) {
            throw $e;
        }

        return $sth;
    }
}


class DB_Iterator {

    public $rs = null;

    public $db = null;

    public function __construct($db, $rs) {
        $this->db = $db;
        $this->rs = $rs;
    }

    public function next() {
        return $this->db->fetch_array($this->rs);
    }

    public function count() {
        return $this->db->count($this->rs);
    }
}
