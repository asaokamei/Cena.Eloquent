<?php
namespace Tests;

class Eloquent_BasicTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        require_once( dirname(__DIR__).'/boot.php' );
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
        $seeder = new \DatabaseSeeder;
        $seeder->run();
    }
    
    function test0()
    {
        
    }
}
