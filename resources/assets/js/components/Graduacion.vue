<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Graduaciones
                        <button type="button" @click="abrirModal('graduacion','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="valor">Valor</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarGraduacion(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarGraduacion(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Valor</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="graduacion in arrayGraduacion" :key="graduacion.id">
                                    <td>
                                        <button type="button" @click="abrirModal('graduacion','actualizar',graduacion)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="graduacion.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarGraduacion(graduacion.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarGraduacion(graduacion.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="graduacion.nombre"></td>
                                    <td v-text="graduacion.valor"></td>
                                    <td>
                                        <div v-if="graduacion.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>                                
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <!-- <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de graduacion">
                                        
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Graduación (*)</label>
                                    <div class="col-md-9">
                                        <select v-model="nombre" class="form-control">
                                            <option value="">Seleccione</option>
                                            <option value="Esfera">Esfera</option>
                                            <option value="Cilindro">Cilindro</option>
                                            <option value="Eje">Eje</option>
                                            <option value="Adición">Adición</option>
                                            <option value="Distancia Interpupilar">Distancia Interpupilar</option>
                                            <option value="Agudeza Visual">Agudeza Visual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Valor (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="valor" class="form-control" placeholder="Ingrese valor" maxlength="6">
                                    </div>
                                </div>
                                <div v-show="errorGraduacion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjGraduacion" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarGraduacion()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarGraduacion()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                graduacion_id: 0,
                nombre : '',
                valor : '',
                arrayGraduacion : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorGraduacion : 0,
                errorMostrarMsjGraduacion : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,  
                    'last_page' : 0,
                    'from' : 0,     
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación.
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;

            }
        },
        methods : {
            listarGraduacion (page,buscar,criterio){
                let me=this;
                var url= '/graduacion?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayGraduacion = respuesta.graduaciones.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual.
                me.pagination.current_page = page;
                //Envia la peticion para visualizar la data de esa pagina.
                me.listarGraduacion(page,buscar,criterio);
            },
            registrarGraduacion(){
                if (this.validarGraduacion()){
                    return;
                }

                let me = this;

                axios.post('/graduacion/registrar',{
                    'nombre': this.nombre,
                    'valor': this.valor
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarGraduacion(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarGraduacion(){
                if (this.validarGraduacion()){
                    return;
                }

                let me = this;

                axios.put('/graduacion/actualizar',{
                    'nombre': this.nombre,
                    'valor': this.valor,
                    'id': this.graduacion_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarGraduacion(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            desactivarGraduacion(id){
                swal({
                title: 'Esta seguro de desactivar esta graduacion?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/graduacion/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarGraduacion(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });

                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                })
            },
            activarGraduacion(id){
                swal({
                title: 'Esta seguro de activar esta graduación?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/graduacion/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarGraduacion(1,'','nombre');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });

                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                })
            },
            validarGraduacion(){
                this.errorGraduacion=0;
                this.errorMostrarMsjGraduacion =[];

                if (!this.nombre) this.errorMostrarMsjGraduacion.push("El nombre de la graduación no puede estar vacío.");

                if (this.errorMostrarMsjGraduacion.length) this.errorGraduacion = 1;

                return this.errorGraduacion;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='',
                this.nombre='',
                this.valor='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "graduacion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Graduación';
                                this.nombre= '';
                                this.valor = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Graduación';
                                this.tipoAccion=2;
                                this.graduacion_id=data['id'];
                                this.nombre = data['nombre'];
                                this.valor= data['valor'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarGraduacion(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>