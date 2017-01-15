<?php
/**
 * Created by PhpStorm.
 * User: Lisa
 * Date: 1/13/2017
 * Time: 7:48 PM
 */

namespace ACServer\Controllers;

use ACServer\Models\Avatar;
use ACServer\Models\Portal;

class PortalController
{
    /**
     * GET /portals/create.php
     * @param bool $error
     *
     * @return array
     */
    public function create( $error = false )
    {
        $avatars = Avatar::getWhere( [ 'AvatarGUID', 'name' ], [] );

        return [
            'avatars' => $avatars,
            'error' => $error
        ];
    }

    /**
     * GET /portals/addDestination.php
     * @param bool $error
     *
     * @return array
     */
    public function destination( $error = false )
    {
        $portals = Portal::getWhere( [ '*' ], [ 'Dest_Landblock IS NULL' ] );
        $avatars = Avatar::getWhere( [ 'AvatarGUID', 'name' ], [] );

        return [
            'portals' => $portals,
            'avatars' => $avatars,
            'error' => $error
        ];
    }

    /**
     * POST /portals/addDestination.php
     * @param array $data
     *
     * @return bool
     */
    public function edit( $data )
    {
        $portal = new Portal( $data );
        return $portal->update( $data );
    }

    /**
     * POST /portals/create.php
     * @param array $data
     *
     * @return bool
     */
    public function save( $data )
    {
        $portal = new Portal( $data );
        return $portal->insert( $data );
    }
}
