<?php
class dbi extends mysqli {
    /**
     * array of mysql error codes on which to retry a connection
     *
     * @var array
     */
    public static $retry_error_codes = array(
        1203, //ER_TOO_MANY_USER_CONNECTIONS
        1040, //ER_CON_COUNT_ERROR
        2002, //CR_CONNECTION_ERROR
        2006, //CR_SERVER_GONE_ERROR
        2013, //CR_SERVER_LOST
    );
    /**
     * singleton dbi instance
     *
     * @var   dbi
     * @since 2.4.1
     */
    private static $instance;
    /**
     * query cacher instance
     *
     * @var   dbi
     * @since 2.4.1
     */
    private static $querycacher = null;
    /**
     * array of singleton dbi instances
     *
     * @var   dbi[]
     * @since 2.4.9
     */
    private static $instances = array();
    /**
     * b_s() every query
     *
     * @var bool
     */
    public $show_queries = false;
    /**
     * count of executed queries
     *
     * @var int
     */
    public $query_count = 0;
    /**
     * array of query types to print
     *
     * @var array
     */
    public $print_types = array();
    /**
     * use unbuffered queries
     * care needs to be taken to reset this property or be aware of the changes it requires
     *
     * @var bool
     */
    public $unbuffered = false;
    /**
     * last query run by connection
     *
     * @var string
     */
    public $last_query;
    /**
     * host to connect to
     *
     * @var string
     */
    private $host;
    /**
     * user to connect as
     *
     * @var string
     */
    private $user;
    /**
     * password for user
     *
     * @var string
     */
    private $password;
    /**
     * database name to connect to
     *
     * @var string
     */
    private $db;
    private $socket;
    /**
     * port used to connect to server
     *
     * @var int
     * @since 2.2
     */
    private $port = 3306;
    /**
     * number of times to retry a query on retry errors
     *
     * @var int
     */
    private $retries = 5;
    /**
     * delay step in seconds
     *
     * each retry is delayed by (last retry delay) + retry_step
     *
     * @var int
     */
    private $retry_step = 1;
    /**
     * internal status of transaction
     *
     * causes most queries to fail instead of retry
     *
     * @var bool
     */
    private $in_transaction = false;
    /**
     * array of variables set on a connnection
     *
     * @var array
     */
    private $_variables = array();
    /**
     * comment prepended to all queries to identify running script from db server
     *
     * @var string
     */
    private $query_comment;
    /**
     * Used for nested transactions
     * @var int
     */
    private $transactionLevel = 0;

    /**
     * Creates a new db connection
     *
     * set the connection charset to UTF-8 since 2.2.2
     *
     * @param   string $host     host to connect to
     * @param   string $user     user to connect as
     * @param   string $password password for user
     * @param   string $db       database name to connect to
     * @param   int    $port     port used to connect to server
     * @param   string $socket   socket used to connect to server
     * @uses    dbi::connect()
     * @throws dbiException
     */
    public function __construct($host, $user, $password, $db, $port = 3306, $socket = null) {
        $this->host     = 'mysql.imscrm.com';
        $this->user     = 'imscrm';
        $this->password = 'Y2C7Q2iKQ{E#';
        $this->db       = 'imscrm';
        $this->port     = $port;
        $this->socket   = $socket ? $socket : ini_get('mysqli.default_socket');
        self::connect();

        /*
         * @since 2.2.2
         */
        $this->set_charset('utf8');
        $this->_set_query_comment();
    }

    /**
     * Creates a new db connection or returns the existing singleton instance
     *
     * @param   string $host     host to connect to
     * @param   string $user     user to connect as
     * @param   string $password password for user
     * @param   string $db       database name to connect to
     * @param   string $port     port used to connect to server
     * @param   string $socket   socket used to connect to server
     * @uses    static::__construct()
     * @since   2.4.1
     * @return dbi
     * @throws dbiException
     */
    public static function sconnect($host, $user, $password, $db, $port = 3306, $socket = null) {
        $key = "$host-$user-$password-$db-$port-$socket";
        if (array_key_exists($key, static::$instances)) {
            return static::$instances[$key];
        } else {
            static::$instances[$key] = new static($host, $user, $password, $db, $port, $socket);
            if (!isset(static::$instance)) {
                static::$instance = static::$instances[$key];
            }
            return static::$instances[$key];
        }
    }

    /**
     * returns the singleton instance
     *
     * @return static
     * @since  2.4.1
     */
    public static function get_instance() {
        return static::$instance;
    }

    /**
     * empties the instances cache
     */
    public static function clear_instances() {
        static::$instance  = null;
        static::$instances = array();
    }

    /**
     * wrapper for mysqli::__construct
     *
     * implements retry logic
     *
     * @uses    mysqli::__construct()  creates a new mysqli connection
     */
    public function connect() {
        $delay = 0;
        for ($i = 0; $i < $this->retries; ++$i) {
            parent::__construct($this->host, $this->user, $this->password, $this->db, $this->port, $this->socket);
            if (!$this->connect_errno) {
                foreach ($this->_variables as $k => $v) {
                    $vs = $this->_values_string($v);
                    $this->query("SET $k = $vs");
                }
                return true;
            } elseif (in_array($this->connect_errno, self::$retry_error_codes)) {
                sleep($delay);
                $delay += $this->retry_step;
            } else {
                break;
            }
        }
        throw new dbiException('failed to connect: ' . $this->connect_error, $this->connect_errno);
    }
    function b_h() {
        echo "<hr />\n";
    }
    
    /**
     * echos <code>&lt;br /&gt;</code>
     *
     * @author Brad Jorgensen <Brad@debtpaypro.com>
     */
    function b_b() {
        echo "<br />\n";
    }
    function b_s($text) {
        $this->b_h();
        echo $text;
        $this->b_h();
    }
    /**
     * wrapper for mysqli::query
     * also prepends hostname and pid in a comment
     *
     * enabling $debug will print the query
     *
     * @param   string $query query to execute
     * @param   bool   $debug b_s() the query
     * @return  mysqli_result
     * @uses    mysqli::query() runs the query
     * @throws dbiException
     */
    public function query($query, $debug = false) {
        $this->query_count++;
        if ($debug || $this->show_queries) {
            if ($this->print_types && is_array($this->print_types)) {
                foreach ($this->print_types as $type) {
                    if (stripos($query, $type) === 0) {
                        $this->b_s($query);
                        break;
                    }
                }
            } else {
                $this->b_s($query);
            }
        }

        $result = $this->_run_query('/* ' . $this->query_comment . ' */ ' . $query);

        if ($this->error) {
            throw new dbiException('error running query: ' . $this->error, $this->errno, null, $query);
        }

        return $result;
    }

    /**
     * starts a transaction and sets internal state so that the current connection must be used
     * @param boolean $useSavepoint set to true to enable nested transactions
     * @return boolean
     * @throws dbiException
     */
    public function start_transaction($useSavepoint = false) {
        // use nested transactions if requested OR if already started nested transaction
        if ($useSavepoint || $this->transactionLevel > 0) {
            $this->setSavepoint();
            // do not set $this->in_transaction, so transaction is always started for DBObjectBase::save()
            return true;
        }
        // this could be replaced by $this->begin_transaction() according to https://dev.mysql.com/doc/refman/8.0/en/commit.html
        if ($this->autocommit(false)) {
            $this->in_transaction = true;
            return true;
        } else {
            throw new dbiException("failed starting transaction");
        }
    }

    private function setSavepoint()
    {
        if ($this->transactionLevel == 0) {
            $this->begin_transaction();
        } else {
            $this->savepoint('LEVEL' . $this->transactionLevel);
        }
        // keeping transaction level
        $this->transactionLevel++;
    }

    /**
     * commits the current transaction and release the connection requirement
     *
     * @return boolean
     * @throws dbiException
     */
    public function commit_transaction() {
        // transactionLevel is only updated when using savepoints
        if ($this->transactionLevel > 0) {
            $trLevel = --$this->transactionLevel;
            if ($this->transactionLevel < 0) {
                throw new dbiException("Wrong transaction level: $this->transactionLevel");
            }
            if ($this->transactionLevel == 0) {
                // all nested transactions are processed
                $this->commit();
            } else {
                $this->release_savepoint('LEVEL' . $trLevel);
            }
            return true;
        }
        if ($this->commit()) {
            $this->autocommit(true);
            $this->in_transaction = false;
            return true;
        } else {
            throw new dbiException("failed committing transaction");
        }
    }

    /**
     * rolls back the current transaction and release the connection requirement
     *
     * @return boolean
     * @throws dbiException
     */
    public function rollback_transaction() {
        if ($this->transactionLevel > 0) {
            $trLevel = --$this->transactionLevel;
            if ($this->transactionLevel < 0) {
                throw new dbiException("Wrong transaction level: $this->transactionLevel");
            }
            if ($this->transactionLevel == 0) {
                // all nested transactions are processed
                $this->rollback();
            } else {
                $this->query('ROLLBACK TO LEVEL' . $trLevel);
            }
            return true;
        }
        if ($this->rollback()) {
            $this->autocommit(true);
            $this->in_transaction = false;
            return true;
        } else {
            throw new dbiException("failed rolling back transaction");
        }
    }

    /**
     * utility to safely construct a query string in the style of sprintf
     *
     * @param   string $query query string with '%' as in sprintf
     * @return  string                 escaped query string
     * @uses    mysqli::escape_string()    escapes query string
     */
    public function qs($query) {
        $args  = func_get_args();
        $query = array_shift($args);
        $args  = array_map('self::escape_string', $args);
        array_unshift($args, $query);
        $query = call_user_func_array('sprintf', $args);
        return $query;
    }

    /**
     * alias for mysqli::escape_string()
     *
     * @param   string $string raw string to escape
     * @return  string                  escaped string
     * @uses    mysqli::escape_string() runs the query
     */
    public function escape($string) {
        return $this->escape_string($string);
    }

    /**
     * alias for dbi::query()
     *
     * @param   string $query query to execute
     * @param   bool   $debug b_s() the query
     * @return  mysqli_result
     * @uses    dbi::query()    runs the query
     * @throws dbiException
     */
    public function q($query, $debug = false) {
        return $this->query($query, $debug);
    }

    /**
     * returns the first result as an object
     *
     * @param   string $query query to execute
     * @param   string $class class name to instanciate
     * @param   bool   $debug b_s() the query
     * @return  object          first result of query as an object
     * @uses    dbi::query()    runs the query
     * @throws dbiException
     */
    public function q_1($query, $class = 'StdClass', $constructor_args = array(), $debug = false) {
        if (method_exists($class, '__construct')) {
            $result = $this->query($query, $debug);
            $obj = $result->fetch_object($class,$constructor_args);
            $result->free_result();
            return $obj;
        }

        $result = $this->query($query, $debug);
        $obj = $result->fetch_object($class);
        $result->free_result();

        return $obj;
    }

    /**
     * returns all results as an array of objects with "key" used as the array index
     *
     * @param   string $query query to execute
     * @param   string $key field name to use as array key
     * @param   string $class class name to instantiate
     * @param   bool   $debug b_s() the query
     * @return  array           array of result objects
     * @uses    dbi::query()    runs the query
     * @throws dbiException
     */
    public function q_all_keyed($query, $key, $class = 'StdClass', $constructor_args = array(), $debug = false) {
        $array  = array();
        $result = self::query($query, $debug);
        if (method_exists($class, '__construct')) {
            while ($row = $result->fetch_object($class, $constructor_args)) {
                $array[$row->$key] = $row;
            }
        } else {
            while ($row = $result->fetch_object($class)) {
                $array[$row->$key] = $row;
                unset($array[$row->$key]->$key);
            }
        }
        $result->free_result();
        return $array;
    }

    /**
     * returns all results as an array of objects
     *
     * @param   string $query query to execute
     * @param   string $class class name to instantiate
     * @param   bool   $debug b_s() the query
     * @return  array           array of result objects
     * @uses    dbi::query()    runs the query
     * @throws dbiException
     */
    public function q_all($query, $class = 'StdClass', $constructor_args = array(), $debug = false) {
        $array  = array();
        $result = self::query($query, $debug);
        if (method_exists($class, '__construct')) {
            while ($row = $result->fetch_object($class, $constructor_args)) {
                $array[] = $row;
            }
        } else {
            while ($row = $result->fetch_object($class)) {
                $array[] = $row;
            }
        }
        $result->free_result();
        return $array;
    }

    /**
     * returns an array of all returned values
     *
     * @param   string $query query to execute
     * @param   bool   $debug b_s() the query
     * @return  array
     * @uses    dbi::q_all()    runs each query
     * @throws dbiException
     */
    public function q_all_values($query, $debug = false) {
        $out    = array();
        $result = $this->query($query, $debug);
        while ($row = $result->fetch_object()) {
            foreach ($row as $values) {
                $out[] = $values;
            }
        }
        $result->free_result();
        return $out;
    }

    /**
     * returns the number of results of a query
     *
     * @param   string $query query to execute
     * @param   bool   $debug b_s() the query
     * @return  int             number of results
     * @uses    dbi::query()    runs the query
     * @throws dbiException
     */
    public function q_n($query, $debug = false) {
        return $this->query($query, $debug)->num_rows;
    }

    /**
     * selects a single row with optional simple conditions
     *
     * @param string|array $fields           fields to select, string or array
     * @param string       $table            table to select from
     * @param string|array $where            string or array of WHERE conditions
     * @param string       $class            name of class to instanciate with result
     * @param array        $constructor_args array of args to pass to object constructor
     * @param bool         $debug            b_s() the query
     * @return object      first result of query as an object
     * @uses dbi::query()  runs the query
     * @throws dbiException
     */
    public function select_1($fields, $table, $where = array(), $class = 'StdClass', $constructor_args = array(), $debug = false) {
        if (is_array($fields)) {
            $temp = array();
            foreach ($fields as $field) {
                $temp[] = "`$field`";
            }
            $fields_string = implode(',', $temp);
        } else {
            $fields_string = $fields;
        }
        $query = "SELECT $fields_string FROM `$table`";
        if ($where) {
            if ($where_string = $this->_get_where_string($where)) {
                $query .= " WHERE $where_string";
            }
        }
        $query .= ' LIMIT 1';
        return $this->q_1($query, $class, $constructor_args, $debug);
    }

    /**
     * selects all rows with optional simple conditions
     *
     * @param string|array $fields           fields to select, string or array
     * @param string       $table            table to select from
     * @param string|array $where            string or array of WHERE conditions
     * @param string       $class            name of class to instanciate with result
     * @param array        $constructor_args array of args to pass to object constructor
     * @param bool         $debug            b_s() the query
     * @return array
     * @throws dbiException
     * @uses dbi::query()  runs the query
     */
    public function select_all($fields, $table, $where, $class = 'StdClass', $constructor_args = array(), $debug = false) {
        if (is_array($fields)) {
            $temp = array();
            foreach ($fields as $field) {
                $temp[] = "`$field`";
            }
            $fields_string = implode(',', $temp);
        } else {
            $fields_string = $fields;
        }
        $query = "SELECT $fields_string FROM `$table`";
        if ($where && $where_string = $this->_get_where_string($where)) {
            $query .= " WHERE $where_string";
        }
        return $this->q_all($query, $class, $constructor_args, $debug);
    }

    /**
     * inserts data from an array into a table
     *
     * @param   string $table
     * @param   array  $row      $key => $value pairs of data to insert
     * @param   array  $odu      data for 'ON DUPLICATE KEY UPDATE' clause
     * @param   string $modifier INSERT type modifier, usually blank or 'IGNORE'
     * @param   bool   $debug    b_s() the query
     * @return  int     insert id
     * @uses    dbi::_values_string()   formats the value for the query
     * @uses    dbi::query()            runs the query
     * @throws dbiException
     */
    public function insert($table, $row, $odu = array(), $modifier = '', $debug = false) {
        $keys   = array();
        $values = array();
        foreach ($row as $key => $value) {
            $keys[]   = "`$key`";
            $values[] = $value;
        }
        $query = "INSERT $modifier INTO `$table` (";
        $query .= implode(', ', $keys);
        $query .= ") VALUES (";
        $query .= $this->_values_string($values);
        $query .= ")";

        if ($odu) {
            $query .= ' ON DUPLICATE KEY UPDATE ';
            foreach ($odu as $key => $value) {
                $temp[] = "$key = " . $this->_values_string($value, true);
            }
            $query .= implode(', ', $temp);
        }

        $this->query($query, $debug);
        return $this->insert_id;
    }

    /**
     * replaces data from an array into a table
     *
     * @param   string $table
     * @param   array  $row   $key => $value pairs of data to insert
     * @param   bool   $debug b_s() the query
     * @return  int     insert id
     * @uses    dbi::_values_string()   formats the value for the query
     * @uses    dbi::query()            runs the query
     * @throws dbiException
     */
    public function replace($table, $row, $debug = false) {
        $keys   = array();
        $values = array();
        foreach ($row as $key => $value) {
            $keys[]   = "`$key`";
            $values[] = $value;
        }
        $query = "REPLACE INTO `$table` (";
        $query .= implode(', ', $keys);
        $query .= ") VALUES (";
        $query .= $this->_values_string($values);
        $query .= ")";
        $this->query($query, $debug);
        return $this->insert_id;
    }

    /**
     * inserts multiple rows in a single query 
     *
     * @param   string $table
     * @param   array  $rows     array of arrays of data in $key => $value pairs to insert
     * @param   array  $odu      data for 'ON DUPLICATE KEY UPDATE' clause
     * @param   string $modifier INSERT type modifier, usually blank or 'IGNORE'
     * @param   bool   $debug    b_s() the query
     * @return  mysqli_result           mysqli_result object
     * @uses    dbi::_values_string()   formats the value for the query
     * @uses    dbi::query()            runs the query
     * @throws dbiException
     */
    public function insert_array($table, $rows, $odu = array(), $modifier = '', $debug = false) {
        $keys = array_map(function ($key) {
            return "`$key`";
        }, array_keys($rows[0]));

        $query = "INSERT $modifier INTO `$table` (";
        $query .= implode(', ', $keys);
        $query .= ") VALUES ";

        foreach ($rows as $row) {
            $value_string    = '(';
            $value_string    .= $this->_values_string($row);
            $value_string    .= ')';
            $value_strings[] = $value_string;
        }

        $query .= implode(', ', $value_strings);

        if ($odu) {
            $query .= ' ON DUPLICATE KEY UPDATE ';
            foreach ($odu as $key => $value) {
                $temp[] = "$key = " . $this->_values_string($value, true);
            }
            $query .= implode(', ', $temp);
        }

        return $this->query($query, $debug);
    }

    /**
     * gets a lock with "GET LOCK"
     *
     * DO NOT USE ON MULTI-MASTER REPLICATED DATABASES!!
     * these locks to not get replicated and are not reliable
     *
     * @param   string $name    lock name
     * @param   string $timeout lock wait timeout
     * @param   bool   $debug   b_s() the query
     * @return  mysqli_result
     * @uses    dbi::query()    runs the query
     * @throws dbiException
     */
    public function get_lock($name, $timeout = 10, $debug = false) {
        return $this->query("SELECT GET_LOCK('$name', $timeout)", $debug);
    }

    /**
     * release lock acquired with "GET LOCK"
     *
     * DO NOT USE ON MULTI-MASTER REPLICATED DATABASES!!
     * these locks do not get replicated and are not reliable on multi-master setups
     *
     * @param   string $name  lock name
     * @param   bool   $debug b_s() the query
     * @uses    dbi::query()    runs the query
     * @throws dbiException
     */
    public function release_lock($name, $debug = false) {
        $this->query("SELECT RELEASE_LOCK('$name')", $debug);
    }

    /**
     * "UPDATE" wrapper method
     *
     * @param   string       $table
     * @param   string|array $data  data for "SET" clause
     * @param   string|array $where extra conditions for "WHERE" clause
     * @param   bool         $debug b_s() the query
     * @return  mysqli_result
     * @uses    dbi::_values_string()   formats the value for the query
     * @uses    dbi::query()            runs the query
     * @throws dbiException
     */
    public function update($table, $data, $where = '', $debug = false) {
        $query = "UPDATE `$table` SET ";
        if (is_array($data)) {
            $temp = array();
            foreach ($data as $key => $value) {
                $temp[] = "`$key` = " . $this->_values_string($value, true);
            }
            $query .= implode(', ', $temp);
        } else {
            $query .= $data;
        }
        if ($where) {
            if ($where_string = $this->_get_where_string($where)) {
                $query .= " WHERE $where_string";
            }
        }
        return self::query($query, $debug);
    }

    /**
     * sets variables for a connection so they can be reset if the connection is broken
     *
     * @param string $var name of variable
     * @param mixed  $val value of variable
     * @throws dbiException
     */
    public function set_variable($var, $val) {
        $this->_variables[$var] = $val;
        $vs                     = $this->_values_string($val);
        $this->query("SET $var = $vs");
    }

    /**
     * "DELETE" wrapper method
     *
     * @param   string       $table
     * @param   string|array $where extra conditions for "WHERE" clause
     * @param   bool         $debug b_s() the query
     * @return  mysqli_result
     * @uses    dbi::_values_string()   formats the value for the query
     * @uses    dbi::query()            runs the query
     * @throws dbiException
     */
    public function delete($table, $where = '', $debug = false) {
        $query = "DELETE FROM `$table`";
        if ($where) {
            if ($where_string = $this->_get_where_string($where)) {
                $query .= " WHERE $where_string";
            }
        }
        return self::query($query, $debug);
    }

    /** Delete large queries safely
     *
     * @throws dbiException
     */
    public function safe_delete($query, $limit = 5000, $debug = false) {
        $limit  = (int) $limit;
        $nquery = $query .= " LIMIT " . $limit;
        $this->query($nquery, $debug);
        $count = 0;
        while ($this->affected_rows > 0) {
            $this->query($nquery, $debug);
            $count += $this->affected_rows;
        }
        return $count;
    }

    /**
     * Update large queries safely
     *
     * @param   string       $table
     * @param   string|array $data
     * @param   string|array $where
     * @param   int          $limit
     * @param   int          $limitToSleep
     * @param   bool         $debug
     * @return  int
     * @throws dbiException
     */
    public function safe_update($table, $data, $where = '', $limit = 1000, $limitToSleep = 5000, $debug = false) {
        $where_string = $this->_get_where_string($where);
        if ($limit) {
            $where_string .= " LIMIT " . (int)$limit;
        }

        $count = 0;
        do {
            $this->update($table, $data, $where_string, $debug);
            $count += $this->affected_rows;

            if ($limitToSleep && ($count % $limitToSleep) < $this->affected_rows) {
                sleep(5);
            }
        } while ($this->affected_rows > $limit);

        return $count;
    }

    /**
     * called when unserializing
     *
     * @uses dbi::connect() re-opens the db connection
     * @throws dbiException
     */
    public function __wakeup() {
        $this->connect();
    }

    /**
     * called when serializing
     *
     * @uses mysqli::close() closes the db connection
     */
    public function __sleep() {
        $this->close();
        return array('db', 'host', 'password', 'port', 'print_types', 'query_count', 'retries', 'retry_step', 'show_queries', 'user');
    }

    /**
     * * inserts multiple rows in a single query
     *
     * @param        $table
     * @param        $columns           array of column names
     * @param        $values            array of arrays of data, each inner array is a row with data in the same order as the keys array
     * @param array  $odu               data for 'ON DUPLICATE KEY UPDATE' clause
     * @param string $modifier          INSERT type modifier, usually blank or 'IGNORE'
     * @param bool   $debug             b_s() the query
     * @return mysqli_result             mysqli_result object
     * @uses    dbi::_values_string()   formats the value for the query
     * @uses    dbi::query()            runs the query
     * @throws dbiException
     */
    public function insert_array_keyless($table, $columns, $values, $odu = array(), $modifier = '', $debug = false) {
        $columns = array_map(function ($key) {
            return "`$key`";
        }, $columns);
        $query   = "INSERT $modifier INTO `$table` (" . implode(', ', $columns) . ") VALUES ";

        foreach ($values as $row) {
            $value_string    = '(';
            $value_string    .= $this->_values_string($row);
            $value_string    .= ')';
            $value_strings[] = $value_string;
        }

        $query .= implode(', ', $value_strings);

        if ($odu) {
            $query .= ' ON DUPLICATE KEY UPDATE ';
            foreach ($odu as $key => $value) {
                $temp[] = "$key = " . $this->_values_string($value, true);
            }
            $query .= implode(', ', $temp);
        }
        return self::query($query, $debug);
    }

    /**
     * returns if currently in a transaction
     *
     * @return bool
     */
    public function getTransactionStatus() {
        return $this->in_transaction;
    }

    /**
     * Returns true if transactions (either nested or straignt) was started
     * @return bool
     */
    public function inTransaction()
    {
        return $this->in_transaction || $this->transactionLevel > 0;
    }

    /**
     * internal query method with retry logic
     *
     * @param   string $query query to execute
     * @return  mysqli_result
     * @uses    mysqli::query()             runs the query
     * @uses    mysqli::$retry_error_codes  errors to retry on
     * @uses    dbi::$in_transaction        forbids retrying if true
     * @throws dbiException
     */
    private function _run_query($query) {
        for ($i = 0; $i < $this->retries; ++$i) {
            $result_mode = $this->unbuffered ? MYSQLI_USE_RESULT : MYSQLI_STORE_RESULT;
            $result      = parent::query($query, $result_mode);
            if ($this->errno == 1213 && !$this->in_transaction) { // deadlock detected, retry transaction
                continue;
            } elseif (!$this->errno || !in_array($this->errno, self::$retry_error_codes) || $this->in_transaction) {
                $this->last_query = $query;
                return $result;
            } else {
                $this->connect();
            }
        }
        return $result;
    }

    /**
     * returns the string to directly input into a query
     *
     * output should be placed directly into the query
     * example output:
     * 'string1', 0x1234abc, NULL, 'string2'
     *
     * @param   mixed $values array or string to be prepared
     * @param   bool            force the value to be a scalar
     * @return  string
     * @uses    dbi::escape()   escapes data for use in queries
     */
    private function _values_string($values, $single = false) {
        $temp = array();
        if (!is_array($values) || $single) {
            $values = array($values);
        }
        foreach ($values as $value) {
            if (is_array($value)) {
                $val  = $value[0];
                $cont = $value[1];
            } elseif ($value === null) {
                $temp[] = 'NULL';
                continue;
            } elseif (preg_match('/^0x[0-9a-fA-F]+$/', $value)) { // is a hexadecimal number
                $temp[] = $value;
                continue;
            } else {
                $val  = $value;
                $cont = "'";
            }
            $temp[] = $cont . $this->escape_string($val) . $cont;
        }
        return implode(', ', $temp);
    }

    /**
     * initializes $this->query_comment
     */
    private function _set_query_comment() {
        $this->query_comment = gethostname() . ':' . getmypid();
    }

    /**
     * returns a where string from an array or string
     *
     * @param   string|array $where
     * @return  string                processed where wtring
     * @uses    dbi::_values_string() formats the value for the query
     */
    private function _get_where_string($where) {
        if (is_array($where)) {
            $temp = array();
            foreach ($where as $key => $value) {
                if (is_array($value) && !(count($value) === 2 && substr($value[0], -2) === '()' && $value[1] === '')) {
                    $temp[] = "`$key` IN (" . $this->_values_string($value) . ")";
                } else {
                    $temp[] = "`$key` = " . $this->_values_string($value, true);
                }
            }
            return implode(' AND ', $temp);
        } elseif (is_string($where)) {
            return $where;
        }
    }
}

class dbiException extends Exception {
    /**
     * query that caused the exception, if available
     *
     * @var string
     */
    public $query;

    public function __construct($message, $code = 0, $previous = null, $query = null) {
        parent::__construct($message, $code, $previous);
        $this->query = $query;
    }
}

/**
 * returns the global $dbi object
 *
 * @global dbi $dbi
 * @return dbi
 */
function dbi() {
    global $dbi;
    return $dbi;
}
