<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('direccion_e', 70);
            $table->string('email_e', 50);
            $table->string('nombre_e', 100);
            $table->string('razon_social', 100);
            $table->string('representante', 100);
            $table->string('ruc_e', 20);            
            $table->string('telefono_e', 21);
            $table->timestamps();
        });
        DB::table('empresas')->insert(array('id'=>'1', 'direccion_e'=>'Dirección', 'email_e'=>'empresa@hotmail.com',
        'nombre_e'=>'Empresa', 'razon_social'=>'Razón social', 'representante'=>'Representante', 'ruc_e'=>'RUC', 'telefono_e'=>'Teléfono'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
