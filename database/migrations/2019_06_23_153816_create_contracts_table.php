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
            $table->string('title')->nullable();

            $table->dateTime('validated_at')->nullable();
            $table->dateTime('be_done_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->boolean('wait_for_validate')->default(false);
            $table->unsignedBigInteger('last_propose_by')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();

            $table->unsignedBigInteger('demand_owner_id');
            $table->unsignedBigInteger('candidature_owner_id');
            $table->unsignedBigInteger('demand_id');
            $table->unsignedBigInteger('candidature_id');
            $table->unsignedBigInteger('conversation_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('demand_id')
                ->references('id')
                ->on('demands');
            $table->foreign('candidature_id')
                ->references('id')
                ->on('candidatures');
            $table->foreign('demand_owner_id')
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
