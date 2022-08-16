<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number',150);
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('staff_numbers');
    }
}
