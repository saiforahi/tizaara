<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('phone_number',20)->unique()->nullable();
            $table->string('photo',100)->nullable();
            $table->text('address')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('admins');
    }
}
