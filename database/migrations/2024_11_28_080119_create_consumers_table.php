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
        Schema::create('consumers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('gateway_id');
            $table->uuid('plugin_id')->nullable();
            $table->string('username')->nullable();
            $table->string('custom_id')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();

            $table->foreign('gateway_id')->references('id')->on('gateway_services')->onDelete('cascade');
            $table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers');
    }
};
