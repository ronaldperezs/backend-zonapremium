<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarPrecioDistribuidor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->integer('precio_distribuidor')->nullable();
        });        
        DB::statement("ALTER TABLE `usuarios` MODIFY COLUMN `tipo_usuario` enum('admin','distribuidor','revendedor','usuario')NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->dropColumn('precio_distribuidor')->nullable();
        });
        DB::statement("ALTER TABLE `usuarios` MODIFY COLUMN `tipo_usuario` enum('admin','revendedor','usuario')NOT NULL");
    }
}
