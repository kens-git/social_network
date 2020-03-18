<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWallPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wall_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wall_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wall_posts');
    }
}
