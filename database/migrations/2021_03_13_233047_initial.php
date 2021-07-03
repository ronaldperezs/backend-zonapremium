<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Initial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->bigInteger('celular');
            $table->enum('tipo_usuario', ['admin', 'revendedor', 'usuario']);
            $table->string('password');
            $table->string('api_token')->nullable();
            $table->timestamps();
        });

        Schema::create('plataformas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('planes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('duracion');
            $table->string('calidad');
            $table->string('tipo_cuenta');
            $table->string('descripcion');
            $table->integer('precio');
            $table->foreignId('plataforma_id')->constrained('plataformas');
            $table->timestamps();
        });

        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('password');
            $table->foreignId('plan_id')->constrained('planes');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('plataformas');
        Schema::dropIfExists('planes');
        Schema::dropIfExists('suscripciones');
    }
}
