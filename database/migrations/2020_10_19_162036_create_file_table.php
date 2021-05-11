<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('application', 20)->nullable();
            $table->string('name', 350)->nullable();
            $table->string('hash_name', 350)->nullable();
            $table->string('path', 350)->nullable();
            $table->string('driver', 20)->nullable();
            $table->string('bucket', 60)->nullable();
            $table->string('type')->nullable();
            $table->string('content_type', 30)->nullable();
            $table->string('url', 350)->nullable();
            $table->json('metadata')->nullable();
            $table->bigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
