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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicituds')->restrictOnDelete();
            $table->double('pago_busqueda',4,2);
            $table->double('pago_verificacion',4,2);
            $table->unsignedSmallInteger('cantidad_folio');
            $table->double('pago_folio',4,2);
            $table->unsignedSmallInteger('cantidad_fotocopia');
            $table->double('pago_fotocopia',4,2);
            $table->double('total',4,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
