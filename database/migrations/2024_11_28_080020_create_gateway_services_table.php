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
            $table->string('name')->unique();;
            $table->string('tags')->nullable();
            $table->string('upstream_url')->nullable();
            $table->string('protocol')->nullable();
            $table->string('host')->nullable();
            $table->string('path')->nullable();
            $table->integer('port')->nullable();
            $table->boolean('enabled')->default(true);
            $table->text('public_key')->nullable();
            $table->text('private_key')->nullable();
            $table->timestamps();

            // Advanced Fields
            $table->integer('retries');
            $table->integer('connect_timeout');
            $table->integer('write_timeout');
            $table->integer('read_timeout');
            $table->text('ca_certificates')->nullable();
            $table->text('client_certificate')->nullable();
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
