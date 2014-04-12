<?php
namespace Tests;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

trait SetUpsTrait 
{
    public static function setupDb()
    {
        static::setUpEloquent();
        static::setDbTables();
        static::addSeeds();
    }

    public static function setUpEloquent()
    {

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

        $capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        $capsule->bootEloquent();

    }
    
    public static function setDbTables()
    {
        $tables = array(
            '\CreatePostTable',
            '\CreateCommentTable',
            '\CreateTagTable',
            '\CreatePostTagTable',
        );
        foreach( $tables as $t ) {
            $post = new $t;
            $post->down();
            $post->up();
        }
    }
    
    public static function addSeeds()
    {
        require_once( dirname(__DIR__).'/boot.php' );
        $seeder = new \DatabaseSeeder;
        $seeder->run();
    }
}