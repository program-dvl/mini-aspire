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
        Schema::create('repayment_schedules', function (Blueprint $table) {
            $table->id();
            $table->float('amount', 9, 2);
            $table->date('date');
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->unsignedBigInteger('loan_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repayment_schedules');
    }
};
