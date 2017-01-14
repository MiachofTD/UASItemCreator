<?php
/**
 * Created by PhpStorm.
 * User: Lisa
 * Date: 1/13/2017
 * Time: 7:41 PM
 */

namespace ACServer\Models;

use ACServer\includes\Database;

class Avatar extends ACObject
{
    protected static $table = 'avatar';
    protected static $locationTable = 'avatar_location';

    public function __construct( array $portal = [] )
    {
        $this->database = new Database();
        $this->database->connect();

        $this->mapData( $portal );
    }

    public function insert( $data )
    {

    }

    public function update( $data )
    {

    }

    public function delete() {}

    /**
     * @param $id
     * @return array
     */
    public static function getOne( $id )
    {
        $database = new Database();
        $query = 'SELECT avatar.AvatarGUID, avatar.name as avatarName, location.*
            FROM ' . static::$table . ' AS avatar
            LEFT JOIN ' . static::$locationTable . ' as location 
                ON avatar.AvatarGUID = location.AvatarGUID
            WHERE avatar.AvatarGUID = :id';
        return $database->connect()->query( $query, [ [ 'field' => 'id', 'value' => $id ] ] )->getOne();
    }
}
