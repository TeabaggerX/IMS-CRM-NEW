<?php
/**
 * Class templates
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class templates extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'templates';
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
        'affiliate'    => [],
        'body' => [],
        'url' => [],
        'affiliate_id' => [],
        'affiliate_active' => [],
        'color' => [],
        'created_at' => [],
        'updated_at' => [],
        'api' => [],
        'del' => [],
    ];

}
