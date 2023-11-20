<?php
/**
 * Class everflowChannel
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class everflowChannel extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'everflow_channels';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $dbFields = [
        'id' => ['private' => true],
        'network_channel_id' => [ 'type' => 'int' ],
        'network_id'    => ['type' => 'int'],
        'name' => ['type'      => 'varchar'],
        'status' => [ 'type' => 'varchar' ],
        'del' => [ 'type' => 'int' ],
        'timestamp' => [ 'type' => '' ]
    ];

}
