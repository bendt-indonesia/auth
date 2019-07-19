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
        Schema::create('module_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 80);
            $table->string('slug', 80)->unique();
            $table->string('icon', 80)->nullable();
            $table->timestamps();
        });

        Schema::create('module', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 80);
            $table->string('slug', 80)->unique();
            $table->unsignedInteger('group_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('module_group')->onDelete('cascade');
        });

        Schema::create('module_attribute', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('module_id');
            $table->string('name',150);
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('module')->onDelete('cascade');
        });

        Schema::create('role_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description', 500)->nullable();
            $table->timestamps();

            $table->unique('name');
        });

        Schema::create('role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description', 500)->nullable();
            $table->string('type', 500)->nullable();
            $table->unsignedInteger('module_id')->nullable();
            $table->timestamps();

            $table->unique('name');
            $table->foreign('module_id')->references('id')->on('module')->onDelete('restrict');
        });

        Schema::create('role_group_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->unsigned();
            $table->unsignedInteger('role_group_id')->unsigned();
            $table->boolean('is_visible')->default(true);

            $table->foreign('role_id')->references('id')->on('role')->onDelete('restrict');
            $table->foreign('role_group_id')->references('id')->on('role_group')->onDelete('cascade');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_group_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('username')->nullable();
            $table->text('bookmark')->nullable();
            $table->text('settings')->nullable();
            $table->unsignedTinyInteger('status_id')->default(1);
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('role_group_pivot');
        Schema::dropIfExists('users');
        Schema::dropIfExists('role');
        Schema::dropIfExists('role_group');
        Schema::dropIfExists('module_attribute');
        Schema::dropIfExists('module');
        Schema::dropIfExists('module_group');
    }
}
