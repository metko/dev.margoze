<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'username');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->text('biography')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('sector')->nullable();
            $table->string('postal')->nullable();
            $table->string('city')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('phone_1')->nullable();
            $table->integer('phone_2')->nullable();
            $table->datetime('date_of_birth')->nullable();
            $table->boolean('vehiculable')->nullable();
            $table->boolean('trusted')->default(0);
            $table->boolean('professional')->default(0);
            $table->boolean('subscriber')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
