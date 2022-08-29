<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordParkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_parkings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('status');
            $table->string('code_park')->unique();
            $table->string('nopol')->unique();
            $table->dateTime('timein');
            $table->dateTime('timeout')->nullable();
            $table->string('total_bayar')->nullable();
            $table->string('paymentmethod_id')->nullable();
            $table->string('path_qr')->nullable();
            $table->string('path_barcode')->nullable();
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
        Schema::dropIfExists('record_parkings');
    }
}
