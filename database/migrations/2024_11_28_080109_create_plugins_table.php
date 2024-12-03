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
        Schema::create('plugins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('gateway_id');
            $table->string('name')->nullable();
            $table->string('type_plugin')->nullable();
            $table->boolean('enabled')->nullable();
            $table->string('config')->nullable();
            $table->json('protocols')->nullable();
            $table->timestamps();

            $table->foreign('gateway_id')->references('id')->on('gateway_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};
