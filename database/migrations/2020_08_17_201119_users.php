<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('clinic_id')->nullable();
            $table->string('source_id')->nullable();
            $table->boolean('active')->default(false);
            $table->boolean('active_website')->default(false);
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('document_number')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('cpf_number')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('cbo')->nullable();
            $table->string('cnes')->nullable();
            $table->string('number_room')->nullable();
            $table->string('privete_price')->nullable();
            $table->string('privete_price_full')->nullable();
            $table->text('about')->nullable();
            $table->text('schedule_default')->nullable();
            $table->string('description_licence')->nullable();
            $table->boolean('is_licence')->default(false);
            $table->boolean('is_external_schedule')->default(false);
            $table->boolean('is_children_schedule')->default(false);
            $table->string('source')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
