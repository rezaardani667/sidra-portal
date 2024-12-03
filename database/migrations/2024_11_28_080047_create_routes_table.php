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
        Schema::create('routes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('gateway_id');
            $table->string('name')->nullable();
            $table->string('tags')->nullable();
            $table->string('protocol')->nullable();
            $table->string('host')->nullable();
            $table->string('methods')->nullable();
            $table->string('path')->nullable();
            $table->text('expression')->nullable();
            $table->timestamps();

            $table->foreign('gateway_id')->references('id')->on('gateway_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
