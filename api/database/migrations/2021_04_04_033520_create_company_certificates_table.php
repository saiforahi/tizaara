<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->foreignId('company_id')->references('id')->on('company_basic_infos')->onDelete('cascade');
            $table->string('reference_number',15);
            $table->string('issued_by',150);
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->string('certificate_photo_name',150);
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
        Schema::dropIfExists('company_certificates');
    }
}
