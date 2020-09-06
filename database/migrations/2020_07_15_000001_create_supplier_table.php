<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->text('email');
            $table->string('shopname')->nullable();
            $table->longText('bis_info')->nullable();
            $table->string('mobile')->unique();
            $table->longText('legal_name')->nullable();
            $table->longText('address')->nullable();
            $table->longText('personalic')->nullable();
            $table->string('br_num')->nullable();
            $table->longText('nic_copy')->nullable();
            $table->tinyInteger('status');
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('supplier');
    }
}
