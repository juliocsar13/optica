<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcliente')->unsigned();
            $table->foreign('idcliente')->references('id')->on('personas');
            $table->integer('idusuario')->unsigned();
            $table->foreign('idusuario')->references('id')->on('users');
            $table->string('tipo_comprobante', 20);
            $table->string('serie_comprobante', 7)->nullable();
            $table->string('num_comprobante', 10);
            $table->dateTime('fecha_hora');
            $table->decimal('impuesto', 4, 2);
            $table->decimal('total', 11, 2);
            $table->string('estado', 20);            

            $table->string('esfera', 6)->nullable();
            $table->string('cilindro', 6)->nullable();
            $table->string('eje', 4)->nullable();
            $table->string('add', 6)->nullable();
            $table->string('dip', 10)->nullable();
            $table->string('av', 10)->nullable();
            $table->string('prisma', 10)->nullable();

            $table->string('esfera2', 6)->nullable();
            $table->string('cilindro2', 6)->nullable();
            $table->string('eje2', 4)->nullable();
            $table->string('av2', 10)->nullable();
            $table->string('prisma2', 10)->nullable();

            $table->integer('idproveedor')->unsigned()->nullable();
            $table->foreign('idproveedor')->references('id')->on('proveedores');
            $table->string('referencia', 50)->nullable();

            $table->string('puente', 10)->nullable();
            $table->string('hor', 10)->nullable();
            $table->string('vert', 10)->nullable();          
            $table->string('diag', 10)->nullable();

            $table->string('color', 20)->nullable();
            $table->string('efecto', 20)->nullable();
            $table->string('tono', 20)->nullable();
            $table->string('angulo_pantoscopio', 20)->nullable();
            $table->string('ang_curvatura', 10)->nullable();
            $table->string('dist_lectura', 10)->nullable();
            $table->string('st', 10)->nullable();
            $table->string('he', 10)->nullable();

            $table->decimal('adelanto', 11, 2)->nullable();
            $table->decimal('pendiente', 11, 2)->nullable();
            $table->string('forma_pago', 8);

            $table->decimal('adelanto_v', 11, 2)->nullable();

            $table->integer('idsucursal')->unsigned();
            $table->foreign('idsucursal')->references('id')->on('sucursales');

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
        Schema::dropIfExists('ventas');
    }
}