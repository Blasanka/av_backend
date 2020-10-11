<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->string('mobile')->unique();
            $table->text('email')->unique();
            $table->string('password');
            $table->string('username');
            $table->longText('verify_code')->nullable();
            $table->longText('full_name')->nullable();
            $table->longText('dob')->nullable();
            $table->longText('gender')->nullable();
            $table->longText('address')->nullable();
            $table->longText('nic')->nullable();
            // $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('role_id');
            // $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            // $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('mobile')->references('mobile_number')->on('verify_code')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
