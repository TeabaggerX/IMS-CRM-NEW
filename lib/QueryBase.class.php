<?php

/**
 * @author Satya <satya@debtpaypro.com>
 */
class QueryBase {

    /**
     * @var string Database Connection
     */
    protected $DBIconn;

    /**
     * @var array query builder params
     */
    private $_parameters = array();

    /**
     * @var array join conditions
     */
    private $joinsArray = array();

    /**
     * @var array where conditions
     */
    public $whereConditionsArray = array();

    /**
     * @var string limit rows
     */
    private $limit;

    /**
     * @var string offset rows
     */
    private $offset;

    /**
     * @var string orderBY columns
     */
    private $orderBy = '';

    /**
     * @var string fields
     */
    private $fieldString = '*';

    /**
     * @var array result set
     */
    public $results = array();

    /**
     * @var string base table name
     */
    protected $baseTable      = '';

    /**
     * @var array union condition
     */
    private $unionArray = array();

    /**
     * @var string groupBy condition
     */
    private $groupBy = '';

    /**
     * @var array having conditions
     */
    private $havingConditionsArray = array();

    /**
     *
     * @var string distinct
     */
    private $distinct = '';

    /**
     * @var string class to use for running the query and getting results, used when calling getObject, etc.
     */
    protected $objectClass;

    /**
     * @param string $baseTable use DUAL if you are not selecting from a table.
     * @param string $objectClass class to use for running the query and getting results
     */
    public function __construct($baseTable, $objectClass = null) {
        $this->DBIconn        = \dbi::get_instance();
        $this->baseTable      = $baseTable;
        $this->objectClass    = $objectClass;
    }

    public function __toString() {
        return $this->getQuery();
    }

    public function distinct() {
        $this->distinct = 'DISTINCT ';
        return $this;
    }

    public function join($joinType, $tableName, $col1, $col2,$alias='') {
        if (stripos($col1, ".") !== false) {
            list($table1, $field1) = explode(".", $col1);
        } else {
            $table1 = $this->baseTable;
            $field1 = $col1;
        }
        if (stripos($col2, ".") !== false) {
            list($table2, $field2) = explode(".", $col2);
        } else {
            $table2 = $tableName;
            $field2 = $col2;
        }

        switch ($joinType) {
            case 'INNER':
                $case = 'INNER';
                break;
            case 'LEFT':
                $case = 'LEFT';
                break;
            case 'RIGHT':
                $case = 'RIGHT';
        }
        if($alias != '') {
            $alias = ' '.$alias;
        }

        $this->joinsArray[] = " " . $case . " JOIN `" . $tableName . "`".$alias." ON `". $table1 . "`.`" . $field1 . "` = `" . $table2 . "`.`" . $field2 . "`";
        return $this;
    }

    public function innerJoin($tableName, $col1, $col2,$alias='') {
        return $this->join('INNER', $tableName, $col1, $col2,$alias);
    }

    public function leftJoin($tableName, $col1, $col2,$alias='') {
        return $this->join('LEFT', $tableName, $col1, $col2, $alias);
    }

    public function rightJoin($tableName, $col1, $col2,$alias='') {
        return $this->join('RIGHT', $tableName, $col1, $col2,$alias);
    }

    // @todo update to take a QueryBase
    public function union($fields, $tableName) {
        if (is_array($fields) && count($fields) > 0) {
            $fieldsArray = array();
            foreach ($fields as $key) {
                $fieldsArray[] = $this->getField($key);
            }
            $fieldString = implode(",", $fieldsArray);
        } else {
            $fieldString = $this->getField($fields);
        }
        $this->unionArray[] = " UNION  SELECT " . $fieldString . " FROM `" . $tableName . "`";
        return $this;
    }

    /**
     * Alias of and_where()
     *
     * @param   mixed   $column  column name or array($column, $alias) or object
     * @param   string  $op      logic operator
     * @param   mixed   $value   column value
     * @return  $this
     */
    public function where($column, $op, $value) {
        return $this->and_where($column, $op, $value);
    }

    /**
     * Creates a new "AND WHERE" condition for the query.
     *
     * @param   mixed   $column  column name or array($column, $alias) or object
     * @param   string  $op      logic operator
     * @param   mixed   $value   column value
     * @return  $this
     */
    public function and_where($column, $op, $value) {
        $this->whereConditionsArray[] = array('AND' => array($column, $op, $value));
        return $this;
    }

    /**
     * Creates a new "OR WHERE" condition for the query.
     *
     * @param   mixed   $column  column name or array($column, $alias) or object
     * @param   string  $op      logic operator
     * @param   mixed   $value   column value
     * @return  $this
     */
    public function or_where($column, $op, $value) {
        $this->whereConditionsArray[] = array('OR' => array($column, $op, $value));
        return $this;
    }

    /**
     * Alias of and_where_open()
     *
     * @return  $this
     */
    public function where_open() {
        return $this->and_where_open();
    }

    /**
     * Opens a new "AND WHERE (...)" grouping.
     *
     * @return  $this
     */
    public function and_where_open() {
        $this->whereConditionsArray[] = array('AND' => '(');
        return $this;
    }

    /**
     * Opens a new "OR WHERE (...)" grouping.
     *
     * @return  $this
     */
    public function or_where_open() {
        $this->whereConditionsArray[] = array('OR' => '(');
        return $this;
    }

    /**
     * Closes an open "WHERE (...)" grouping.
     *
     * @return  $this
     */
    public function where_close() {
        return $this->and_where_close();
    }

    /**
     * Closes an open "WHERE (...)" grouping or removes the grouping when it is
     * empty.
     *
     * @return  $this
     */
    public function where_close_empty() {
        $group = end($this->whereConditionsArray);
        if ($group AND reset($group) === '(') {
            array_pop($this->whereConditionsArray);
            return $this;
        }
        return $this->where_close();
    }

    /**
     * Closes an open "WHERE (...)" grouping.
     *
     * @return  $this
     */
    public function and_where_close() {
        $this->whereConditionsArray[] = array('AND' => ')');
        return $this;
    }

    /**
     * Closes an open "WHERE (...)" grouping.
     *
     * @return  $this
     */
    public function or_where_close() {
        $this->whereConditionsArray[] = array('OR' => ')');
        return $this;
    }

    /**
     * Alias of and_having()
     *
     * @param   mixed   $column  column name or array($column, $alias) or object
     * @param   string  $op      logic operator
     * @param   mixed   $value   column value
     * @return  $this
     */
    public function having($column, $op, $value) {
        return $this->and_having($column, $op, $value);
    }

    /**
     * Creates a new "AND HAVING" condition for the query.
     *
     * @param   mixed   $column  column name or array($column, $alias) or object
     * @param   string  $op      logic operator
     * @param   mixed   $value   column value
     * @return  $this
     */
    public function and_having($column, $op, $value) {
        $this->havingConditionsArray[] = array('AND' => array($column, $op, $value));
        return $this;
    }

    /**
     * Creates a new "OR HAVING" condition for the query.
     *
     * @param   mixed   $column  column name or array($column, $alias) or object
     * @param   string  $op      logic operator
     * @param   mixed   $value   column value
     * @return  $this
     */
    public function or_having($column, $op, $value) {
        $this->havingConditionsArray[] = array('OR' => array($column, $op, $value));
        return $this;
    }

    /**
     * Alias of and_having_open()
     *
     * @return  $this
     */
    public function having_open() {
        return $this->and_having_open();
    }

    /**
     * Opens a new "AND HAVING (...)" grouping.
     *
     * @return  $this
     */
    public function and_having_open() {
        $this->havingConditionsArray[] = array('AND' => '(');
        return $this;
    }

    /**
     * Opens a new "OR HAVING (...)" grouping.
     *
     * @return  $this
     */
    public function or_having_open() {
        $this->havingConditionsArray[] = array('OR' => '(');
        return $this;
    }

    /**
     * Closes an open "HAVING (...)" grouping.
     *
     * @return  $this
     */
    public function having_close() {
        return $this->and_having_close();
    }

    /**
     * Closes an open "HAVING (...)" grouping or removes the grouping when it is
     * empty.
     *
     * @return  $this
     */
    public function having_close_empty() {
        $group = end($this->havingConditionsArray);

        if ($group AND reset($group) === '(') {
            array_pop($this->havingConditionsArray);
            return $this;
        }
        return $this->having_close();
    }

    /**
     * Closes an open "HAVING (...)" grouping.
     *
     * @return  $this
     */
    public function and_having_close() {
        $this->havingConditionsArray[] = array('AND' => ')');
        return $this;
    }

    /**
     * Closes an open "HAVING (...)" grouping.
     *
     * @return  $this
     */
    public function or_having_close() {
        $this->havingConditionsArray[] = array('OR' => ')');
        return $this;
    }

    public function fields($fields) {
        if (is_array($fields) && count($fields) > 0) {
            $fieldsArray = array();
            foreach ($fields as $key) {
                $fieldsArray[] = $this->getField($key);
            }
            $this->fieldString = implode(",", $fieldsArray);
        } else if (is_string($fields) && $fields != "") {
            if (stripos($fields, ",") !== false) {
                $this->fields(explode(",", $fields));
            } else {
                $this->fieldString = $this->getField($fields);
            }
        } else {
            $this->fieldString = '*';
        }
        return $this;
    }

    public function concat($fields, $seperator, $as) {
        if (is_array($fields) && count($fields) > 0) {
            foreach ($fields as $key) {
                $concatfieldsArray[] = $this->getField($key);
            }
                $this->fieldString .= " ,CONCAT(" . implode(',"' . $seperator . '",', $concatfieldsArray) . ") AS " . $as;
        }
        return $this;
    }

    public function limit($limit) {
        $this->limit = intval($limit);
        return $this;
    }

    public function offset($offset) {
        $this->offset = intval($offset);
        return $this;
    }

    //@todo fix this
    public function orderBy($orderBy) {
        if (is_array($orderBy) && count($orderBy) > 0) {
            $orderArray = array();
            foreach ($orderBy as $key => $val) {
                $val = mb_strtolower(trim($val));
                if(!in_array($val, ['', 'asc', 'desc'])) continue;
                $orderArray[] = $this->getField($key) . " " . $val;
            }
            $this->orderBy = " ORDER BY " . implode(", ", $orderArray);
        } elseif ($orderBy === null) {
            $this->orderBy = "";
        } else {
            $this->orderBy = " ORDER BY " . $orderBy;
        }
        return $this;
    }

    public function groupBy($groupBy) {
        if (is_array($groupBy) && count($groupBy) > 0) {
            $groupByArray = array();
            foreach ($groupBy as $key) {
                $groupByArray[] = $this->getField($key);
            }
            $this->groupBy = " GROUP BY " . implode(",", $groupByArray);
        } else if (is_string($groupBy) && $groupBy != "") {
            if (stripos($groupBy, ",") !== false) {
                $this->groupBy(explode(",", $groupBy));
            } else {
                $this->groupBy = " GROUP BY " . $this->getField($groupBy);
            }
        } else {
            $this->groupBy = '';
        }
        return $this;
    }

    public function getField($field) {
        if (is_array($field)) {
            switch ($field['type']) {
                case 'function':
                    $fieldName = $this->getField($field['field']);
                    $ret       = "{$field['function']}({$fieldName})";
                    break;

                case 'subquery':
                    if ($field['subquery'] instanceof QueryBase) {
                        $ret = '(' . $field['subquery']->getQuery() . ')';
                    } else {
                        $ret = '(' . $field['subquery'] . ')';
                    }
                    break;

                case 'field':
                default:
                    $ret = $this->getField($field['field']);
                    break;
            }
            if ($field['alias']) {
                $ret .= " AS {$field['alias']}";
            }
        } else {
            if (stripos($field, ".") !== false) {
                list($tableName, $fieldName) = explode(".", $field);
                if ($fieldName == "*") {
                    $ret = "`" . $this->DBIconn->escape(trim($tableName, "\"' ")) . "`." . $this->DBIconn->escape(trim($fieldName, "\"' "));
                } else {
                    $ret = "`" . $this->DBIconn->escape(trim($tableName, "\"' ")) . "`.`" . $this->DBIconn->escape(trim($fieldName, "\"' ")) . "`";
                }
            } elseif ($field == "*") {
                $ret = "*";
            } else {
                $ret = "`" . $this->DBIconn->escape(trim($field, "\"' ")) . "`";
            }
        }
        return $ret;
    }

    protected function quote($value) {
        if ($value === NULL) {
            return 'NULL';
        } elseif ($value === TRUE) {
            return "'1'";
        } elseif ($value === FALSE) {
            return "'0'";
        } elseif (is_array($value)) {
            return '(' . implode(', ', array_map(array($this, __FUNCTION__), $value)) . ')';
        } elseif (is_int($value)) {
            return (int) $value;
        } elseif (is_float($value)) {
            // Convert to non-locale aware float to prevent possible commas
            return sprintf('%F', $value);
        }

        return "'" . $this->DBIconn->escape($value) . "'";
    }

    protected function compileConditions(array $conditions) {
        $last_condition = NULL;
        $sql            = '';
        foreach ($conditions as $group) {
            // Process groups of conditions
            foreach ($group as $logic => $condition) {
                if ($condition === '(') {
                    if (!empty($sql) AND $last_condition !== '(') {
                        // Include logic operator
                        $sql .= ' ' . $logic . ' ';
                    }
                    $sql .= '(';
                } elseif ($condition === ')') {
                    $sql .= ')';
                } else {
                    if (!empty($sql) AND $last_condition !== '(') {
                        // Add the logic operator
                        $sql .= ' ' . $logic . ' ';
                    }
                    // Split the condition
                    list($column, $op, $value) = $condition;
                    if ($value === NULL) {
                        if ($op === '=') {
                            // Convert "val = NULL" to "val IS NULL"
                            $op = 'IS';
                        } elseif ($op === '!=' OR $op === '<>') {
                            // Convert "val != NULL" to "valu IS NOT NULL"
                            $op = 'IS NOT';
                        }
                    }
                    // Database operators are always uppercase
                    $op = strtoupper($op);
                    if ($op === 'BETWEEN' AND is_array($value)) {
                        // BETWEEN always has exactly two arguments
                        list($min, $max) = $value;
                        if ((is_string($min) AND array_key_exists($min, $this->_parameters)) === FALSE) {
                            // Quote the value, it is not a parameter
                            $min = $this->quote($min);
                        }
                        if ((is_string($max) AND array_key_exists($max, $this->_parameters)) === FALSE) {
                            // Quote the value, it is not a parameter
                            $max = $this->quote($max);
                        }
                        // Quote the min and max value
                        $value = $min . ' AND ' . $max;
                    }  elseif ((is_string($value) AND array_key_exists($value, $this->_parameters)) === FALSE) {
                        // Quote the value, it is not a parameter
                        $value = $this->quote($value);
                    }
                    if ($column) {
                        // Apply proper quoting to the column
                        $column = $this->getField($column);
                    }
                    if ($op === 'FIND_IN_SET') {
                        $sql .= " FIND_IN_SET($value,$column)>0";
                    } else {
                        // Append the statement to the query
                        $sql .= trim($column . ' ' . $op . ' ' . $value);
                    }
                }
                $last_condition = $condition;
            }
        }

        return $sql;
    }

    public function getCountQuery() {
        $ret = "SELECT COUNT(*) AS `ctr`";
        $ret .= " FROM `" . $this->DBIconn->escape(trim($this->baseTable, "\"' ")) . "`";
        if (count($this->joinsArray) > 0) {
            $ret .= implode(" ", $this->joinsArray);
        }
        if (count($this->whereConditionsArray) > 0) {
            $ret .= " WHERE " . $this->compileConditions($this->whereConditionsArray);
        }
        if (count($this->unionArray) > 0) {
            $ret .= implode(" ", $this->unionArray);
        }
        $ret .= $this->groupBy;
        if (count($this->havingConditionsArray) > 0) {
            $ret .= " HAVING " . $this->compileConditions($this->havingConditionsArray);
        }
        return $ret;
    }

    public function getQueryArray() {
        $this->results['distinct'] = $this->distinct;
        $this->results['fields']   = $this->fieldString;
        $this->results['joins']    = $this->joinsArray;
        $this->results['where']    = $this->compileConditions($this->whereConditionsArray);
        $this->results['having']   = $this->compileConditions($this->havingConditionsArray);
        $this->results['order_by'] = $this->orderBy;
        if ($this->limit > 0) {
            if ($this->offset > 0) {
                $this->results['limit'] = " LIMIT " . $this->offset . ", " . $this->limit;
            } else {
                $this->results['limit'] = " LIMIT " . $this->limit;
            }
        } else {
            $this->results['limit'] = "";
        }
        $this->results['union']   = $this->unionArray;
        $this->results['groupBy'] = $this->groupBy;
        $this->results['having']  = $this->havingConditionsArray;
        return $this->results;
    }

    public function getQuery() {
        $ret = "SELECT ";
        if ($this->distinct != '') {
            $ret .= $this->distinct;
        }
        $ret .= $this->fieldString . " FROM `" . $this->DBIconn->escape(trim($this->baseTable, "\"' ")) . "`";
        if (count($this->joinsArray) > 0) {
            $ret .= implode(" ", $this->joinsArray);
        }
        if (count($this->whereConditionsArray) > 0) {
            $ret .= " WHERE " . $this->compileConditions($this->whereConditionsArray);
        }
        if (count($this->unionArray) > 0) {
            $ret .= implode(" ", $this->unionArray);
        }
        $ret .= $this->groupBy;
        if (count($this->havingConditionsArray) > 0) {
            $ret .= " HAVING " . $this->compileConditions($this->havingConditionsArray);
        }
        $ret .= $this->orderBy;
        if ($this->limit > 0) {
            if ($this->offset > 0) {
                $ret .= " LIMIT " . $this->offset . ", " . $this->limit;
            } else {
                $ret .= " LIMIT " . $this->limit;
            }
        }
        return $ret;
    }

    public function getObject($constructorParams = array()) {
        if ($this->whereConditionsArray) {
            return $this->DBIconn->q_1($this->getQuery(), $this->objectClass, $constructorParams);
        } else {
            throw new \Exception("At least one where condition has to be specified");
        }
    }

    public function getObjects($constructorParams = array()) {
        return $this->DBIconn->q_all($this->getQuery(), $this->objectClass, $constructorParams);
    }

}