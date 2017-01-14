<?php

define( 'APP_DIRECTORY', __DIR__ . '/..' );

require_once APP_DIRECTORY . '/vendor/autoload.php';

use ACServer\TemplateEngines\TwigFunctions;
use ACServer\TemplateEngines\Twig as View;

$view = new View( '', [ 'debug' => true ] );
$view->addExtension( new TwigFunctions() );
$view->addExtension( new Twig_Extension_Debug() );