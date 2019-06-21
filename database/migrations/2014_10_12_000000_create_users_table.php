<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->text('biography')->nullable();
            $table->string('adress_1')->nullable();
            $table->string('adress_2')->nullable();
            $table->string('sector')->nullable();
            $table->integer('postal')->nullable();
            $table->string('city')->nullable();
            $table->string('avatar')->nullable();
            $table->bigInteger('phone_1')->nullable();
            $table->bigInteger('phone_2')->nullable();
            $table->datetime('date_of_birth')->nullable();

            $table->boolean('vehiculable')->default(false);
            $table->boolean('suspended')->default(false);
            $table->boolean('banned')->default(false);
            $table->boolean('profesionnal')->default(false);
            $table->boolean('subscriber')->default(false);

            $table->rememberToken();
            $table->datetime('last_signin_at')->nullable();
            $table->datetime('last_active_at')->nullable();
            $table->string('last_ip')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
