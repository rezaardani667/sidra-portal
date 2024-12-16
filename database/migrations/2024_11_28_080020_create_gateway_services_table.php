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
        Schema::create('gateway_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('data_plane_id');
            $table->string('name')->unique();
            $table->string('tags')->nullable();
            $table->string('upstream_url')->nullable();
            $table->string('protocol')->nullable();
            $table->string('host')->nullable();
            $table->string('path')->nullable();
            $table->integer('port')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            // Advanced Fields
            $table->integer('retries');
            $table->integer('connect_timeout');
            $table->integer('write_timeout');
            $table->integer('read_timeout');
            $table->text('ca_certificates')->nullable();
            $table->text('client_certificate')->nullable();

            $table->foreign('data_plane_id')->references('id')->on('data_plane_nodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateway_services');
    }
};
