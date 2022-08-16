<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRndStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rnd_staff', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
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
        Schema::dropIfExists('rnd_staff');
    }
}
