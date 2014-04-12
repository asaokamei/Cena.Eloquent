<?php

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call( 'PostTableSeeder' );
        $this->call( 'CommentTableSeeder' );
        $this->call( 'TagTableSeeder' );
        $this->call( 'PostTagTableSeeder' );
    }

}

class PostTableSeeder extends Seeder
{
    public function run()
    {
        DB::table( 'post' )->truncate();

        Post::create( array(
            'title'     => 'this is Laravel Blog',
            'status'    => Post::STATUS_PUBLIC,
            'content'   => 'uses Laravel Framework to demonstrate Cena technology!',
            'publishAt' => new DateTime( 'now' ),
        ) );

        Post::create( array(
            'title'     => 'Second Blog',
            'status'    => Post::STATUS_DRAFT,
            'content'   => 'Modify this content and publish me. ',
            'publishAt' => new DateTime( 'now' ),
        ) );
    }
}

class CommentTableSeeder extends Seeder
{
    public function run()
    {
        DB::table( 'comment' )->truncate();

        Comment::create( array(
            'status'  => Comment::STATUS_PUBLIC,
            'post_id' => 1,
            'comment' => 'this is a comment about Laravel Blog',
        ) );

        Comment::create( array(
            'status'  => Comment::STATUS_HIDE,
            'post_id' => 1,
            'comment' => 'this is a hidden comment about Laravel Blog',
        ) );

        Comment::create( array(
            'status'  => Comment::STATUS_PUBLIC,
            'post_id' => 2,
            'comment' => 'more comments here. ',
        ) );
    }
}

class TagTableSeeder extends Seeder
{
    public function run()
    {
        DB::table( 'tag' )->truncate();

        Tag::create( array(
            'tag' => 'php'
        ) );

        Tag::create( array(
            'tag' => 'laravel'
        ) );

        Tag::create( array(
            'tag' => 'cena'
        ) );
    }
}

class PostTagTableSeeder extends Seeder
{
    public function run()
    {
        DB::table( 'post_tag' )->truncate();

        PostTag::create( array(
            'post_id' => 1,
            'tag_id'  => 1
        ));

        PostTag::create( array(
            'post_id' => 1,
            'tag_id'  => 2
        ));

        PostTag::create( array(
            'post_id' => 2,
            'tag_id'  => 3
        ));
    }
}