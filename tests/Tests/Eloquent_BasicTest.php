<?php
namespace Tests;

use Cena\Eloquent\Factory;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;

require_once( dirname(__DIR__).'/boot.php' );
require_once( __DIR__.'/SetUpsTrait.php' );

/**
 * Class Eloquent_BasicTest
 * 
 * This test ensures the models are working correctly, 
 * as well as getting used to Eloquent ORM. 
 *
 * @package Tests
 */
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
        /** @var \Post $post */
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

    /**
     * @test
     */
    function BelongsToMany()
    {
        // get post from db. 
        /** @var \Post $post */
        $post = \Post::find(1);
        $tags = $post->tags;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($tags) );
        $this->assertEquals( '2', count($tags) );
        
        // create new tag and associate to the post (and save!)
        $content = 'tag:'.mt_rand(1000,9999);
        $tag4 = new \Tag( array('tag'=>$content) );
        $post->tags()->save( $tag4 );

        // is it associated? get the same post from db, again. 
        $post = \Post::find(1);
        /** @var \Illuminate\Database\Eloquent\Collection $tags */
        $tags = $post->tags;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($tags) );
        $this->assertEquals( '3', count($tags) );
        $this->assertEquals( $content, $tags[2]->tag );
        
        // OK, get the 3rd tag that is not associated with the post.
        $tag3 = \Tag::find(3);
        $this->assertEquals( 'Tag', get_class($tag3) );
        $this->assertEquals( null, $tags->find( $tag3 ) ); // it's not in the association.
        
        // let's associate the 3rd and 4th tags to the post. 
        $new_tags = array( $tag3, $tag4 );
        $post->tags()->detach();
        $post->tags()->saveMany( $new_tags );

        // is it associated? get the same post from db, again. 
        $post = \Post::find(1);
        /** @var \Illuminate\Database\Eloquent\Collection $tags */
        $tags = $post->tags;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($tags) );
        $this->assertEquals( '2', count($tags) );
        $this->assertEquals( $content, $tags[1]->tag );
    }

    /**
     * @test
     */
    function finding_relation_types()
    {
        /** @var \Post $post */
        $post = \Post::find(1);
        $relation = $post->comments();
        $this->assertEquals( 'Illuminate\Database\Eloquent\Relations\HasMany', get_class($relation) );
        $relation = $post->tags();
        $this->assertEquals( 'Illuminate\Database\Eloquent\Relations\BelongsToMany', get_class($relation) );

        /** @var \Comment $comment */
        $comment = \Comment::find(1);
        $relation = $comment->post();
        $this->assertEquals( 'Illuminate\Database\Eloquent\Relations\BelongsTo', get_class($relation) );
    }
}
