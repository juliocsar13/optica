<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('razon_social_s', 100);
            $table->string('tipo_documento_s', 20);
            $table->string('num_documento_s', 20);
            $table->string('direccion_s', 70);
            $table->string('telefono_s', 21);
            $table->string('email_s', 50)->nullable();
            $table->string('representante_s', 100)->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
        DB::table('sucursales')->insert(array('id'=>'1', 'razon_social_s'=>'Sucursal 1', 'tipo_documento_s'=>'RUC', 
        'num_documento_s'=>'12345678', 'direccion_s'=> 'La Molina', 'telefono_s'=>'051212121', 'estado'=>'1'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucursales');
    }
}