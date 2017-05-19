<?php

class Database {

    // database connection
    private $conn = null;

    // singleton design pattern
    private static $instance;
    private function __construct($host, $user, $pass, $db) {
        $this->conn = mysqli_connect($host, $user, $pass, $db);
    }
    private function __clone() {}
    private function __sleep() {}
    private function __wakeup() {}
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query($query) {
        return mysqli_query($this->conn, $query);
    }

    public function error() {
        return mysqli_error($this->conn);
    }

    public function escape($str) {
        return mysqli_real_escape_string($this->conn, $str);
    }

    public function insert($table, $vars) {
        $rows = array_keys($vars);
        $vals = array_values($vars);
        foreach ($vals as $i => $val) {
            switch (gettype($val)) {
                case "integer":
                case "double":
                    $vals[$i] = (string) $val;
                    break;
                case "boolean":
                    $vals[$i] = ($val ? "1" : "0");
                    break;
                case "string":
                    $vals[$i] = "\"" . mysqli_real_escape_string($this->conn, $val) . "\"";
                    break;
                case "NULL":
                    $vals[$i] = "NULL";
                    break;
            }
        }
        $table = Database::$prefix . $table;
        $result = Database::query("INSERT INTO `" . $table . "` (`" . implode("`, `", $rows) . "`) VALUES (" . implode(", ", $vals) . ");");
        return $result ? true : false;
    }

    public function update($table, $where, $vars) {
        $rows = array_keys($vars);
        $vals = array_values($vars);
        $pairs = array();
        foreach ($vals as $i => $val) {
            switch (gettype($val)) {
                case "integer":
                case "double":
                    $vals[$i] = (string) $val;
                    break;
                case "boolean":
                    $vals[$i] = ($val ? "1" : "0");
                    break;
                case "string":
                    $vals[$i] = "\"" . mysqli_real_escape_string($this->conn, $val) . "\"";
                    break;
                case "NULL":
                    $vals[$i] = "NULL";
                    break;
            }
            $pairs[] = "`" . $rows[$i] . "` = " . $vals[$i];
        }
        $table = Database::$prefix . $table;
        $result = Database::query("UPDATE `" . $table . "` SET " . implode(", ", $pairs) . ($where ? " WHERE " . $where : "") . ";");
        return $result ? true : false;
    }

    public function delete($table, $where) {
        $table = Database::$prefix . $table;
        $result = Database::query("DELETE FROM `" . $table . "`" . ($where ? " WHERE " . $where : "") . ";");
        return $result ? true : false;
    }

    public function fetch($table, $where=null, $sort=null, $limit=array(0, 1000)) {
        $table = Database::$prefix . $table;
        // define a string to store our SQL query
        $conds = "";
        if ($where) {
            $conds .= " WHERE " . $where;
        }
        if ($sort) {
            $conds .= " ORDER BY " . $sort;
        }
        $conds .= " LIMIT " . implode(", ", $limit);
        $result = Database::query("SELECT * FROM `" . $table . "`" . $conds . ";");
        $data = array();

        // only parse the data if we were sucessful
        if ($result) {
            $count = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
                $count++;
            }
        }
        return $data;
    }
}

?>