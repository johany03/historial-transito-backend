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
        Schema::create('historial_transito', function (Blueprint $table) {
            $table->id();
            $table->string('placas')->nullable();
            $table->string('recibe')->nullable();
            $table->date('fecha_de_entrega')->nullable();
            $table->string('quien_entrega')->nullable();
            $table->string('tramite')->nullable();
            $table->text('observaciones')->nullable();
            $table->date('fecha_de_archivo')->nullable();
            $table->string('archivo')->nullable();
            $table->timestamp('fecha_de_importacion')->useCurrent();
            $table->timestamps();
            $table->softDeletes(); // Agrega el soporte para borrado suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_transito');
    }
};
