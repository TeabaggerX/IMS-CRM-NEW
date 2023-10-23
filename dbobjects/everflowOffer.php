<?php
/**
 * Class everflowOffer
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class everflowOffer extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'everflowOffer';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $dbFields = [
        'id' => ['private' => true],
        'offer_id' => [ 'type' => 'int' ],
        'offer_id_encoded' => [ 'type' => 'varchar' ],
        'advertiser_id'    => ['type' => 'int'],
        'name' => ['type'      => 'varchar'],
        'offer_status' => [ 'type' => 'varchar' ],
        'visibility' => [ 'type' => 'varchar' ],
        'advertiser_name' => [ 'type' => 'varchar' ],
        'category' => [ 'type' => 'varchar' ],
        'channels' => [ 'type' => 'varchar' ],
        'channel_id' => [ 'type' => 'inc' ],
        'payout_type' => [ 'type' => 'varchar' ],
        'revenue_type' => [ 'type' => 'varchar' ],
        'del' => [ 'type' => 'int' ],
        'timestamptest' => [ 'type' => 'timestamp' ],
    ];

}
