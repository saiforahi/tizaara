<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_factories', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->foreignId('company_id')->references('id')->on('company_basic_infos')->onDelete('cascade');
            $table->string('factory_space',250);
            $table->foreignId('staff_number_id')->references('id')->on('staff_numbers')->onDelete('cascade');
            $table->foreignId('rnd_staff_id')->references('id')->on('rnd_staff')->onDelete('cascade');
            $table->foreignId('production_line_id')->references('id')->on('production_lines')->onDelete('cascade');
            $table->foreignId('annual_output_id')->references('id')->on('annual_outputs')->onDelete('cascade');
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
        Schema::dropIfExists('company_factories');
    }
}
