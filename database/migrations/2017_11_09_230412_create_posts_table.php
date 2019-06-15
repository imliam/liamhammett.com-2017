<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->text('text');
            $table->text('styles')->nullable();
            $table->text('scripts')->nullable();
            $table->datetime('publish_date')->nullable();
            $table->boolean('published')->default(false);
            $table->boolean('tweet_sent')->default(false);
            $table->boolean('blog_content')->default(false);
            $table->string('author')->default('Liam Hammett');
            $table->string('external_url')->nullable();
            $table->timestamps();
        });
    }
}
