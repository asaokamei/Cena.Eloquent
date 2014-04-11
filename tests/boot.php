<?php
if( file_exists( dirname(__DIR__).'/vendor' ) ) {
    define( 'TEST_VENDOR_DIR', dirname(__DIR__).'/vendor' );
} elseif( file_exists( dirname(dirname(dirname(dirname(__DIR__)))).'/vendor' ) ) {
    define( 'TEST_VENDOR_DIR', dirname(dirname(dirname(dirname(__DIR__)))).'/vendor' );
} else {
    die( 'Cannot find vendor directory' );
}
require_once( TEST_VENDOR_DIR . '/autoload.php' );

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection(array(
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'cena_laravel',
    'username'  => 'admin',
    'password'  => 'admin',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
));

$capsule->bootEloquent();
