<?php
if( file_exists( dirname(__DIR__).'/vendor' ) ) {
    define( 'TEST_VENDOR_DIR', dirname(__DIR__).'/vendor' );
} elseif( file_exists( dirname(dirname(dirname(dirname(__DIR__)))).'/vendor' ) ) {
    define( 'TEST_VENDOR_DIR', dirname(dirname(dirname(dirname(__DIR__)))).'/vendor' );
} else {
    die( 'Cannot find vendor directory' );
}
require_once( TEST_VENDOR_DIR . '/autoload.php' );


/*
 * read classes and files for testing. 
 */

require_once( __DIR__.'/database/migrations/create_post_table.php' );
require_once( __DIR__.'/database/migrations/create_comment_table.php' );
require_once( __DIR__.'/database/migrations/create_tag_table.php' );
require_once( __DIR__.'/database/migrations/create_post_tag_table.php' );

require_once( __DIR__.'/database/seeds/DatabaseSeeder.php' );

require_once( __DIR__.'/models/Post.php' );
require_once( __DIR__.'/models/Comment.php' );
require_once( __DIR__.'/models/Tag.php' );
require_once( __DIR__.'/models/PostTag.php' );
