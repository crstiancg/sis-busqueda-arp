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
            $table->string('otorgantes');
            $table->string('favorecidos');
            $table->date('fecha');
            $table->string('bien')->nullable();
            $table->text('mas_datos')->nullable();
            $table->string('tipo_copia')->nullable();
            $table->char('ubigeo_cod', 6)->nullable();
            $table->foreign('ubigeo_cod')->references('codigo')->on('ubigeos')->nullOnDelete();
            $table->string('estado');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
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
