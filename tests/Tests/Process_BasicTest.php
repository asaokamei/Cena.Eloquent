<?php
namespace Tests;

use Cena\Cena\CenaManager;
use Cena\Cena\Factory;
use Cena\Cena\Process;
use Illuminate\Database\Eloquent\Relations\HasMany;

require_once( dirname(__DIR__).'/boot.php' );
require_once( __DIR__.'/SetUpsTrait.php' );

class Process_BasicTest extends \PHPUnit_Framework_TestCase
{
    use SetUpsTrait;

    /**
     * @var CenaManager
     */
    public $cm;

    /**
     * @var Process
     */
    public $process;

    public static function setUpBeforeClass()
    {
        self::setupDb();
    }

    function setUp()
    {
        $this->cm = self::setCm();
        $this->process = Factory::buildProcess( $this->cm );
    }
    
    function getCase1()
    {
        $md_content = 'content:'.mt_rand(1000,9999);
        $md_comment = 'comment:'.mt_rand(1000,9999);
        $input = array(
            'post.0.1' => array(
                'prop' => array(
                    'title' => 'title:'.md5(uniqid()),
                    'content' => $md_content,
                ),
            ),
            'comment.0.1' => array(
                'prop' => array(
                    'comment' => $md_comment,
                ),
                'link' => array(
                    'post' => 'post.0.1'
                ),
            ),
        );
        return $input;
    }

    function test0()
    {
        $this->assertEquals( 'Cena\Cena\CenaManager', get_class( $this->cm ) );
        $this->assertEquals( 'Cena\Cena\Process',     get_class( $this->process ) );
    }

    /**
     * @test
     */
    function process_cena_input()
    {
        $input = $this->getCase1();
        $md_content = $input['post.0.1']['prop']['content'];
        $md_comment = $input['comment.0.1']['prop']['comment'];
        $this->process->process( $input );
        
        /*
         * check the process.
         */
        
        // get post and check it. 
        /** @var \Post[] $posts */
        $posts = $this->cm->getCollection()->findByModel('post');
        $this->assertEquals( true, is_array( $posts ) );
        $this->assertEquals( 1, count( $posts ) );
        
        $this->assertEquals( 'Post', get_class( $posts[0] ) );
        $this->assertEquals( $md_content, $posts[0]->content );
        
        // get comment and check it.
        /** @var HasMany $comments */
        $comments = $this->cm->getCollection()->findByModel('comment');
        $this->assertEquals( true, is_array( $comments ) );
        $this->assertEquals( 1, count( $comments ) );
        
        $this->assertEquals( 'Comment', get_class( $comments[0] ) );
        $this->assertEquals( $md_comment, $comments[0]->comment );
        
        // is comment related to the post?
        $post = $comments[0]->post;
        $this->assertEquals( 'Post', get_class( $post ) );
        $this->assertSame( $posts[0], $post );
    }

    /**
     * @test
     */
    function process_and_save()
    {
        $input = $this->getCase1();
        $md_content = $input['post.0.1']['prop']['content'];
        $md_comment = $input['comment.0.1']['prop']['comment'];
        $this->process->process( $input );
        $this->cm->save();
    }
}