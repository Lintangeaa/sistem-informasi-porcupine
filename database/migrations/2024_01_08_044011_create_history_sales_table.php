<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->bigInteger('accepted_weight')->nullable(false);
            $table->bigInteger('accepted_price')->nullable(false);
            $table->bigInteger('accepted_total')->nullable(false);
            $table->enum('status', ['accepted', 'decline'])->default('accepted');
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales_proposals')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_sales');
    }
};
