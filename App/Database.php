<?php
namespace App;

use PDO;
use PDOException;

include(__DIR__.'/../config.php');
class Database
{
    private $dbname;
    private $dbhost;
    private $dbuser;
    private $dbpassword;
    private $dbport;
    private $dbc;
    function __construct()
    {
        $this->dbhost = &$GLOBALS['DB_HOST'];  
        $this->dbuser = &$GLOBALS['DB_USER'];  
        $this->dbpassword = &$GLOBALS['DB_PASS'];  
        $this->dbname = &$GLOBALS['DB_NAME'];  
        $this->dbport = &$GLOBALS['DB_PORT'];  

        try {
            $dsn = "mysql:host=" . $this->dbhost . ";port=" . $this->dbport . ";dbname=" . $this->dbname;
            $options = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            $this->dbc = new PDO($dsn, $this->dbuser, $this->dbpassword, $options);
        } catch (PDOException $exc) {
            echo "Hiba: " . $exc->getMessage();
        }
    }

    
    public function getColumnName($table)
    {
        $sql = "SHOW COLUMNS FROM " . $table;
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $data;
    }
    public function getNoKeyColumnName($table)
    {
        $column = $this->getColumnName($table);
        $sql = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY';";
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $column = array_diff($column, [$data[0]['Column_name']]);
        return $column;
    }
    public function read(string $table)
    {
        $sql = "SELECT * FROM " . $table;
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function insert($table, $data)
    {
        $colName = $this->getNoKeyColumnName($table);
        $sql = "INSERT INTO " . $table . " ( ";
        foreach ($colName as $col) {
            if ($col <> end($colName)) {
                $sql .= $col . ", ";
            } else {
                $sql .= $col . " ) ";
            }
        }
        $sql .= "VALUES ( ";
        foreach ($colName as $col) {
            if ($col <> end($colName)) {
                $sql .= "'" . $data["$col"] . "', ";
            } else {
                $sql .= "'" . $data["$col"] . "' ) ";
            }
        }
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute();
        return true;
    }

    public function update($table, $data, $id)
    {
        $colName = $this->getNoKeyColumnName($table);
        $sql = "UPDATE " . $table . " SET ";
        foreach ($colName as $col) {

            if ($col <> end($colName)) {
                $sql .= $col . " = :" . $col . ", ";
            } else {
                $sql .= $col . " = :" . $col . " ";
            }
        }
        $sql .= "WHERE id = :id ";
        $stmt = $this->dbc->prepare($sql);
        $stmt->bindParam(':id', $id);
        foreach ($colName as $col) {
            $stmt->bindParam(":$col", $data["$col"]);
        }
        $stmt->execute();


    }


    public function delete($table, $id)
    {
        $sql = "DELETE FROM " . $table . " WHERE id = " . $id;
        $stmt = $this->dbc->prepare($sql);
        if($stmt->execute())return true;
    }

    public function delete_all($table,$column, $id)
    {
        $sql = "DELETE FROM " . $table . " WHERE "."$column"."= " . $id;
        $stmt = $this->dbc->prepare($sql);

        if($stmt->execute())return true;
    }

    public function readOne($table, $id)
    {
        $sql = "SELECT * FROM " . $table . " WHERE id =" . $id;
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getItemByValue(string $table, string $column, string $value)
    {

        try {
            $sql = "SELECT * FROM " .  $table  . " WHERE " . $column . " =  '" . $value . "'";
            $stmt = $this->dbc->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $exc) {
            echo "Lekérdezési hiba: " . $exc->getTraceAsString();
        }
    }
    public function filter(string $table, array $filters)
    {
        $sql = "SELECT * FROM " . $table . " WHERE ";

        foreach ($filters as $key => $value) {
            $sql .= $key . " = " . $value . " AND ";
        }

        $sql = substr($sql, 0, strlen($sql) - 4);
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function read_filter(string $table, array $columns, string $value)
    {
        $sql = "SELECT * FROM " . $table . " WHERE ";
        foreach ($columns as $col) {
            $sql .= " UPPER(" . $col . ") LIKE UPPER('%" . $value . "%') OR ";
        }
        $sql = substr($sql, 0, strlen($sql) - 4);
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function count(string $table,$column,$id){
        $sql = "SELECT COUNT(*) FROM ". $table . " WHERE ".$column ."=".$id;
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchColumn();
        return $data;

    }
}