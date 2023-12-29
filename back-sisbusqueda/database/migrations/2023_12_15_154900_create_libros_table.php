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
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->year('anio')->nullable();
            $table->unsignedTinyInteger('mes')->nullable();
            $table->unsignedTinyInteger('dia')->nullable();
            $table->date('fecha')->nullable();
            $table->string('protocolo');
            $table->unsignedBigInteger('notario_id')->nullable();
            $table->foreign('notario_id')->references('id')->on('notarios')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
