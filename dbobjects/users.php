<?php
/**
 * Class users
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class users extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'users';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $title_field = 'affiliate_id';
    /**
     * Definition of the object fields
     *
     * @var array
     */
    protected static $dbFields = [
        'id' => [],
        'first_name'    => [],
        'last_name' => [],
        'username' => [],
        'password' => [],
        'active' => [],
        'lastlogin' => [],
    ];
}
