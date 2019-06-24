<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('demander_owner_id');
            $table->unsignedBigInteger('candidature_owner_id');
            $table->unsignedBigInteger('demand_id');
            $table->unsignedBigInteger('candidature_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('demand_id')
                ->references('id')
                ->on('demands');
            $table->foreign('candidature_id')
                ->references('id')
                ->on('candidatures');
            $table->foreign('demander_owner_id')
                ->references('id')
                ->on('users');
            $table->foreign('candidature_owner_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
