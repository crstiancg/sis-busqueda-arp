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
        Schema::create('solicituds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notario_id')->nullable();
            $table->foreign('notario_id')->references('id')->on('notarios')->nullOnDelete();
            $table->unsignedBigInteger('subserie_id')->nullable();
            $table->foreign('subserie_id')->references('id')->on('sub_series')->nullOnDelete();
            $table->unsignedBigInteger('solicitante_id')->nullable();
            $table->foreign('solicitante_id')->references('id')->on('solicitantes')->nullOnDelete();
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicituds');
    }
};
