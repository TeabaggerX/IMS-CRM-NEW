<?php
/**
 * Class drafts
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class drafts extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'drafts';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */

    protected static $dbFields = [
        'id' => [],
        'title'    => ['type' => 'varchar'],
        'post_name'    => ['type' => 'varchar'],
        'post_id'    => ['type' => 'int'],
        'post_status'    => ['type' => 'varchar'],
        'affiliate_id'    => ['type' => 'int'],
        'url' => ['type' => 'varchar'],
        'html' => ['type' => 'text'],
        'del' => ['type' => 'int'],
        'timestamp' => [],
    ];

}