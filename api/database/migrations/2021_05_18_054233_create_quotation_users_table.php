<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');//supplier user id store here
            $table->tinyInteger('status')->default(0);
            $table->text('note')->nullable();
            $table->boolean('is_sale')->default(0);
            $table->timestamp('response_at')->nullable();
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
        Schema::dropIfExists('quotation_users');
    }
}
