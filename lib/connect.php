<?php
class db {

    protected $connection;
    protected $query;
    protected $show_errors = TRUE;
    protected $query_closed = TRUE;
    public $query_count = 0;

    public function __construct() {
        try {
            $dbhost = 'mysql.imscrm.com';
            $dbname = 'imscrm';
            $dbuser = 'imscrm';
            $dbpass = 'Y2C7Q2iKQ{E#';
            $charset = 'utf8';

            $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
            if ($this->connection->connect_error) {
                file_put_contents('debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__.'Failed to connect to MySQL - ' . $this->connection->connect_error, 8);
                $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
            }
            $this->connection->set_charset($charset);
        } catch (\Exception $e) {
            file_put_contents('debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || __construct ERROR: ".$e->getMessage(), 8);
        }
    }
    public function getOneWhere($where, $table, $orderBy=null, $limit = null) {
        try {
            $query = "SELECT * FROM `$table` WHERE ";

            foreach ($where as $f => $v) {
                $query .= $f." = '".$v."'";
            }
            if($orderBy) {
                $query .= " ORBER BY ".$orderBy." DESC";
            }
            if($limit) {
                $query .= " LIMIT ".$limit;
            }
            
            return $this->query($query)->fetchArray();

        } catch (\Exception $e) {
            file_put_contents('debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || getOneWhere ERROR: ".$e->getMessage(), 8);
            file_put_contents('debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || The query: ".$query, 8);
        }
    }
    public function getAll($table, $orderBy=null, $limit = null) {
        try {
            $query = "SELECT * FROM `$table`";

            if($orderBy) {
                $query .= " ORBER BY ".$orderBy." DESC";
            }
            if($limit) {
                $query .= " LIMIT ".$limit;
            }
            
            return $this->query($query)->fetchAll();

        } catch (\Exception $e) {
            file_put_contents('debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || getOneWhere ERROR: ".$e->getMessage(), 8);
        }
    }
    public function query($query) {
        if (!$this->query_closed) {
            $this->query->close();
        }
        if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
                $types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
                    if (is_array($args[$k])) {
                        foreach ($args[$k] as $j => &$a) {
                            $types .= $this->_gettype($args[$k][$j]);
                            $args_ref[] = &$a;
                        }
                    } else {
                        $types .= $this->_gettype($args[$k]);
                        $args_ref[] = &$arg;
                    }
                }
                array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
            if ($this->query->errno) {
                file_put_contents('debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__.'Unable to process MySQL query (check your params) - ' . $this->query->error, 8);
                $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
            }
            $this->query_closed = FALSE;
            $this->query_count++;
        } else {
            file_put_contents('debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__.'<br>Query: ' . $query . '<br>ERROR: ' . $this->connection->error, 8);
            $this->error('<br>Query: ' . $query . '<br>ERROR: ' . $this->connection->error);
        }
        return $this;
    }


    public function fetchAll($callback = null) {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    public function fetchArray() {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            foreach ($row as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    public function close() {
        return $this->connection->close();
    }

    public function numRows() {
        $this->query->store_result();
        return $this->query->num_rows;
    }

    public function affectedRows() {
        return $this->query->affected_rows;
    }

    public function lastInsertID() {
        return $this->connection->insert_id;
    }

    public function error($error) {
        if ($this->show_errors) {
            return $error;
        }
    }

    private function _gettype($var) {
        if (is_string($var)) return 's';
        if (is_float($var)) return 'd';
        if (is_int($var)) return 'i';
        return 'b';
    }
    public function getProductName($id){
        $product = array();
        $product_services = $this->query('SELECT * FROM `product_services` WHERE `product_id` = '.$id)->fetchAll()[0];
        $product['name'] = $product_services['product_name'];
        $consultation_lines = $this->query('SELECT * FROM `consultation_lines` WHERE product_id = '.$id)->fetchAll();
        $total_products = count($consultation_lines);
        $product['total'] = $product_services['unit_selling_price'] * $total_products;
        return $product;
    }
    public function checkLogin($username, $password){
        $user_info = $this->getOneWhere(['username' => $username], 'users');
        if(password_verify($password, $user_info['password'])){
            $_SESSION["loggedin"] = 'yes';
        } else {
            $_SESSION["loggedin"] = '';
            return 'There was an error, Please try again if you can not login call one of the Jon\'s';
        }
    }
    public function insert_sql($query){
        try{
            $this->query($query);
            return 'yes';
        } catch (\Exception $e) {
            return 'query: '.$query.'<br>'.$e->getMessage();
        }
    }
    public function insert_sql_file($query){
        try{
            $query = addslashes($query);
            $this->query("INSERT INTO sql_file (`query`, `active`) VALUES ('".$query."', 1)");
            return 'yes';
        } catch (\Exception $e) {
            return 'query: '.$query.'<br>'.$e->getMessage();
        }
    }
}
?>