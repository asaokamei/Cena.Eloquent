<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{

    protected $table = 'comment';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Capsule::schema()->create( $this->table, function ( $table ) {
            /** @var Blueprint $table */
            $table->increments( 'comment_id' );
            $table->integer( 'post_id' );
            $table->integer( 'status' );
            $table->string( 'comment', 1024 * 10 );
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Capsule::schema()->dropIfExists( $this->table );
    }

}
