<?php

/**
 *
 * DBObjectBase
 *
 * @author Jon Sovsky <jonsov@gmail.com>
 * @todo   update to handle multi column primary keys
 * @todo   document new code
 */
abstract class DBObjectBase implements \JsonSerializable {
    protected static $primaryKey  = 'id';
    protected static $table;
    protected static $dbFields    = array();
    protected static $journaled   = false;
    protected static $allowedAttr = [
        'label', // human readable label for column name
        'type', // should match the db column type
        'displays_as', // how it renders in html field
        'formats_as', // how the value formats (currency, phone number, decimal)
        'options_from', // where options come from
        'options',
        'encoding', // enum[json, base64, encrypt, serialized]
        'private', // @todo how is this intended to be used?
        'required', // is it required to save in DB
        'related_object', // class of another DBObject
        'display_field',
        'ttl', // for Redis caching @todo figure out how to use this
        'validation', // to keep validation rule for the frontend
        'calculated', // such fields will be sipped during save() if true
    ];

    /**
     * keys:
     * id: id selector
     * title: title selector
     * exclusions: enable exclusions table check
     *
     * @var array
     */
    protected static $options_options = [];
    /**
     * @var mixed used by getTitle() to lookup the title for an object by this field
     */
    protected static $title_field;
    /**
     * internal static caching
     *
     * @var $cache array
     */
    protected static $cache = [];
    protected static $problems = [];
    /**
     * use static caching
     *
     * @var bool
     */
    protected static $use_cache    = [];
    public           $customFields = [];
    protected        $toSaveCF     = [];
    public           $associatedObjects = [];
    protected        $__properties = array();
    protected        $initialized  = false;
    protected        $isUpdating  = []; // this was added to not resave everything
    private          $journalObj;

    protected static $queryfields;
    /**
     * @var false[]
     */
    protected $lastResult = ['success' => false];
    /**
     * @var static[]|array
     */
    protected $relations = [];

    public function __construct($A_ACCT=null) {
        if (static::$dbFields === array()) {
            throw new \DBObjects\DBObjectException("dbFields have to be set");
        }
        $this->isUpdating = []; // this was added to not resave everything

        /// Decode Values
        $this->decode();

        $this->initialized = true;
    }

    /**
     * @param $field
     * @return bool
     */
    public static function hasField($field) {
        return array_key_exists($field, static::$dbFields);
    }

    /**
     * @return string
     */
    public static function getPrimaryKey() {
        return static::$primaryKey;
    }

    /**
     * @param       $where
     * @param array $constructorParams
     * @return static[]
     * @throws dbiException
     */
    public static function getWhere($where, $orderBy=null, $constructorParams = array()) {
        $query = self::startQuery();
        foreach ($where as $f => $v) {
            if($v == ''){
                $query->where($f, '!=', '');
            } else {
                $query->where($f, '=', $v);
            }
        }
        if($orderBy) {
            $query->orderBy($orderBy);
        }
        return static::dbi()->q_all($query->getQuery(), "\\" . static::class, $constructorParams);
    }
    public static function getAllWhere($key, $op, $value, $orderBy=null, $constructorParams = array()) {
        $query = self::startQuery();
        $query->where($key, $op, $value);
        if($orderBy) {
            $query->orderBy($orderBy);
        }
        return static::dbi()->q_all($query->getQuery(), "\\" . static::class, $constructorParams);
    }

    public static function getWhereIN($where, $orderBy=null, $constructorParams = array()) {
        $query = self::startQuery();
        $i=1;
        foreach ($where as $f => $v) {
            if($i == 1){
                $query->where($f, 'IN', $v);
            } else {
                $query->where($f, '=', $v);
            }
            $i++;
        }
        if($orderBy) {
            $query->orderBy($orderBy);
        }
        
        return static::dbi()->q_all($query->getQuery(), "\\" . static::class, $constructorParams);
    }
    
    /**
     * @param $where
     * @param null $orderBy
     * @param array $constructorParams
     * @return static
     * @throws dbiException
     */
    public static function getOneWhere($where, $orderBy=null, $constructorParams = array()) {
        $query = self::startQuery();

        foreach ($where as $f => $v) {
            if($v == ''){
                $query->where($f, '!=', '');
            } else {
                $query->where($f, '=', $v);
            }
        }
        if($orderBy) {
            $query->orderBy($orderBy);
        }
        $query->limit(1);

        return static::dbi()->q_1($query->getQuery(), "\\" . static::class, $constructorParams);
    }

    /**
     * @return dbi
     */
    public static function dbi() {
        return \dbi::get_instance();
    }

    /**
     * @return string
     */
    public static function table() {
        return static::$table;
    }


    /**
     * @return QueryBase
     */
    public static function startQuery() {
        return new QueryBase(static::$table, "\\" . static::class);
    }

    /**
     * @param QueryBase $query
     * @param $constructorParams
     * @return static
     * @throws \DBObjects\DBObjectException
     * @throws dbiException
     */
    public static function getObject(QueryBase $query, $constructorParams = array()) {
        $q = $query->getQueryArray();
        if ($q['where'] != "") {
            return static::dbi()->q_1($query->getQuery(), static::class, $constructorParams);
        } else {
            throw new \DBObjects\DBObjectException("At least one where condition has to be specified");
        }
    }

    /**
     * @param   $query
     * @param  array     $constructorParams
     * @return static[]
     */
    public static function listObjects($query, $constructorParams = array()) {
        return static::dbi()->q_all($query->getQuery(), "\\" . get_called_class(), $constructorParams);
    }

    public static function debugQuery($query, $constructorParams = array()) {
        return $query->getQuery();
    }

    public static function getObjects($query, $constructorParams = array()) {
        return static::listObjects($query, $constructorParams);
    }

    public static function deleteWithConditions($query) {
        $q = $query->getQueryArray();
        if ($q['where'] != "") {
            try {
                $table = static::dbi()->escape(static::$table);
                static::dbi()->delete($table, $q['where']);
            } catch (dbiException $e) {
                throw new \DBObjects\DBObjectException("Could not delete record: " . $e->getMessage());
            }
        } else {
            throw new \DBObjects\DBObjectException("At least one condition should be specified");
        }
    }

    public static function updateWithConditions(QueryBase $query, $params) {
        $q = $query->getQueryArray();
        if ($q['where'] != "") {
            try {
                $table = static::dbi()->escape(static::$table);
                return static::dbi()->update($table, $params, $q['where']);
            } catch (dbiException $e) {
                throw new \DBObjects\DBObjectException("Could not update record: " . $e->getMessage());
            }
        } else {
            throw new \DBObjects\DBObjectException("At least one condition should be specified");
        }
    }

    /**
     * Creates a default label
     *
     * @param $field
     * @return mixed
     */
    private static function makeLabel($field) {
        $field = str_replace(" ", "", ucwords(str_replace("_", " ", $field)));
        return preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/', ' $0', $field);
    }

    public function associateObject($objectName, $objectId) {
        // Check whether within SQL transaction
        $this->associatedObjects[] = array("to_object_name" => $objectName, "to_object_id" => $objectId);
    }

    /**
     * alias for getRelatedObject
     *
     * @param $field
     * @return \DBObjectBase
     * @throws \DBObjects\DBObjectException
     */
    public function getFO($path) {
        return $this->getRelatedObject($path);
    }

    /**
     * gets an object by a related field
     *
     * @param $field
     * @return \DBObjectBase
     * @throws \DBObjects\DBObjectException
     */
    public function getRelatedObject($path) {
        /* @todo parse dot notation for downstream resolution */

        try {
            $parts     = explode('.', $path);
            $cnt_parts = count($parts);
            $index     = 1;
            foreach ($parts as $field) {
                if (!static::$dbFields[$field]['related_object']) {
                    throw new \DBObjects\DBObjectException("$field is not a related object");
                }

                /** Stop and return empty string if not field */
                if (empty($this->{$field})) {
                    return null;
                }

                $obj = call_user_func([
                    static::$dbFields[$field]['related_object'],
                    'getObjectByPrimaryKey',
                ], $this->{$field});

                if ($obj && $index < $cnt_parts) {
                    array_shift($parts);
                    return $obj->getRelatedObject(implode('.', $parts));
                }
                $index++;
                return $obj;
            }
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * returns the title of this object
     *
     * @return string
     * @throws \Exception
     */
    public function getTitle() {
        if (!static::$title_field || empty(static::$title_field)) {
            return ''; //throw new \DBObjects\DBObjectException("Missing or incorrect title field property");
        }

        if (is_string(static::$title_field) && !empty($this->__properties[static::$title_field])) {
            return $this->__properties[static::$title_field];
        }

        if (is_array(static::$title_field)) {
            $concat = '';
            foreach (static::$title_field as $field) {
                if (!empty(trim($field)) && array_key_exists($field, $this->__properties)) {
                    $concat .= $this->__properties[$field];
                } else {
                    $concat .= $field;
                }
            }
            return trim($concat);
        }

        return '';
    }
    
    /**
     * insert new record in database
     *
     * @param array $fields
     * @return mixed|null
     * @throws \DBObjects\DBObjectException
     */
    public static function insert($fields = []) {
        $obj = new static;
        foreach($fields as $key => $value) {
            $obj->{$key} = $value;
        }
        return $obj->save(true);
    }

    protected static function getInfo($queryType = null){
        $info = [
            'success' => false,
        ];
        preg_match('/\d/', static::dbi()->info, $result);
    
        if (isset($result[0])) {
            switch ($queryType) {
                case 'insert':
                    // Records: 100 Duplicates: 0 Warnings: 0
                    $info['success'] = $result[0] > 0;
                    break;
                case 'update':
                    // Rows matched: 40 Changed: 40 Warnings: 0
                    $info['success'] = $result[0] > 0;
                    break;
            }
        }
    
        return $info;
    }
    
    /*
    protected static function getInfo($queryType = null){
        $info = [
            'success' => false,
        ];
        preg_match('/\d/', static::dbi()->info, $result);
        switch ($queryType) {
            case 'insert':
                // Records: 100 Duplicates: 0 Warnings: 0
                $info['success'] = $result[0] > 0;
                break;
            case 'update':
                // Rows matched: 40 Changed: 40 Warnings: 0
                $info['success'] = $result[0] > 0;
                break;
        }
        return $info;
    }
    */

    public function save($insert = null) {
        $started_transaction = false;
        if (!static::dbi()->getTransactionStatus()) {
            $started_transaction = true;
            static::dbi()->start_transaction();
        }
        try {

            $params = array();

            foreach (static::$dbFields as $keyName => $attr) {
                if (isset($attr['calculated']) && $attr['calculated']) {
                    // does not need an update
                    continue;
                }
                if ($this->{$keyName} === null) {
                    if (!isset($attr['default'])) {
                        // this was added to not resave everything
                        if(($insert) && !isset($this->{static::$primaryKey}) && empty($this->{static::$primaryKey})) {
                            $params[$keyName] = null;
                        }
                    } elseif ($attr['default'] === 'timestamp') {
                        $params[$keyName] = ['NOW()', ''];
                    } elseif ($attr['default'] === 'date') {
                        $params[$keyName] = ['CURDATE()', ''];
                    } elseif ($attr['default'] !== 'database') {
                        $params[$keyName] = $attr['default'];
                    }
                } else {
                    // this was added to not resave everything
                    if(!($insert) && isset($this->{static::$primaryKey}) && !empty($this->{static::$primaryKey})){
                        if(in_array($keyName, $this->isUpdating)){
                            $params[$keyName] = $this->{$keyName};
                            $params[$keyName] = $this->transcode($keyName, $params[$keyName], 'in');
                        }
                    } else {
                        $params[$keyName] = $this->{$keyName}; // this is the old code
                        $params[$keyName] = $this->transcode($keyName, $params[$keyName], 'in');// this is the old code
                    }

                    // if ($this->journalObj instanceof \DBObjects\DBOJournal) {
                    //     $this->journalObj->stateData[$keyName]['new'] = $params[$keyName];
                    // }
                }
                if (!empty(static::$dbFields[$keyName]['required']) && $this->{$keyName} === null) {
                    // throw new \DBObjects\DBObjectException("Field is required: " . $keyName);
                }
            }

            if (count($params) > 0) {
                // we may want to manage primary key, so checking it does not tell us this is an update
                // should be refactored further to check loaded property
                if (!($insert) && isset($this->{static::$primaryKey}) && !empty($this->{static::$primaryKey})) {
                    // Update existing record/previously loaded object
                    if(method_exists(get_called_class(),'preUpdate')) {
                            // $this->preUpdate();
                    }
                    $updateResult = static::dbi()
                        ->update(static::$table, $params, array(static::$primaryKey => $this->{static::$primaryKey}));
                    $this->lastResult = static::getInfo('update');

                    $recordId = $this->{static::$primaryKey};
                    $action   = 'update';
                    if(method_exists(get_called_class(),'postUpdate')) {
                        // $this->postUpdate();
                    }
                } else {

                    if(method_exists(get_called_class(),'preInsert')) {
                            // $this->preInsert();
                    }
                    // Insert new record
                    $action                                       = 'create';
                    $recordId                                     = $this->{static::$primaryKey} = static::dbi()
                        ->insert(static::$table, $params);
                    $this->lastResult = static::getInfo('insert');
                    // $this->journalObj->stateData[$keyName]['new'] = $params[$keyName];
                    $this->{static::$primaryKey} = $recordId;
                    if(method_exists(get_called_class(),'postInsert')) {
                        // $this->postInsert();
                    }
                }
            }

            // if ($this->journalObj instanceof \DBObjects\DBOJournal) {
            //     $this->journalObj->write($action, $recordId);
            // }

            if ($recordId > 0) {
                $this->saveCustomFieldValues($recordId);
            }

            if (count($this->associatedObjects) > 0) {
                foreach ($this->associatedObjects as $associatedObjectsArr) {
                    // Save mapping
                    if ($action === 'create' && $recordId > 0) {
                        $curRecordId = $recordId;
                    } else {
                        $curRecordId = $this->{static::$primaryKey};
                    }
                    // $mapper = new \DBObjects\ObjectMapper("\\" .static::class, $curRecordId, $associatedObjectsArr['to_object_name'], $associatedObjectsArr['to_object_id']);
                    // $mapper->save();
                }
            }

            if ($started_transaction) {
                static::dbi()->commit_transaction();
            }

            if ($action === 'create' && $recordId > 0) {
                return $recordId;
            } else {
                return array_merge(array(static::$primaryKey => $this->{static::$primaryKey}), $this->__properties);
            }
        } catch (\dbiException $e) {
            if ($started_transaction) {
                static::dbi()->rollback_transaction();
            }
            throw $e;
        }
    }
    private function saveCustomFieldValues($pk) {

        foreach ($this->toSaveCF as $fid => $val) {
            $cf = $this->customFields[$fid];
            $cf->saveData($val, $pk);
        }
    }

    /**
     * set the title field if needing to be overwritten
     *
     * @param $title
     */
    public function setTitle($title) {
        static::$title_field = $title;
    }
    /**
     * Returns related object(s) using existing method with 'get' . ucfirst($name) name.
     * Object(s) is cached in local array and returned from it on any further call.
     * Relations array is cleared on refresh()
     * @param string $name relation name
     * @return static
     */
    protected function getRelation($name)
    {
        if (!key_exists($name, $this->relations)) {
            $method = 'get' . ucfirst($name);
            $this->relations[$name] = $this->$method();
        }
        return $this->relations[$name];
    }
    /* Sanitize data for writing / retreival */

    public function __get($name) {
        if (substr($name, 0, 8) == '__objcf_') {
            $fieldid = (int) str_replace('__objcf_', '', $name);
            if(array_key_exists($fieldid,$this->toSaveCF) && !$this->{static::$primaryKey}) {
                return $this->toSaveCF[$fieldid];
            }
            if (array_key_exists($fieldid, $this->customFields)) {
                return $this->customFields[$fieldid]->getValue($this->{static::$primaryKey});
            }
        }

        if (array_key_exists($name, $this->__properties)) {
            return $this->__properties[$name];
        }
        if (method_exists($this, 'get' . ucfirst($name))) {
            return $this->getRelation($name);
        }
        return null;
    }

    public function __set($name, $value) {
        if (substr($name, 0, 8) == '__objcf_') {
            $fieldid = (int) str_replace('__objcf_', '', $name);
            if (array_key_exists($fieldid, $this->customFields)) {
                $this->isUpdating[] = $name;// this was added to not resave everything
                $this->toSaveCF[$fieldid] = $value;
            }
        } else {

            if ($this->initialized) {
                $this->isUpdating[] = $name;// this was added to not resave everything
                $this->__properties[$name] = $this->sanitize($name, $value);
            } else {
                $this->__properties[$name] = $value;
            }
        }
    }

    public function __isset($name) {
        if (substr($name, 0, 8) == '__objcf_') {
            $field_id = (int) str_replace('__objcf_', '', $name);
            if ($field_id) {
                return isset($this->customFields[$field_id]);
            }
        } else {
            return isset($this->__properties[$name]);
        }
    }

    public function __unset($name) {
        unset($this->__properties[$name]);
    }

    public function __toString() {
        return $this->getTitle();
    }

    public function json() {
        return json_encode($this, JSON_PRETTY_PRINT);
    }

    public function jsonSerialize() {
        foreach ($this->__properties as $k => $v) {
            $out[$k] = $v;
        }

        foreach ($this->customFields as $fid => $field) {
            $out['__objcf_' . $fid] = $field->getValue($this->{static::$primaryKey});
        }

        return $out;
    }

    /** decode values */
    private function decode() {
        foreach (static::$dbFields as $f => $attr) {
            if (array_key_exists('encoding', static::$dbFields[$f])) {
                $this->{$f} = $this->transcode($f, $this->{$f}, 'out');
            }
        }
    }

    /**
     * Handle encoded values
     */
    private function transcode($name, $value, $dir = 'in') {
        if(isset(static::$dbFields[$name]['encoding'])) switch (static::$dbFields[$name]['encoding']) {
            case 'encrypt':
                $value = ($dir === 'in') ? $this->encrypt($value) : $this->decrypt($value);
                break;
            case 'base64':
                $value = ($dir === 'in') ? \base64_encode($value) : \base64_decode($value);
                break;
            case 'serialized':
                $value = ($dir === 'in') ? \serialize($value) : \unserialize($value);
                break;
            case 'json':
                $value = ($dir === 'in') ? \json_encode($value) : \json_decode($value);
                break;
        }

        return $value;
    }
    private function encrypt($text) {
        $Crypt = new fastCrypt();
        return $Crypt->encrypt($text);
    }
    
    private function decrypt($enc) {
        $Crypt = new fastCrypt();
        if (empty($enc)) {
            return null;
        }
        return $Crypt->decrypt($enc);
    }
    private function sanitize($name, $value) {
        if (isset(static::$dbFields[$name]['type'])) switch (static::$dbFields[$name]['type']) {
            case 'date':
                $value = date('Y-m-d', strtotime($value));
                break;

            case 'decimal':
                $value = preg_replace('/[^0-9\.\-]+/', '', $value);
                break;

            case 'datetime':
            case 'timestamp':
                if (!is_array($value)) {
                    $value = date('Y-m-d H:i:s', strtotime($value));
                }
                break;
            case 'int':
            case 'tinyint':
                $value = (int) $value;
                break;
        }

        return $value;
    }

    /**
     * Returns related records by condition.
     * @param array $condition
     * @param string $get 'all'/'first'/'last'
     * @return static|static[]
     * @throws dbiException
     */
    public static function related($condition, $get = 'all')
    {
        $query = self::startQuery();
        foreach ($condition as $field => $value) {
            $query->where($field, '=', $value);
        }
        switch ($get) {
            case 'first':
                $query->orderBy(['id' => 'ASC']);
                $query->limit(1);
                return self::dbi()->q_1($query, static::class);
            case 'last':
                $query->orderBy(['id' => 'DESC']);
                $query->limit(1);
                return self::dbi()->q_1($query, static::class);
            default:
                return self::dbi()->q_all($query, static::class);
        }
    }

    public function asArray()
    {
        return $this->__properties;
    }
}
