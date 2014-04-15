<?php
namespace Tests;

use Cena\Cena\Factory as CenaFactory;
use Cena\Eloquent\Factory;
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

    /**
     * set up Eloquent ORM as global Capsule. 
     */
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

    /**
     * set up database tables; drop and create tables. 
     */
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

    /**
     * add seeds (sample data) into db tables. 
     */
    public static function addSeeds()
    {
        require_once( dirname(__DIR__).'/boot.php' );
        $seeder = new \DatabaseSeeder;
        $seeder->run();
    }

    /**
     * set up CenaManager using Eloquent-EntityManager-Adapter. 
     * 
     * @return \Cena\Cena\CenaManager
     */
    public static function setCm()
    {
        $ema = Factory::buildEmaEloquent();
        $cm  = CenaFactory::buildCenaManager( $ema );
        $cm->setClass( 'Post' );
        $cm->setClass( 'Comment' );
        $cm->setClass( 'Tag' );
        return $cm;
    }
}