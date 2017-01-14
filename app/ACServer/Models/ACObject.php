<?php
/**
 * Created by PhpStorm.
 * User: Lisa
 * Date: 1/13/2017
 * Time: 9:09 PM
 */

namespace ACServer\Models;

use ACServer\includes\Database;

abstract class ACObject
{
    protected $database;

    /**
     * @param array $portal
     */
    protected function mapData( array $portal )
    {
        foreach ( $portal as $key => $value ) {
            $this->{$this->camel_case( $key )} = $value;
        }
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    public static function getAll( $fields = [ '*' ] )
    {
        $database = new Database();
        return $database->connect()->query(
            'SELECT ' . implode( ', ', $fields ) . ' FROM ' . static::$table, []
        )->get();
    }

    /**
     * @param array $fields
     * @param array $whereAnd
     *
     * @return array
     */
    public static function getWhere( $fields = [ '*' ], $whereAnd = [] )
    {
        $query = 'SELECT ' . implode( ', ', $fields ) . ' FROM ' . static::$table;
        if( !empty( $whereAnd ) ) {
            $query .= ' WHERE ' . implode( ' AND ', $whereAnd );
        }

        $database = new Database();
        return $database->connect()->query( $query )->get();
    }

    abstract public function insert( $data );

    abstract public function update( $data );

    abstract public function delete();

    public function camel_case( $value )
    {
        $value = ucwords( str_replace( [ '-', '_' ], ' ', $value ) );

        return lcfirst( str_replace( ' ', '', $value ) );
    }

    /**
     * Make sure all the fields are filled
     * @param $data
     *
     * @return bool
     */
    protected function validate( $data )
    {
        foreach( $data as $field => $value ) {
            //Because 0 is evaluating as empty for some stupid reason
            if( empty( $value ) && ( $value == '' || is_null( $value ) ) ) {
                return false;
            }
        }
        return true;
    }

}
