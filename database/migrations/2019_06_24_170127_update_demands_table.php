<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDemandsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('demands', function (Blueprint $table) {
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->foreign('category_id')
                ->references('id')
                ->on('demand_categories');

            $table->foreign('sector_id')
                ->references('id')
                ->on('demand_sectors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
