<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->integer('duration')->nullable();
            $table->integer('buffer_time')->nullable();
            $table->integer('no_of_allowed_products')->nullable();
            $table->integer('no_of_allowed_keywords')->nullable();
            $table->integer('no_of_allowed_rfq')->nullable();
            $table->integer('no_of_top_adds')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_plans');
    }
}
