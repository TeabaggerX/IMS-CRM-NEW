<?php
/**
 * Class everflowAffiliate
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class everflowAffiliate extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'everflowAffiliate';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $dbFields = [
        'id' => ['private' => true],
        'affiliate_id' => [],
        'affiliate_id_encoded' => ['type'      => 'varchar'],
        'name'    => ['type'      => 'varchar'],
        'accountStatus' => ['type'      => 'varchar'],
        'global_tracking_domain_url' => ['type'      => 'varchar'],
        'timeStamp' => [ 'type' => 'timestamp' ],
    ];

}
