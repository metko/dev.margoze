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
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('category_id');

            $table->foreign('status_id')
                ->references('id')
                ->on('demand_status');

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
