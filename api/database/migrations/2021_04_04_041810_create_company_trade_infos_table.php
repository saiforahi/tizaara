<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTradeInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_trade_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('company_basic_infos')->onDelete('cascade');
            $table->unsignedInteger('export_started_year');
            $table->foreignId('annual_revenue_id')->references('id')->on('annual_revenues')->onDelete('cascade');
            $table->foreignId('export_percent_id')->references('id')->on('export_percents')->onDelete('cascade');
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
        Schema::dropIfExists('company_trade_infos');
    }
}
