<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLitigesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('litiges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');

            $table->boolean('closed')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('causer_id');
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('conversation_id');

            $table->timestamps();

            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('causer_id')
                ->references('id')
                ->on('users');

            $table->foreign('conversation_id')
                ->references('id')
                ->on('glr_conversations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('litiges');
    }
}
