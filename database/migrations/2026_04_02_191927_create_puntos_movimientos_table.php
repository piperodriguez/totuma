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
        Schema::create('puntos_movimientos', function (Blueprint $table) {
            $table->id();
            // Relación con la tabla users
            // Si se borra el usuario, se borra su historial (onDelete cascade)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Datos del movimiento
            $table->integer('cantidad'); // Ejemplo: 100 o -50
            $table->string('tipo'); // 'acumulacion' o 'redencion'
            $table->string('descripcion')->nullable(); // Ej: "Bono de bienvenida" o "Compra Factura #123"
            // Referencia opcional para cruzar con Loggro
            $table->string('referencia_loggro')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntos_movimientos');
    }
};
