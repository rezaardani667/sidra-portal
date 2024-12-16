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
            $table->string('methods')->nullable();
            $table->string('paths')->nullable();
            $table->string('path_type')->nullable();
            $table->integer('port')->nullable();
            $table->string('snis')->nullable();
            $table->string('headers')->nullable();
            $table->text('expression')->nullable();
            $table->timestamps();

            // Advanced Fields
            $table->string('path_handling')->default('v0');
            $table->integer('https_redirect_status_code')->default(426);
            $table->integer('regex_priority')->default(0);
            $table->boolean('strip_path')->default(true);
            $table->boolean('preserve_host')->default(false);
            $table->boolean('request_buffering')->default(true);
            $table->boolean('response_buffering')->default(true);

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
