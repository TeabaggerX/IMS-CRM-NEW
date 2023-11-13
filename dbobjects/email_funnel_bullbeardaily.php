<?php
/**
 * Class email_funnel_bullbeardaily
 *
 * @namespace DBObjects
 * @package   DBObjects
 */

class email_funnel_bullbeardaily extends DBObjectBase {

    protected static $primaryKey = 'id';
    /**
     * Corresponding database table name
     *
     * @var string
     */
    protected static $table = 'email_funnel_bullbeardaily';
    /**
     * Primary human readable title field for the object
     *
     * @var string
     */
    protected static $dbFields = [
        'id' => ['private' => true],
        'email' => ['type'      => 'varchar'],
        'sent' => [ 'type' => 'int' ],
        'timestamp' => [ 'type' => 'timestamp' ],
    ];

    //Jon Sovsky Jr's Custom Function
    public static function getAllWithLimitAndNotSent($limit, $orderBy = null, $constructorParams = array()) {
        $query = self::startQuery();

        $query->where('sent', '=', 0);
    
        $query->limit($limit);
    
        if ($orderBy) {
            $query->orderBy($orderBy);
        }
    
        return static::dbi()->q_all($query->getQuery(), "\\" . static::class, $constructorParams);
    }    
    
    

}
