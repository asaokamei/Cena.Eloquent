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
                'link' => array(
                    'comments' => 'comment.0.2'
                ),
            ),
            'comment.0.1' => array(
                'prop' => array(
                    'comment' => $md_comment.'1',
                ),
                'link' => array(
                    'post' => 'post.0.1'
                ),
            ),
            'comment.0.2' => array(
                'prop' => array(
                    'comment' => $md_comment.'2',
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
        $this->cm->save();

        $post_id = $this->cm->fetch('post.0.1')->getKey();
        
        /*
         * check the process.
         */
        
        // get post and check it. 
        /** @var \Post[] $posts */
        $post = \Post::find( $post_id );
        $this->assertEquals( 'Post', get_class( $post ) );
        $this->assertEquals( $md_content, $post->content );
        
        // get comment and check it.
        /** @var \Comment[] $comments */
        $comments = $post->comments;
        $this->assertEquals( true, $comments instanceof \ArrayAccess );
        $this->assertEquals( 2, count( $comments ) );
        
        $this->assertEquals( 'Comment', get_class( $comments[0] ) );
        $this->assertEquals( $md_comment, $comments[1]->comment );
        
        // is comment related to the post?
        $post2 = $comments[0]->post;
        $post3 = $comments[1]->post;
        $this->assertEquals( $post->getKey(), $post2->getKey() );
        $this->assertEquals( $post->getKey(), $post3->getKey() );
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