<?php

namespace App;

use PDO;
use PDOException;

class DB
{
    private $_dbh;
    private $error;

    private $_stmt;

    public function __construct()
    {
        // Set DSN
        $dsn = $_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->_dbh = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $e->getMessage();
        }
    }

    public function query($query)
    {
        $this->_stmt = $this->_dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->_stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        return $this->_stmt->execute();
    }

    public function resultset()
    {
        $this->execute();
        return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->_stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * Transactions allow multiple changes to a database all in one batch.
     */
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    public function debugDumpParams()
    {
        return $this->_stmt->debugDumpParams();
    }
}
