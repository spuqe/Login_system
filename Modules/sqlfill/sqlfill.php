<?php 

     /* Author @UnrealSec  */

    class SQLFill {
        function __construct($host, $user, $pass, $dbname, $port = 3306) {
            $this->queries = [];
            $this->mysqli = new mysqli($host, $user, $pass, $dbname, $port);
        }

        function nofill($query) {
            if (!is_string($query)) {
                throw new Exception($this::$strings[0].$this::$strings[2]);
            }
            $this->queries[] = $query;
            return $this;
        }

        function fill($template, ...$data) {
            $array = str_split($template);

            $j = 0;
            for ($i = 0; $i < count($array); $i++) {
                if ($array[$i] === '&') {
                    $value = $data[$j++];
                    $d = SQLFill::get_array_d($value);

                    if ($d === 0) {
                        if (!SQLFill::is_valid_field_name($value)) {
                            throw new Exception(SQLFill::$strings[0].SQLFill::$strings[1]);
                        }
                        $array[$i] = SQLFill::escape($value, false);
                    } else if ($d === 1) {
                        $select = [];
                        foreach ($value as &$v) {
                            if (!SQLFill::is_valid_field_name($v)) {
                                throw new Exception(SQLFill::$strings[0].SQLFill::$strings[1]);
                            }
                            $select[] = SQLFill::escape($v, false);
                        }
                        $array[$i] = implode(',', $select);
                    }
                } else if ($array[$i] === '?') {
                    $value = $data[$j++];
                    $d = SQLFill::get_array_d($value);

                    if ($d === 0) {
                        $value = strval($value);
                        $array[$i] = SQLFill::escape($value);
                    } else if ($d === 1) {
                        $array[$i] = SQLFill::escape_array($value);
                    } else if ($d === 2) {
                        $array[$i] = SQLFill::escape_array_2d($value);
                    }
                } else if ($array[$i] === '$') {
                    $value = $data[$j++];
                    $d = SQLFill::get_array_d($value);

                    if ($d === 0) {
                        if (!SQLFill::is_valid_field_name($value)) {
                            throw new Exception($this::$strings[0].$this::$strings[1]);
                        }
                        $array[$i] = SQLFill::escape($value, false);
                    } else if ($d === 1) {
                        foreach ($value as $v) {
                            if (!SQLFill::is_valid_field_name($v)) {
                                throw new Exception($this::$strings[0].$this::$strings[1]);
                            }
                        }
                        $array[$i] = SQLFill::escape_array($value, false);
                    } else if ($d === 2) {
                        foreach ($value as $v1) {
                            foreach ($v1 as $v2) {
                                if (!SQLFill::is_valid_field_name($v2)) {
                                    throw new Exception($this::$strings[0].$this::$strings[1]);
                                }
                            }
                        }
                        $array[$i] = SQLFill::escape_array_2d($value, false);
                    }
                }
            }

            $this->queries[] = implode('', $array);
            return $this;
        }

        function tostr() {
            return implode(';', $this->queries);
        }

        function flush() {
            $this->queries = [];
        }

        function query() {
            $query = $this->tostr();
            $this->flush();
            return new SQLFillResult($this, $this->mysqli->query($query), $this->mysqli->errno !== 0);
        }

        static function escape($str, $quotes = true) {
            if (is_bool($str)) {
                $str = $str ? 'TRUE' : 'FALSE';
                return $str;
            } else {
                $str = strval($str);
            }

            if (in_array($str, SQLFill::$keywords, true)) {
                return $str;
            }

            $str = str_replace('\'', '\\\'', $str);
            return $quotes ? "'".$str."'" : $str;
        }

        static function escape_array($array, $quotes = true) {
            for ($i = 0; $i < count($array); $i++) {
                $array[$i] = SQLFill::escape($array[$i], $quotes);
            }
            return "(".implode(',', $array).")";
        }

        static function escape_array_2d($array, $quotes = true) {
            for ($i = 0; $i < count($array); $i++) {
                $array[$i] = SQLFill::escape_array($array[$i], $quotes);
            }
            return implode(',', $array);
        }

        static function is_valid_field_name($name) {
            $name = strval($name);
            return preg_match('/^.[A-z0-9_\.\(\)]*$/', $name) === 1;
        }

        static function get_array_d($array) {
            if (is_array($array)) {
                if (count($array) > 0 && is_array($array[0])) {
                    return 2;
                }
                return 1;
            }
            return 0;
        }

        public static $strings = [
            'SQLFill: ',
            'Failed to fill database query!',
            'Refused to execute database query!'
        ];

        public static $keywords = [
            'TRUE', 'FALSE'
        ];
    }

    class SQLFillResult {
        function __construct($sqlfill, $result, $is_error = false) {
            $this->sqlfill = $sqlfill;
            $this->result = $result;
            $this->error = $is_error;
        }

        function fetch() {
            return $this->result->fetch_assoc();
        }
    }

?>