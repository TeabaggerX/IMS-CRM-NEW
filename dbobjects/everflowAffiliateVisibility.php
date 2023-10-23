<?php
/**
 * Class everflowAffiliateVisibility
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class everflowAffiliateVisibility extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'everflowAffiliateVisibility';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $dbFields = [
        'id' => ['private' => true],
        'unique_key' => ['type'      => 'int'],
        'offer_id'    => ['type'      => 'int'],
        'affiliate_id' => ['type'      => 'int'],
        'del' => ['type'      => 'int'],
        'timestamp' => [ 'type' => 'timestamp' ],
    ];

}
