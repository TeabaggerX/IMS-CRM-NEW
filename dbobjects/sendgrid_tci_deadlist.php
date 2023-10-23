<?php
/**
 * Class sendgrid_tci_deadlist
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class sendgrid_tci_deadlist extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'sendgrid_tci_deadlist';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $dbFields = [
        'id' => ['private' => true],
        'unique_id' => ['type'      => 'varchar'],
        'email' => ['type'      => 'varchar'],
        'created_at'    => ['type'      => 'varchar'],
        'segment_from_id' => ['type'      => 'varchar'],
        'segment_from_name' => [ 'type' => 'varchar' ],
        'sent_to_marketbeat' => [ 'type' => 'int' ],
        'timestamp' => [ 'type' => 'timestamp' ],
    ];

    //Jon Sovsky Jr's Custom Function
    public static function getAllWithLimitAndNotSentToMarketbeatAndSegmentEqualsDead($limit, $orderBy = null, $constructorParams = array()) {
        $query = self::startQuery();
        $query->where('sent_to_marketbeat', '=', 0); // Filter by sent_to_marketbeat = 0
        $query->where('segment_from_name', '=', 'DEAD'); // Add the new WHERE condition for segment_from_name
        $query->limit($limit);
    
        if ($orderBy) {
            $query->orderBy($orderBy);
        }
    
        return static::dbi()->q_all($query->getQuery(), "\\" . static::class, $constructorParams);
    }

    public static function getAllNotSentToMarketbeatAndSegmentEqualsMining($orderBy = null, $constructorParams = array()) {
        $query = self::startQuery();
        $query->where('sent_to_marketbeat', '=', 0); // Filter by sent_to_marketbeat = 0
        $query->where('segment_from_name', '=', 'Mining'); // Add the new WHERE condition for segment_from_name
        
        if ($orderBy) {
            $query->orderBy($orderBy);
        }
    
        return static::dbi()->q_all($query->getQuery(), "\\" . static::class, $constructorParams);
    }
    
    

}
