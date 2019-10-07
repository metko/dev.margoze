<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('demands_valid_for');
            $table->integer('urgence_status_count');
            $table->integer('photos_demand_count');
            $table->integer('offers_per_month');
            $table->integer('offers_valid_for');
            $table->integer('candidatures_count');
            $table->integer('contracts_count');

            $table->unsignedBigInteger('owner_id');

            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('credits');
    }
}
