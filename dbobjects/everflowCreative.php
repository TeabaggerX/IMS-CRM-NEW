<?php
/**
 * Class everflowCreative
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class everflowCreative extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'everflowCreative';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $dbFields = [
        'id' => ['private' => true],
        'offer_creative_id' => [ 'type' => 'int' ],
        'name' => [ 'type' => 'varchar' ],
        'offer_id'    => ['type'      => 'int'],
        'offer_name' => ['type'      => 'varchar'],
        'creative_type' => [ 'type' => 'varchar' ],
        'is_private' => [ 'type' => 'int' ],
        'creative_status' => [ 'type' => 'varchar' ],
        'html_code' => [ 'type' => 'varchar' ],
        'TimeStamp' => [],
    ];

}
