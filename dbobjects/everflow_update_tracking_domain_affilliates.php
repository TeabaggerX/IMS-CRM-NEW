<?php
/**
 * Class everflow_update_tracking_domain_affilliates
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class everflow_update_tracking_domain_affilliates extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'everflow_update_tracking_domain_affilliates';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $dbFields = [
        'id' => ['private' => true],
        'affiliate_id' => [],
        'name'    => ['type'      => 'varchar']
    ];

    public static function getAllFromEverflowUpdateTrackingDomainAffiliates($orderBy = null, $constructorParams = array()) {
        $query = self::startQuery();
        
        // No specific conditions to add since you want to select all records
    
        if ($orderBy) {
            $query->orderBy($orderBy);
        }
    
        return static::dbi()->q_all($query->getQuery(), "\\" . static::class, $constructorParams);
    }

    public static function getAllFromEverflowUpdateTrackingDomainAffiliatesWhere1597($orderBy = null, $constructorParams = array()) {
        $query = self::startQuery();
        $query->where('affilliate_id', '=', 1597); // Add the condition for affiliate_id = 1597
    
        if ($orderBy) {
            $query->orderBy($orderBy);
        }
    
        return static::dbi()->q_all($query->getQuery(), "\\" . static::class, $constructorParams);
    }
    

}
