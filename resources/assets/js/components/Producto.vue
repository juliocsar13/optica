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
                        <i class="fa fa-align-justify"></i> Productos
                        <button type="button" @click="abrirModal('producto','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="cargarPdf()" class="btn btn-info">
                            <i class="icon-doc"></i>&nbsp;Reporte
                        </button>
                        <input id='xlsFile' style='width:auto;display:inline-block' placeholder="Seleccionar Archivo" type="file" name="file" class="form-control">
                        <button class="btn btn-success" @click='importData()'>Importar productos</button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarProducto(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarProducto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in arrayProducto" :key="producto.id">
                                    <td>
                                        <button type="button" @click="abrirModal('producto','actualizar',producto)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="producto.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarProducto(producto.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarProducto(producto.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="producto.codigo"></td>
                                    <td v-text="producto.nombre"></td>
                                    <td v-text="producto.nombre_familia"></td>
                                    <td v-text="producto.precio_venta"></td>
                                    <td v-text="producto.stock"></td>
                                    <td v-text="producto.descripcion"></td>
                                    <td>
                                        <div v-if="producto.condicion">
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
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Sucursal(*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idsucursal">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.id" v-text="sucursal.razon_social_s"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Categoría(*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idfamilia">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="familia in arrayFamilia" :key="familia.id" :value="familia.id" v-text="familia.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="codigo" class="form-control" placeholder="Código de barras" maxlength="42">
                                        <barcode :value="codigo" :options="{ format: 'EAN-13' }">
                                            Generando código de barras.
                                        </barcode>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre(*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de producto" maxlength="100">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio Venta(*)</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="precio_venta" class="form-control" min="1" max="999999999">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock(*)</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="stock" class="form-control" min="1" max="999999999">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripcion" maxlength="256">
                                    </div>
                                </div>
                                <div v-show="errorProducto" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProducto()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProducto()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!--Fin del modal-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : show}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content" >
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body" style="max-height: calc(100vh - 210px); overflow-y: auto;">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                              <table class="table table-bordered table-striped table-sm table-responsive">
                                  <thead>
                                      <tr>
                                          <th>Código</th>
                                          <th>Nombre</th>
                                          <th>Categoría</th>
                                          <th>Precio Venta</th>
                                          <th>Descripción</th>
                                          <th>Estado</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr v-if="statusProducts==200" v-for="producto in arrayImport" :key="producto.id">

                                          <td v-text="producto.codigo"></td>
                                          <td v-text="producto.nombre"></td>
                                          <td v-text="producto.idfamilia"></td>
                                          <td v-text="producto.precio_venta"></td>
                                          <td v-text="producto.descripcion"></td>
                                          <td>
                                              <div v-if="producto.condicion">
                                                  <span class="badge badge-success">Activo</span>
                                              </div>
                                              <div v-else>
                                                  <span class="badge badge-danger">Desactivado</span>
                                              </div>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="pull-left" style="width: 100%;color: green;"><strong>{{stateProducts}}</strong></div>
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button"  class="btn btn-primary" @click="ImpotarProductos()" v-if="statusProducts==200">Guardar</button>
                            <button type="button"  class="btn btn-secondary" @click="ImpotarProductos()" v-else disabled>Guardar</button>

                        </div>
                    </div>1
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';
    export default {
        data (){
            return {
                producto_id: 0,
                idfamilia : 0,
                nombre_familia : '',
                codigo : '',
                nombre : '',
                precio_venta : 0,
                stock : 0,
                descripcion : '',
                arrayProducto : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorProducto : 0,
                errorMostrarMsjProducto : [],
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
                buscar : '',
                arrayFamilia :[],
                idsucursal : 0,
                razon_social_s_sucursal : '',
                arraySucursal: [],
                arrayImport: [],
                show:0,
                stateProducts: '',
                statusProducts: '',
            }
        },
        components: {
            'barcode': VueBarcode
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
            importData(){

              let data = new FormData();
              let file = document.getElementById('xlsFile').files[0];
              data.append('file', file );

              axios.post('/producto/imprimir', data)
              .then((res) => {
                this.openModal(res.data)
              }).catch((err) => {
                  console.log(err);
              });

            },
            openModal(data){
              this.show = 1;
              this.arrayImport = [];
              this.arrayImport = data.data;
              this.stateProducts = data.status.content;
              this.statusProducts = data.status.status;
              this.tituloModal = 'Importar Productos';
            },
            ImpotarProductos() {
              axios.post('/producto/import', {products: this.arrayImport})
              .then((res) => {
                this.show=0;
                this.listarProducto(1,'','')
              }).catch((err) => {
                  console.log(err);
              });
            },
            listarProducto (page,buscar,criterio){
                let me=this;
                var url= '/producto?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cargarPdf(){
                window.open('http://127.0.0.1:8000/producto/listarPdf','_blank');
            },
            selectFamilia(){
                let me=this;
                var url= '/familia/selectFamilia';
                axios.get(url).then(function (response) {

                    var respuesta= response.data;
                    me.arrayFamilia = respuesta.familias;
                })
                .catch(function (error) {

                });
            },
            selectSucursal(){
                let me=this;
                var url= '/sucursal/selectSucursal';
                axios.get(url).then(function (response) {

                    var respuesta= response.data;
                    me.arraySucursal = respuesta.sucursales;
                })
                .catch(function (error) {

                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual.
                me.pagination.current_page = page;
                //Envia la peticion para visualizar la data de esa pagina.
                me.listarProducto(page,buscar,criterio);
            },
            registrarProducto(){
                if (this.validarProducto()){
                    return;
                }

                let me = this;

                axios.post('/producto/registrar',{
                    'idfamilia': this.idfamilia,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'precio_venta': this.precio_venta,
                    'descripcion': this.descripcion,
                    'idsucursal': this.idsucursal,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarProducto(1,'','nombre');
                }).catch(function (error) {

                });
            },
            actualizarProducto(){
                if (this.validarProducto()){
                    return;
                }

                let me = this;

                axios.put('/producto/actualizar',{
                    'idfamilia': this.idfamilia,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'precio_venta': this.precio_venta,
                    'descripcion': this.descripcion,
                    'idsucursal': this.idsucursal,
                    'id': this.producto_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarProducto(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            desactivarProducto(id){
                swal({
                title: 'Esta seguro de desactivar este producto?',
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

                    axios.put('/producto/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarProducto(1,'','nombre');
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
            activarProducto(id){
                swal({
                title: 'Esta seguro de activar este producto?',
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

                    axios.put('/producto/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarProducto(1,'','nombre');
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
            validarProducto(){
                this.errorProducto=0;
                this.errorMostrarMsjProducto =[];

                if (this.idsucursal==0) this.errorMostrarMsjProducto.push("Seleccione una sucursal.");

                if (this.idfamilia==0) this.errorMostrarMsjProducto.push("Seleccione una categoría.");
                if (!this.nombre) this.errorMostrarMsjProducto.push("El nombre del producto no puede estar vacio.");
                if (!this.stock) this.errorMostrarMsjProducto.push("El stock del producto debe ser un número y no puede estar vacío.");
                if (!this.precio_venta) this.errorMostrarMsjProducto.push("El precio de venta del producto debe ser un número y no puede estar vacío.");

                if (this.errorMostrarMsjProducto.length) this.errorProducto = 1;

                return this.errorProducto;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='',
                this.idfamilia= 0;
                this.nombre_familia = '';
                this.codigo = '';
                this.nombre = '';
                this.precio_venta = 0;
                this.stock = 0;
                this.descripcion = '';
                this.idsucursal = 0;
                this.razon_social_s_sucursal = '';
                this.errorProducto=0;
                this.show = 0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "producto":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Producto';
                                this.idfamilia=0;
                                this.nombre_familia='';
                                this.codigo='';
                                this.nombre= '';
                                this.precio_venta=0;
                                this.stock=0;
                                this.descripcion = '';
                                this.idsucursal=0;
                                this.razon_social_s_sucursal='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Producto';
                                this.tipoAccion=2;
                                this.producto_id=data['id'];
                                this.idfamilia=data['idfamilia'];
                                this.codigo=data['codigo'];
                                this.nombre = data['nombre'];
                                this.stock=data['stock'];
                                this.precio_venta=data['precio_venta'];
                                this.descripcion= data['descripcion'];
                                this.idsucursal=data['idsucursal'];
                                break;
                            }
                        }
                    }
                }
                this.selectFamilia();
                this.selectSucursal();
            }
        },
        mounted() {
            this.listarProducto(1,this.buscar,this.criterio);
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
