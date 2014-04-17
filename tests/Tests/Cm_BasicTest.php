<?php
namespace Tests;

use Cena\Cena\CenaManager;

require_once( dirname(__DIR__).'/boot.php' );
require_once( __DIR__.'/SetUpsTrait.php' );

class Cm_BasicTest extends \PHPUnit_Framework_TestCase
{
    use SetUpsTrait;

    /**
     * @var CenaManager
     */
    public $cm;
    
    public static function setUpBeforeClass()
    {
        self::setupDb();
    }

    function setUp()
    {
        $this->cm = self::setCm();
    }

    function test0()
    {
        $this->assertEquals( 'Cena\Cena\CenaManager', get_class( $this->cm ) );
    }

    /**
     * @test
     */
    function newEntity_returns_new_entity_objects()
    {
        $entity = $this->cm->newEntity( 'post', 1 );
        $this->assertEquals( 'Post', get_class( $entity ) );
        $this->assertEquals( false, $this->cm->manipulate($entity)->isRetrieved() );
        $this->assertEquals( false, $this->cm->manipulate($entity)->isDeleted() );

        $entity = $this->cm->newEntity( 'comment', 1 );
        $this->assertEquals( 'Comment', get_class( $entity ) );
        $this->assertEquals( false, $this->cm->manipulate($entity)->isRetrieved() );
        $this->assertEquals( false, $this->cm->manipulate($entity)->isDeleted() );

        $entity = $this->cm->newEntity( 'tag', 1 );
        $this->assertEquals( 'Tag', get_class( $entity ) );
        $this->assertEquals( false, $this->cm->manipulate($entity)->isRetrieved() );
        $this->assertEquals( false, $this->cm->manipulate($entity)->isDeleted() );
    }

    /**
     * @test
     */
    function getEntity_returns_existing_entity_objects()
    {
        $entity = $this->cm->getEntity( 'post', 1 );
        $this->assertEquals( 'Post', get_class( $entity ) );
        $this->assertEquals( true,  $this->cm->manipulate($entity)->isRetrieved() );
        $this->assertEquals( false, $this->cm->manipulate($entity)->isDeleted() );

        $entity = $this->cm->getEntity( 'comment', 1 );
        $this->assertEquals( 'Comment', get_class( $entity ) );
        $this->assertEquals( true,  $this->cm->manipulate($entity)->isRetrieved() );
        $this->assertEquals( false, $this->cm->manipulate($entity)->isDeleted() );

        $entity = $this->cm->getEntity( 'tag', 1 );
        $this->assertEquals( 'Tag', get_class( $entity ) );
        $this->assertEquals( true,  $this->cm->manipulate($entity)->isRetrieved() );
        $this->assertEquals( false, $this->cm->manipulate($entity)->isDeleted() );
    }

    /**
     * @test
     */
    function relate_associates_entities()
    {
        // get post from db. 
        /** @var \Post $post */
        $post = $this->cm->getEntity( 'post', 1 );
        $this->assertEquals( 'Post', get_class( $post ) );

        // get related comments
        $comments = $post->comments;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($comments) );
        $this->assertEquals( '2', count($comments) );

        // create new comment.
        $content = 'new comment:'.mt_rand(1000,9999);
        $new_comment = $this->cm->newEntity( 'comment' );
        $new_comment->comment = $content;
        $this->cm->manipulate($new_comment)->link( 'post', $post );
        $this->cm->save();

        // is it associated? get the same post from db, again. 
        $post_check = \Post::find(1);
        $comments = $post_check->comments;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($comments) );
        $this->assertEquals( '3', count($comments) );
        $this->assertEquals( $content, $comments[2]->comment );

        // another way of relating entities
        $content = 'new comment:'.mt_rand(1000,9999);
        $new_comment = $this->cm->newEntity( 'comment' );
        $new_comment->comment = $content;
        $this->cm->manipulate($post)->link( 'comments', $new_comment );
        $this->cm->save();

        // is it associated? get the same post from db, again. 
        $post_check = \Post::find(1);
        $comments = $post_check->comments;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($comments) );
        $this->assertEquals( '4', count($comments) );
        $this->assertEquals( $content, $comments[3]->comment );
    }

    /**
     * @test
     */
    function relate_for_many2many_associates_entities()
    {
        // get post from db. 
        /** @var \Post $post */
        $post = $this->cm->getEntity( 'post', 1 );
        $tags = $post->tags;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($tags) );
        $this->assertEquals( '2', count($tags) );

        // create new tag and associate to the post (and save!)
        $content = 'tag:'.mt_rand(1000,9999);
        $tag4 = $this->cm->newEntity( 'tag' );
        $tag4->tag = $content;
        $this->cm->manipulate($post)->link( 'tags', $tag4 );

        // is it associated? get the same post from db, again. 
        $post_check = \Post::find(1);
        /** @var \Illuminate\Database\Eloquent\Collection $tags */
        $tags = $post_check->tags;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($tags) );
        $this->assertEquals( '3', count($tags) );
        $this->assertEquals( $content, $tags[2]->tag );

        // OK, get the 3rd tag that is not associated with the post.
        $tag3 = $this->cm->getEntity( 'tag', 3 );
        $this->assertEquals( 'Tag', get_class($tag3) );
        $this->assertEquals( null, $tags->find( $tag3 ) ); // it's not in the association.

        // let's associate the 3rd and 4th tags to the post. 
        $new_tags = array( $tag3, $tag4 );
        $this->cm->manipulate($post)->link( 'tags', $new_tags );

        // is it associated? get the same post from db, again. 
        $post = \Post::find(1);
        /** @var \Illuminate\Database\Eloquent\Collection $tags */
        $tags = $post->tags;
        $this->assertEquals( 'Illuminate\Database\Eloquent\Collection', get_class($tags) );
        $this->assertEquals( '2', count($tags) );
        $this->assertEquals( $content, $tags[1]->tag );
    }
}
