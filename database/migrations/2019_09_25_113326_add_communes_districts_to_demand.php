<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommunesDistrictsToDemand extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('demands', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('commune_id')->nullable();

            $table->foreign('commune_id')
                ->references('id')
                ->on('communes');

            $table->foreign('district_id')
                ->references('id')
                ->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('demand', function (Blueprint $table) {
        });
    }
}
