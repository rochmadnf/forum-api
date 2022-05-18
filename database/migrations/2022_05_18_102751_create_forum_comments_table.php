<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('forum_comments', function (Blueprint $table) {
      $table->id();
      $table->string('body');
      $table->string('category');
      $table->foreignId('user_id');
      $table->foreignId('forum_id');
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('forum_id')->references('id')->on('forums');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('forum_comments');
  }
};
