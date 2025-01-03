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
            $table->uuid('consumers_id')->nullable();
            $table->string('name_plugin')->nullable();
            $table->string('type_plugin')->nullable();
            $table->boolean('enabled')->nullable();
            $table->string('config')->nullable();
            $table->string('applied_to')->nullable();
            $table->string('ordering')->default('Static');
            $table->string('tags')->default('-');
            $table->timestamps();

            $table->foreign('consumers_id')->references('id')->on('consumers')->onDelete('cascade');
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
