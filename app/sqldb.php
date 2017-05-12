<?
class DB {
    private static $conn = null;
    private static $prefix = "";
    public static function connect($host="localhost", $user="root", $pass=null, $db=null) {
        DB::$conn = mysqli_connect($host, $user, $pass, $db);
    }
    public static function setPrefix($newPrefix="") {
        DB::$prefix = ($newPrefix ? $newPrefix : "");
    }
    public static function query($query) {
        return mysqli_query(DB::$conn, $query);
    }
    public static function error() {
        return mysqli_error(DB::$conn);
    }
    public static function escape($str) {
        return mysqli_real_escape_string(DB::$conn, $str);
    }
    public static function insert($table, $vars) {
        if (!DB::$conn) {
            DB::connect();
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
                    $vals[$i] = "\"" . mysqli_real_escape_string(DB::$conn, $val) . "\"";
                    break;
                case "NULL":
                    $vals[$i] = "NULL";
                    break;
            }
        }
        $table = DB::$prefix . $table;
        $result = DB::query("INSERT INTO `" . $table . "` (`" . implode("`, `", $rows) . "`) VALUES (" . implode(", ", $vals) . ");");
        return $result ? true : false;
    }
    public static function update($table, $where, $vars) {
        if (!DB::$conn) {
            DB::connect();
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
                    $vals[$i] = "\"" . mysqli_real_escape_string(DB::$conn, $val) . "\"";
                    break;
                case "NULL":
                    $vals[$i] = "NULL";
                    break;
            }
            $pairs[] = "`" . $rows[$i] . "` = " . $vals[$i];
        }
        $table = DB::$prefix . $table;
        $result = DB::query("UPDATE `" . $table . "` SET " . implode(", ", $pairs) . ($where ? " WHERE " . $where : "") . ";");
        return $result ? true : false;
    }
    public static function delete($table, $where) {
        if (!DB::$conn) {
            DB::connect();
        }
        $table = DB::$prefix . $table;
        $result = DB::query("DELETE FROM `" . $table . "`" . ($where ? " WHERE " . $where : "") . ";");
        return $result ? true : false;
    }
    public static function fetch($table, $where=null, $sort=null, $limit=array(0, 1000)) {
        if (!DB::$conn) {
            DB::connect();
        }
        $table = DB::$prefix . $table;
        $conds = "";
        if ($where) {
            $conds .= " WHERE " . $where;
        }
        if ($sort) {
            $conds .= " ORDER BY " . $sort;
        }
        $conds .= " LIMIT " . implode(", ", $limit);
        $result = DB::query("SELECT * FROM `" . $table . "`" . $conds . ";");
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
    public static function fetchJoin($fields, $tables, $where=null, $sort=null, $limit=array(0, 1000)) {
        if (!DB::$conn) {
            DB::connect();
        }
        $field = implode(", ", $fields);
        foreach ($tables as $i => $table) {
            $tables[$i] = DB::$prefix . $table;
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
        $result = DB::query("SELECT " . $field . " FROM " . $table . $conds . ";");
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
