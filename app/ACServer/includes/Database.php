<?php

/**
 * Created by PhpStorm.
 * User: Lisa
 * Date: 1/13/2017
 * Time: 8:57 PM
 */

namespace ACServer\includes;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $name;
    /**
     * @var PDO
     */
    private $connection;
    /**
     * @var PDOStatement
     */
    private $query;

    /**
     * Database constructor.
     *
     * @param string $DB_HOST
     * @param string $DB_USER
     * @param string $DB_PASS
     * @param string $DB_NAME
     */
    public function __construct( $DB_HOST = 'localhost', $DB_USER = 'username', $DB_PASS = 'password', $DB_NAME = 'uas2')
    {
        $this->host = $DB_HOST;
        $this->username = $DB_USER;
        $this->password = $DB_PASS;
        $this->name = $DB_NAME;
    }

    /**
     * Initialize the database connection
     * @param array $connectionOptions
     *
     * @return $this
     */
    public function connect( $connectionOptions = [])
    {
        if ( empty( $connectionOptions ) ) {
            $connectionOptions = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];
        }

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name;

        try {
            $this->connection = new PDO( $dsn, $this->username, $this->password, $connectionOptions );
        }
        catch ( PDOException $e )
        {
            //Die and tell me what's wrong so i can fix it
            die( $e->getMessage() );
        }

        return $this;
    }

    /**
     * Bind field values to the query
     *
     * @param string $field
     * @param string $value
     * @param string|null $type
     *
     * @return $this
     */
    protected function bind( $field, $value, $type = NULL )
    {
        //If we didn't pass the type of value, try and determine what was passed
        if ( is_null( $type ) ) {
            $type = PDO::PARAM_STR;

            if( is_int( $value ) ) {
                $type = PDO::PARAM_INT;
            }
            elseif( is_bool( $value ) ) {
                $type = PDO::PARAM_BOOL;
            }
            elseif( is_null( $value ) ) {
                $type = PDO::PARAM_NULL;
            }
        }

        $this->query->bindValue( $field, $value, $type );

        return $this;
    }

    /**
     * Build the query that should be run
     *
     * @param string $query
     * @param array $values
     *  Each entry in $values should at least have 'field' and 'value' parameters on it
     *
     * @return $this
     */
    public function query( $query, $values = [] )
    {
        $this->query = $this->connection->prepare( $query );

        //If field values were passed, just go ahead and bind them
        if ( is_array( $values ) && !empty( $values ) ) {
            foreach( $values as $value ) {
                if ( !isset( $value[ 'type' ] ) || empty( $value[ 'type' ] ) ) {
                    $value[ 'type' ] = NULL;
                }
                $this->bind( $value[ 'field' ], $value[ 'value' ], $value[ 'type' ] );
            }
        }

        return $this;
    }

    /**
     * Execute the query
     * @return bool
     */
    public function execute()
    {
        return $this->query->execute();
    }

    /**
     * Execute the query and return the queried data
     * @return array
     */
    public function get()
    {
        if( $this->execute() ) {
            return $this->query->fetchAll( PDO::FETCH_ASSOC );
        }

        return [];
    }

    /**
     * Execute the query and return the first row of queried data
     * @return array
     */
    public function getOne()
    {
        if( $this->execute() ) {
            return $this->query->fetch( PDO::FETCH_ASSOC );
        }

        return [];
    }

    /**
     * Get the ID of the last record inserted
     * @return string
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Alias for $this->execute()
     * @return bool
     */
    public function insert()
    {
        return $this->execute();
    }

    /**
     * Alias for $this->execute()
     * @return bool
     */
    public function update()
    {
        return $this->execute();
    }

    /**
     * Alias for $this->execute()
     * @return bool
     */
    public function delete()
    {
        return $this->execute();
    }
}
