<?php

class sqlDB {

    private static $conn = null;          // database connection
    private static $host = "localhost";   // database host machine
    private static $user = "root";        // database username
    private static $pass = null;          // database user password
    private static $db = null;            // database name
    private static $prefix = "";          // table prefix

    public static function connect($host, $user $pass, $db) {
        sqlDB::$conn = mysqli_connect($host, $user, $pass, $db);
    }

    public static function query($query) {
        return mysqli_query(sqlDB::$conn, $query);
    }

    public static function error() {
        return mysqli_error(sqlDB::$conn);
    }

    public static function escape($str) {
        return mysqli_real_escape_string(sqlDB::$conn, $str);
    }

    public static function insert($table, $vars) {
        // check if we are connected to the database
        if (!sqlDB::$conn) {
            sqlDB::connect();
        }

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
                    $vals[$i] = "\"" . mysqli_real_escape_string(sqlDB::$conn, $val) . "\"";
                    break;
                case "NULL":
                    $vals[$i] = "NULL";
                    break;
            }
        }
        $table = sqlDB::$prefix . $table;
        $result = sqlDB::query("INSERT INTO `" . $table . "` (`" . implode("`, `", $rows) . "`) VALUES (" . implode(", ", $vals) . ");");
        return $result ? true : false;
    }

    public static function update($table, $where, $vars) {
        // check if we are connected to the database
        if (!sqlDB::$conn) {
            sqlDB::connect();
        }

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
                    $vals[$i] = "\"" . mysqli_real_escape_string(sqlDB::$conn, $val) . "\"";
                    break;
                case "NULL":
                    $vals[$i] = "NULL";
                    break;
            }
            $pairs[] = "`" . $rows[$i] . "` = " . $vals[$i];
        }
        $table = sqlDB::$prefix . $table;
        $result = sqlDB::query("UPDATE `" . $table . "` SET " . implode(", ", $pairs) . ($where ? " WHERE " . $where : "") . ";");
        return $result ? true : false;
    }

    public static function delete($table, $where) {
        // check if we are connected to the database
        if (!sqlDB::$conn) {
            sqlDB::connect();
        }

        $table = sqlDB::$prefix . $table;
        $result = sqlDB::query("DELETE FROM `" . $table . "`" . ($where ? " WHERE " . $where : "") . ";");
        return $result ? true : false;
    }

    public static function fetch($table, $where=null, $sort=null, $limit=array(0, 1000)) {
        // check if we are connected to the database
        if (!sqlDB::$conn) {
            sqlDB::connect();
        }

        $table = sqlDB::$prefix . $table;
        // define a string to store our SQL query
        $conds = "";
        if ($where) {
            $conds .= " WHERE " . $where;
        }
        if ($sort) {
            $conds .= " ORDER BY " . $sort;
        }
        $conds .= " LIMIT " . implode(", ", $limit);
        $result = sqlDB::query("SELECT * FROM `" . $table . "`" . $conds . ";");
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

    public static function fetchJoin($fields, $tables, $where=null, $sort=null, $limit=array(0, 1000)) {
        // check if we are connected to the database
        if (!sqlDB::$conn) {
            sqlDB::connect();
        }

        $field = implode(", ", $fields);
        foreach ($tables as $i => $table) {
            $tables[$i] = sqlDB::$prefix . $table;
        }
        $table = implode(", ", $tables);
        $conds = "";
        if ($where) {
            $conds .= " WHERE " . $where;
        }
        if ($sort) {
            $conds .= " ORDER BY " . $sort;
        }
        $conds .= " LIMIT " . implode(", ", $limit);
        $result = sqlDB::query("SELECT " . $field . " FROM " . $table . $conds . ";");
        $data = array();
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