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
    'prefix'    => 'test_'
));

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Set the cache manager instance used by connections... (optional)
//    $capsule->setCacheManager(...);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

$capsule->bootEloquent();

require_once( __DIR__.'/database/migrations/create_post_table.php' );
require_once( __DIR__.'/database/migrations/create_comment_table.php' );
require_once( __DIR__.'/database/migrations/create_tag_table.php' );
require_once( __DIR__.'/database/migrations/create_post_tag_table.php' );
