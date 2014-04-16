<?php
namespace Tests;

use Cena\Eloquent\Factory;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;

require_once( dirname(__DIR__).'/boot.php' );
require_once( __DIR__.'/SetUpsTrait.php' );

class Eloquent_BasicTest extends \PHPUnit_Framework_TestCase
{
    use SetUpsTrait;
    
    public $ema;
    
    public static function setUpBeforeClass()
    {
        self::setupDb();
    }
    
    function setUp()
    {
        $this->ema = Factory::buildEmaEloquent();
    }
    
    function test0()
    {
        $entity = \Post::find(1);
        $this->assertTrue( $entity instanceof Eloquent );
        $this->assertEquals( 'this is Laravel Blog', $entity->title );
        
        $comments = $entity->comments;
        $this->assertTrue( $comments instanceof Collection );
        $this->assertEquals( 2, count( $comments ) );

        $this->assertEquals( 'this is a comment about Laravel Blog', $comments[0]->comment );
        $this->assertEquals( 'this is a hidden comment about Laravel Blog', $comments[1]->comment );
    }

    /**
     * @test
     */
    function Factory_method_returns_EmaEloquent()
    {
        $this->assertEquals( 'Cena\Eloquent\EmaEloquent', get_class( $this->ema ) );
    }

    /**
     * @test
     */
    function belongsTo_relation_using_associate_and_save()
    {
        // get post from db. 
        $post = \Post::find(1);
        $this->assertEquals( 'Post', get_class( $post ) );
        
        // get related comments
        $comments = $post->comments;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($comments) );
        $this->assertEquals( '2', count($comments) );
        
        // create new comment.
        $content = 'new comment:'.mt_rand(1000,9999);
        $new_comment = new \Comment( [ 'comment' => $content ] );
        $new_comment->post()->associate( $post );
        $new_comment->save();
        
        // is it associated? get the same post from db, again. 
        $post = \Post::find(1);
        $comments = $post->comments;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($comments) );
        $this->assertEquals( '3', count($comments) );
        $this->assertEquals( $content, $comments[2]->comment );

        // another way of relating entities
        $content = 'new comment:'.mt_rand(1000,9999);
        $new_comment = new \Comment( [ 'comment' => $content ] );
        $post->comments()->save( $new_comment );
        $post->save();

        // is it associated? get the same post from db, again. 
        $post = \Post::find(1);
        $comments = $post->comments;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($comments) );
        $this->assertEquals( '4', count($comments) );
        $this->assertEquals( $content, $comments[3]->comment );
    }
}
