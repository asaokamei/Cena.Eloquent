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
}
