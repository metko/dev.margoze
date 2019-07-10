<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('comment');
            $table->decimal('note', 1, 1)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('causer_id');
            $table->unsignedBigInteger('contract_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts');
            $table->foreign('causer_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
}
