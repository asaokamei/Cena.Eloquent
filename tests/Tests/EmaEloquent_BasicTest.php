<?php
namespace Tests;

use Cena\Eloquent\EmaEloquent;
use Cena\Eloquent\Factory;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;

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
    function Ema_newEntity_creates_new_entity()
    {
        $entity = $this->ema->newEntity( 'Post' );
        $this->assertEquals( 'Post', get_class( $entity ) );
        $this->assertTrue( $entity instanceof Eloquent );
    }
}