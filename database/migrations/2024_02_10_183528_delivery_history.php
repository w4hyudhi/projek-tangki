<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_histories', function (Blueprint $table) {

            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->dateTime('time');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('location');
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
        Schema::dropIfExists('delivery_histories');
    }
};
