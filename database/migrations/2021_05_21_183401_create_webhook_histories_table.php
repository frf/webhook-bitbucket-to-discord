<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhook_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('webhook_id')->unsigned();
            $table->timestamps();
            $table->json('content')->nullable();
            $table->foreign('webhook_id')->references('id')->on('webhooks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhook_histories');
    }
}
