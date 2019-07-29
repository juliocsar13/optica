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
                        <i class="fa fa-align-justify"></i> Empresa                        
                        <button type="button" @click="abrirModal('empresa','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">                        
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Direccion</th>
                                    <th>Email</th>
                                    <th>Razon Social</th>
                                    <th>Representante</th>
                                    <th>RUC</th>
                                    <th>Telefono</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="empresa in arrayEmpresa" :key="empresa.id">
                                    <td>
                                        <button type="button" @click="abrirModal('empresa','actualizar',empresa)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;                                        
                                    </td>
                                    <td v-text="empresa.nombre_e"></td>
                                    <td v-text="empresa.direccion_e"></td>
                                    <td v-text="empresa.email_e"></td>
                                    <td v-text="empresa.razon_social"></td>
                                    <td v-text="empresa.representante"></td>
                                    <td v-text="empresa.ruc_e"></td>
                                    <td v-text="empresa.telefono_e"></td>
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
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre_e" class="form-control" placeholder="Nombre de la empresa" maxlength="100">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion_e" class="form-control" placeholder="Dirección de la empresa" maxlength="70">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Email (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="email_e" class="form-control" placeholder="Email de la empresa" maxlength="50">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Razón Social (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="razon_social" class="form-control" placeholder="Razón social de la empresa" maxlength="100">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Representante (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="representante" class="form-control" placeholder="Representante de la empresa" maxlength="100">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">RUC (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="ruc_e" class="form-control" placeholder="Ruc de la empresa" maxlength="20">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono_e" class="form-control" placeholder="Dirección de la empresa" maxlength="21">
                                    </div>
                                </div>
                                <div v-show="errorEmpresa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEmpresa" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEmpresa()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEmpresa()">Actualizar</button>
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
                empresa_id: 0,
                direccion_e : '',
                email_e: '',
                nombre_e : '',
                razon_social : '',
                representante : '',
                ruc_e: '',
                telefono_e: '',
                arrayEmpresa : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEmpresa : 0,
                errorMostrarMsjEmpresa : [],
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
            listarEmpresa (page,buscar,criterio){
                let me=this;
                var url= '/empresa?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEmpresa = respuesta.empresas.data;
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
                me.listarEmpresa(page,buscar,criterio);
            },
            registrarEmpresa(){
                if (this.validarEmpresa()){
                    return;
                }

                let me = this;

                axios.post('/empresa/registrar',{
                    'direccion_e': this.direccion_e,
                    'email_e': this.email_e,
                    'nombre_e': this.nombre_e,
                    'razon_social': this.razon_social,
                    'representante': this.representante,
                    'ruc_e': this.ruc_e,
                    'telefono_e': this.telefono_e,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEmpresa(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarEmpresa(){
                if (this.validarEmpresa()){
                    return;
                }

                let me = this;

                axios.put('/empresa/actualizar',{
                    'direccion_e': this.direccion_e,
                    'email_e': this.email_e,
                    'nombre_e': this.nombre_e,
                    'razon_social': this.razon_social,
                    'representante': this.representante,
                    'ruc_e': this.ruc_e,
                    'telefono_e': this.telefono_e,
                    'id': this.empresa_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEmpresa(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },            
            validarEmpresa(){
                this.errorEmpresa=0;
                this.errorMostrarMsjEmpresa =[];

                if (!this.nombre_e) this.errorMostrarMsjEmpresa.push("El nombre de la empresa no puede estar vacío.");

                if (this.errorMostrarMsjEmpresa.length) this.errorEmpresa = 1;

                return this.errorEmpresa;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='',
                this.nombre='',
                this.descripcion='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "empresa":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Empresa';                                
                                this.direccion_e = '';
                                this.email_e = '';
                                this.nombre_e = '';
                                this.razon_social = '';
                                this.representante = '';
                                this.ruc_e = '';
                                this.telefono_e = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar empresa';
                                this.tipoAccion=2;
                                this.empresa_id=data['id'];
                                this.direccion_e= data['direccion_e'];
                                this.email_e = data['email_e'];
                                this.nombre_e = data['nombre_e'];
                                this.razon_social = data['razon_social'];
                                this.representante = data['representante'];
                                this.ruc_e = data['ruc_e'];
                                this.telefono_e = data['telefono_e'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarEmpresa(1,this.buscar,this.criterio);
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