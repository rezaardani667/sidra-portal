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
            $table->string('host_header');
            $table->string('client_certificate');
            $table->string('tags');
            $table->string('algorithm');
            $table->string('slots');
            $table->string('has_on');
            $table->string('hash_fallback');
            $table->string('health_check');
            $table->string('healthchecks_threshold');
            $table->timestamps();
        });
    }
    // protected $fillable = ['name','host_header','client_certificate','tags','algorithm','slots','has_on','hash_fallback'];


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upstreams');
    }
};
