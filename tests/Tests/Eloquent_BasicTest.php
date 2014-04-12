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
}
