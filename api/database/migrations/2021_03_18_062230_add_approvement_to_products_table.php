<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovementToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false);
            $table->dateTime('approved_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->removeColumn('is_approved');
            $table->removeColumn('approved_date');
            $table->removeColumn('approved_by');
        });
    }
}
