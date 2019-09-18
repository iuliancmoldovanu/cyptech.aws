<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('Guest User');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['guest', 'player', 'admin', 'super_admin'])
                ->default('guest');
            $table->string('email')->default('guest@email.com');
            $table->string('phone')->default('unavailable');
            $table->dateTime('last_access_at');
            $table->integer('total_login');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
