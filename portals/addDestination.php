<?php
/**
 * Created by PhpStorm.
 * User: Lisa
 * Date: 1/14/2017
 * Time: 12:28 AM
 */

require_once '../app/app.php';

use ACServer\Controllers\PortalController;

$controller = new PortalController();

$error = false;

if ( isset( $_REQUEST[ 'AvatarGUID' ] ) ) {
    $error = !$controller->edit( $_REQUEST );
}

$view->render( 'portals/addDestination.twig', $controller->destination( $error ) );