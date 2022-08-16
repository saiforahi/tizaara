<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportPercentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_percents', function (Blueprint $table) {
            $table->id();
            $table->string('percent',150);
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
        Schema::dropIfExists('export_percents');
    }
}
