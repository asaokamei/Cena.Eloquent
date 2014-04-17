<?php
namespace Tests;

use Cena\Eloquent\EmaEloquent;
use Cena\Eloquent\Factory;
use Illuminate\Database\Eloquent\Model as Eloquent;

require_once( dirname(__DIR__).'/boot.php' );
require_once( __DIR__.'/SetUpsTrait.php' );


class EmaEloquent_BasicTest extends \PHPUnit_Framework_TestCase
{
    use SetUpsTrait;

    /**
     * @var EmaEloquent
     */
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
        $this->assertEquals( 'Cena\Eloquent\EmaEloquent', get_class( $this->ema ) );
    }

    /**
     * @test
     */
    function newEntity_creates_new_entity()
    {
        $entity = $this->ema->newEntity( 'Post' );
        $this->assertEquals( 'Post', get_class( $entity ) );
        $this->assertTrue( $entity instanceof Eloquent );
    }

    /**
     * @test
     */
    function findEntity_returns_Post_entity()
    {
        $entity = $this->ema->findEntity( 'Post', 1 );
        $this->assertEquals( 'Post', get_class( $entity ) );
        $this->assertTrue( $entity instanceof Eloquent );
        $this->assertEquals( 'this is Laravel Blog', $entity->title );
    }

    /**
     * @test
     */
    function getKey_returns_primary_key()
    {
        $entity = $this->ema->findEntity( 'Post', 1 );
        $this->assertEquals( '1', $this->ema->getId( $entity ) );
        $this->assertEquals( $entity->getKey(), $this->ema->getId( $entity ) );
    }
    
    /**
     * @test
     */
    function save_new_and_retrieved_entities()
    {
        // new entity
        /** @var \Post $new1 */
        $new1 = $this->ema->newEntity( 'Post' );
        $new1->title = 'save_new_and_retrieved_entities:new1';
        $get1 = $this->ema->findEntity( 'Post', 1 );
        $get1->title = 'save_new_and_retrieved_entities:get1';
        
        // save
        $this->ema->save();
        
        // get them from db
        $new1a = \Post::find( $new1->getKey() );
        $get1a = \Post::find( $get1->getKey() );

        $this->assertEquals( $new1->title, $new1a->title );
        $this->assertEquals( $get1->title, $get1a->title );
        $this->assertNotSame( $new1, $new1a );
        $this->assertNotSame( $get1, $get1a );
    }

    /**
     * @test
     */
    function isRetrieved_returns_true_for_entity_from_db()
    {
        $entity = $this->ema->findEntity( 'Post', 1 );
        $this->assertEquals( true, $this->ema->isRetrieved( $entity ) );
    }
    
    /**
     * @test
     */
    function isRetrieved_returns_false_for_new_entity()
    {
        $entity = $this->ema->newEntity( 'Post', 1 );
        $this->assertEquals( false, $this->ema->isRetrieved( $entity ) );
    }

    /**
     * @test
     */
    function delete_removes_data_from_db()
    {
        $entity = $this->ema->findEntity( 'Post', 1 );
        $this->assertEquals( true, $this->ema->isRetrieved( $entity ) );
        $this->ema->deleteEntity( $entity );
        $this->assertEquals( true, $this->ema->isDeleted( $entity ) );
        $this->ema->save();

        $entity = \Post::find( 1 );
        $this->assertEquals( null, $entity );
    }

    /**
     * @test
     */
    function isCollection_returns_true_for_non_entities()
    {
        $entity = $this->ema->findEntity( 'Post', 2 );
        $this->assertEquals( false, $this->ema->isCollection( $entity ) );

        $this->assertEquals( true, $this->ema->isCollection( null ) );
        $this->assertEquals( true, $this->ema->isCollection( array() ) );
    }
    
}