<?php
/**
 * Created by PhpStorm.
 * User: Lisa
 * Date: 1/13/2017
 * Time: 8:59 PM
 */

namespace ACServer\Models;

use ACServer\includes\Database;

class Portal extends ACObject
{
    /**
     * @var string
     */
    protected static $table = 'gameobjects_portals';

    /**
     * Portal constructor.
     * @param array $portal
     */
    public function __construct( array $portal = [] )
    {
        $this->database = new Database();
        $this->database->connect();

        $this->mapData( $portal );
    }

    /**
     * @param $portal
     * @return bool
     */
    public function insert( $portal )
    {
        if( !$this->validate( $portal ) ) {
            return false;
        }

        $avatar = Avatar::getOne( $portal[ 'AvatarGUID' ] );
        $fields = [
            'OwnerID', 'Name', 'Description', 'color', 'min_lvl', 'max_lvl',
            'Landblock', 'Position_X', 'Position_Y', 'Position_Z',
            'Orientation_W', 'Orientation_X', 'Orientation_Y', 'Orientation_Z'
        ];
        $bindings = [];
        foreach( $fields as $field ) {
            if( isset( $portal[ $field ] ) ) {
                $bindings[] = [ 'field' => $field, 'value' => $portal[ $field ] ];
            }
            elseif( isset( $avatar[ $field ] ) ) {
                $bindings[] = [ 'field' => $field, 'value' => $avatar[ $field ] ];
            }
        }

        $query = 'INSERT INTO ' . static::$table . '(' . implode( ', ', $fields ) .
            ') VALUES(:' . implode( ', :', $fields ) . ')';

        return $this->database->query( $query, $bindings )->insert();
    }

    /**
     * @param $portal
     * @return bool
     */
    public function update( $portal )
    {
        if ( !$this->validate( $portal ) ) {
            return false;
        }
        $avatar = Avatar::getOne( $portal[ 'AvatarGUID' ] );

        $fields = [
            'Landblock', 'Position_X', 'Position_Y', 'Position_Z',
            'Orientation_W', 'Orientation_X', 'Orientation_Y', 'Orientation_Z'
        ];
        $query = 'UPDATE ' . static::$table . ' SET ';

        $bindings = [];
        $columns = [];
        foreach( $fields as $field ) {
            if( isset( $avatar[ $field ] ) ) {
                $columns[] = 'Dest_' . $field . '=:' . 'Dest_' . $field;
                $bindings[] = [ 'field' => 'Dest_' . $field, 'value' => $avatar[ $field ] ];
            }
        }

        $query .= implode(', ', $columns ) . ' WHERE ID = :id LIMIT 1';
        $bindings[] = [ 'field' => 'id', 'value' => $portal[ 'ID' ] ];

        return $this->database->query( $query, $bindings )->update();
    }

    /**
     * @param $id
     * @return array
     */
    public static function getOne( $id )
    {
        $database = new Database();
        $query = 'SELECT * FROM ' . static::$table . ' WHERE ID = :id';
        return $database->query( $query, [ [ 'field' => 'id', 'value' => $id ] ] )->getOne();
    }
}
