<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemandsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('demands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description');
            $table->text('content');
            $table->integer('postal');
            $table->string('location');
            $table->integer('budget');
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('owner_id');
            $table->datetime('be_done_at');
            $table->datetime('valid_until');
            $table->boolean('contracted');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('demands');
    }
}
