<?php
/**
 * Class everflowReporting
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class everflowReporting extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'everflowReporting';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $dbFields = [
        'id' => ['private' => true],
        'alternate_id' => ['type'      => 'varchar'],
        'category_id' => ['type'      => 'int'],
        'category' => ['type'      => 'varchar'],
        'reporting_date' => ['type'      => 'date'],
        'reporting_date_epoch' => ['type'      => 'varchar'],
        'offer_id'    => ['type'      => 'int'],
        'creative'    => ['type'      => 'varchar'],
        'creative_id'    => ['type'      => 'int'],
        'advertiser_id' => ['type'      => 'int'],
        'offer_url' => ['type'      => 'varchar'],
        'offer_url_id' => [ 'type' => 'int' ],
        'affiliate_id' => [ 'type' => 'int' ],
        'imp' => [ 'type' => 'int' ],
        'totalClick' => [ 'type' => 'int' ],
        'uniqueClick' => [ 'type' => 'int' ],
        'invalidClick' => [ 'type' => 'int' ],
        'duplicateClick' => [ 'type' => 'int' ],
        'grossClick' => [ 'type' => 'int' ],
        'ctr' => [ 'type' => 'int' ],
        'cv' => [ 'type' => 'int' ],
        'invalidCvScrub' => [ 'type' => 'int' ],
        'viewThroughCv' => [ 'type' => 'int' ],
        'totalCv' => [ 'type' => 'int' ],
        'event' => [ 'type' => 'int' ],
        'cvr' => [ 'type' => 'decimal' ],
        'evr' => [ 'type' => 'decimal' ],
        'cpc' => [ 'type' => 'decimal' ],
        'cpm' => [ 'type' => 'decimal' ],
        'cpa' => [ 'type' => 'decimal' ],
        'epc' => [ 'type' => 'decimal' ],
        'rpc' => [ 'type' => 'decimal' ],
        'rpa' => [ 'type' => 'decimal' ],
        'rpm' => [ 'type' => 'decimal' ],
        'payout' => [ 'type' => 'decimal' ],
        'revenue' => ['type'      => 'decimal'],
        'eventRevenue' => ['type'      => 'decimal'],
        'grossSales' => [ 'type' => 'decimal' ],
        'profit' => [ 'type' => 'decimal' ],
        'margin' => [ 'type' => 'decimal' ],
        'roas' => [ 'type' => 'decimal' ],
        'avgSaleValue' => [ 'type' => 'decimal' ],
        'timestamp' => [ 'type' => 'timestamp' ],
    ];
}