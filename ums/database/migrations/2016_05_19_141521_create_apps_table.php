<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function(Blueprint $table) {
            $table->string('app_key')->unique();
            $table->text('app_name');
            $table->timestamps();
            $table->primary('app_key');
        });

        Schema::create('app_user', function(Blueprint $table) {
            $table->string('app_key');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('app_key')->references('app_key')->on('apps')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['app_key', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('apps');
        Schema::drop('app_tag');
    }
}
