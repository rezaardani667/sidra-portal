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
        Schema::create('plugin_service_route', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('plugins_id');
            $table->uuid('gateway_id');
            $table->uuid('routes_id');
            $table->timestamps();

            $table->foreign('plugins_id')->references('id')->on('plugins')->onDelete('cascade');
            $table->foreign('gateway_id')->references('id')->on('gateway_services')->onDelete('cascade');
            $table->foreign('routes_id')->references('id')->on('routes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugin_service_route');
    }
};
