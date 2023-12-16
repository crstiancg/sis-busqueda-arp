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
        Schema::create('registro_busquedas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('RS_id_derivado')->nullable();
            $table->foreign('RS_id_derivado')->references('id')->on('registro_solicituds')->nullOnDelete();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->boolean('estado');
            $table->string('cod_protocolo');
            $table->string('cod_escritura');
            $table->string('cod_folioInicial');
            $table->string('cod_folioFinal');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_busquedas');
    }
};
