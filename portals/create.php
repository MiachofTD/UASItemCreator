<?php
/**
 * Created by PhpStorm.
 * User: Lisa
 * Date: 1/13/2017
 * Time: 8:21 PM
 */

require_once '../app/app.php';

use ACServer\Controllers\PortalController;

$controller = new PortalController();

$error = false;

if ( isset( $_REQUEST[ 'AvatarGUID' ] ) ) {
    $error = !$controller->save( $_REQUEST );
}

$view->render( 'portals/create.twig', $controller->create( $error ) );