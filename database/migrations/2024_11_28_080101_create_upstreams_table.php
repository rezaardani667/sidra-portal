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
        Schema::create('upstreams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('upstream_host');
            $table->string('upstream_port');
            $table->string('client_certificate')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upstreams');
    }
};
