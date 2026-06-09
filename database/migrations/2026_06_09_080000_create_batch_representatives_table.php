<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_representatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumni_id')->nullable(); // reference to alumni
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('batch', 20);
            $table->string('mobile', 20)->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('alumni_id')->references('id')->on('alumni')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_representatives');
    }
};
