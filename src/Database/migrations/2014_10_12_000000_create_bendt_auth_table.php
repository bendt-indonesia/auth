<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBendtAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description', 500)->nullable();
            $table->timestamps();

            $table->unique('name');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description', 500)->nullable();
            $table->timestamps();

            $table->unique('name');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_group_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_root')->default(false);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_group_id')->references('id')->on('role_group')->onDelete('restrict');
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('role_group_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->integer('role_group_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
            $table->foreign('role_group_id')->references('id')->on('role_group')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_group_pivot');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_group');
    }
}
