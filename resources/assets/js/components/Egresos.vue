<template>
    <main class="main">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify" style="margin-top: 8px;"></i> Egresos
                      <button type="button" @click="abrirModal()" class="btn btn-secondary">
                          <i class="icon-plus"></i>&nbsp;Nuevo
                      </button>
                    </div>
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select style="height:39px;" class="form-control col-md-3" v-model="criterio">

                                      <option value="updated_at">Fecha-Hora</option>
                                    </select>
                                    <input type="date" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                      <th>Opciones</th>
                                        <th>Fecha</th>
                                        <th>Usuario</th>

                                        <th>Descripcion</th>
                                        <th>Monto</th>
                                        <th>Tipo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="movimiento in arrayMovimiento" :key="movimiento.id">
                                        <td >
                                          <button type="button" @click="VerMovimiento(movimiento.id)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                          </button> &nbsp;
                                        </td>
                                        <td v-text="movimiento.created_at"></td>
                                        <td v-text="movimiento.usuario"></td>
                                        <td v-text="movimiento.descripcion"></td>
                                        <td v-text="movimiento.monto"></td>
                                        <td v-if='movimiento.tipo == 0'>
                                          <div><span class="badge badge-success  ">Ingreso</span></div>
                                        </td>
                                        <td v-else>
                                          <div><span class="badge badge-success">Egreso</span></div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
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
                    </template>
                    <!--Fin Listado-->
                    <!-- Detalle -->
                    <template v-else-if="listado==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <v-select
                                        :on-search="selectProveedor"
                                        label="nombre"
                                        :options="arrayProveedor"
                                        placeholder="Buscar Proveedores..."
                                        :onChange="getDatosProveedor" >

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="impuesto">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="tipo_comprobante">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Producto <span style="color:red;" v-show="idproducto==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarProducto()" placeholder="Ingrese producto">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="producto">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span style="color:red;" v-show="precio==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color:red;" v-show="cantidad==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.producto">
                                            </td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.cantidad" type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>S/. {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>S/. {{totalImpuesto=((total*impuesto)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>S/. {{total=calcularTotal}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>S/. {{total}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay productos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- Fin Detalle -->
                    <!-- Ver Ingreso -->
                    <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor</label>
                                    <p v-text="proveedor"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="impuesto"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante</label>
                                    <p v-text="num_comprobante"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.producto">
                                            </td>
                                            <td v-text="detalle.precio">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Parcial:</strong></td>
                                            <td>S/. {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>S/. {{totalImpuesto=((total*impuesto)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                            <td>S/. {{total}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Adelanto:</strong></td>
                                            <td>S/. {{adelantoI}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Pendiente:</strong></td>
                                            <td>S/. {{pendienteI}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
                                                No hay productos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>

                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- Fin Ver Ingreso -->
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
                          <div class="row">

                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Persona</label>
                                <v-select
                                :on-search="selectCliente"
                                label="nombre"
                                :options="arrayCliente"
                                placeholder="Buscar Clientes..."
                                :onChange="getDatosCliente">
                              </v-select>
                            </div>
                          </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Descripcion</label>
                                <input type="text" class="form-control" v-model="descripcion">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Monto</label>
                                <input type="number" min='0' class="form-control" v-model="monto">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Tipo de Movimiento</label>
                                <select  v-model="movimiento" class="form-control">
                                  <option value="">Seleccione tipo de movimiento</option>
                                  <option value="0">Ingreso</option>
                                  <option value="1">Egreso</option>
                                </select>
                                </div>
                            </div>
                          </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="registrarMovimiento()">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalEdit}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="row">


                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Descripcion</label>
                                <input type="text" class="form-control" v-model="descripcion">
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Tipo de Movimiento</label>
                                <select  v-model="movimiento" class="form-control">
                                  <option value="">Seleccione tipo de movimiento</option>
                                  <option value="0">Ingreso</option>
                                  <option value="1">Egreso</option>
                                </select>
                                </div>
                            </div>
                          </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="EditarMovimiento()">Guardar</button>
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
    import vSelect from 'vue-select';
    export default {
        data (){
            return {
              arrayCliente: [],

                ingreso_id: 0,
                idproveedor:0,
                proveedor:'',
                nombre : '',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto: 0.18,
                total:0.0,
                totalImpuesto: 0.0,
                totalParcial: 0.0,
                arrayIngreso : [],
                arrayProveedor: [],
                arrayDetalle : [],
                listado:1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIngreso : 0,
                errorMostrarMsjIngreso : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'updated_at',
                buscar : '' ,
                criterioP:'nombre',
                buscarP: '',
                arrayProducto: [],
                idproducto: 0,
                codigo: '',
                producto: '',
                precio: 0,
                cantidad:0,
                descripcion: '',
                monto: 0,
                movimiento: '',
                arrayMovimiento:[],
                tipo: '',
                modalEdit:0,
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
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
            },
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayIngreso.length;i++){
                    resultado=resultado - this.arrayIngreso[i].adelantoI*-1
                }
                return resultado;
            }
        },
        methods : {
            EditarMovimiento(id) {
              let me = this;
              let data = {};
              data.descripcion = me.descripcion;
              data.movimiento = me.movimiento;

              axios.post('/movimiento/editar', data).then(function (response) {
                me.modal = 0;
                me.monto = 0;
                me.descripcion = 0;
                me.movimiento = 0;
                me.idcliente = '';
                me.listarMovimiento(1);
              }).catch(function (error) {
                console.log(error);
              });
            },
            VerMovimiento(id) {
              this.modalEdit = 1;
              this.tituloModal = 'Editar movimiento I/E';
            },
            async registrarMovimiento() {
              let me = this;
              let data = {};
              data.idpersona = me.idcliente;
              data.monto = me.monto;
              data.descripcion = me.descripcion;
              data.movimiento = me.movimiento;
              console.log(data);
              let result = await this.verificarCaja();
              if (result == 0) {

                swal(
                  'Caja Error!',
                  'Tiene que Aperturar una Caja antes de realizar un Ingreso y/o Egreso.',
                  'error'
                )

              } else {

                axios.post('/movimiento/registrar', data).then(function (response) {
                  me.modal = 0;
                  me.monto = 0;
                  me.descripcion = 0;
                  me.movimiento = 0;
                  me.idcliente = '';
                  me.listarMovimiento(1);
                }).catch(function (error) {
                  console.log(error);
                });
              }
            },
            abrirModal(modelo, accion, data = []){
                this.modal = 1;
                this.tituloModal = 'Crear movimiento I/E';
            },
            async verificarCaja() {
              const response = await  axios.post('/caja/verificar')
              if (response.data.result == 1) {
                return 1;
              } else {
                return 0;
              }
            },
            listarMovimiento(page){
              let me=this;
              var url= '/movimiento?page=' + page;
              axios.get(url).then(function (response) {
                  var respuesta= response.data;
                  me.arrayMovimiento = respuesta.movimientos.data;
                  me.pagination= respuesta.pagination;
              })
              .catch(function (error) {
                  console.log(error);
              });
            },
            listarIngreso (page,buscar,criterio){
                let me=this;
                var url= '/egreso?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayIngreso = respuesta.ingresos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectProveedor(search,loading){
                let me=this;
                loading(true);

                var url= '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayProveedor=respuesta.proveedores;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;
            },
            buscarProducto(){
                let me=this;
                var url= '/producto/buscarProducto?filtro=' + me.codigo;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos;

                    if (me.arrayProducto.length>0){
                        me.producto=me.arrayProducto[0]['nombre'];
                        me.idproducto=me.arrayProducto[0]['id'];
                    }
                    else{
                        me.producto='No existe producto';
                        me.idproducto=0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCliente(val1){
                let me = this;
                me.loading = true;
                me.idcliente = val1.id;
            },
            selectCliente(search,loading){
                let me=this;
                loading(true)

                var url= '/cliente/selectCliente?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayCliente=respuesta.clientes;
                    loading(false)
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
                me.listarMovimiento(page);
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idproducto==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },
            agregarDetalle(){
                let me=this;
                if(me.idproducto==0 || me.cantidad==0 || me.precio==0){
                }
                else{
                    if(me.encuentra(me.idproducto)){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese producto ya se encuentra agregado!',
                        })
                    }
                    else{
                        me.arrayDetalle.push({
                            idproducto: me.idproducto,
                            producto: me.producto,
                            cantidad: me.cantidad,
                            precio: me.precio
                        });
                        me.codigo="";
                        me.idproducto=0;
                        me.producto="";
                        me.cantidad=0;
                        me.precio=0;
                    }

                }
            },
            agregarDetalleModal(data =[]){
                let me=this;
                if(me.encuentra(data['id'])){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese producto ya se encuentra agregado!',
                        })
                    }
                    else{
                        me.arrayDetalle.push({
                            idproducto: data['id'],
                            producto: data['nombre'],
                            cantidad: 1,
                            precio: 1
                        });
                    }
            },
            listarProducto (buscar,criterio){
                let me=this;
                var url= '/producto/listarProducto?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarIngreso(){
                if (this.validarIngreso()){
                    return;
                }

                let me = this;

                axios.post('/ingreso/registrar',{
                    'idproveedor': this.idproveedor,
                    'tipo_comprobante': this.tipo_comprobante,
                    'serie_comprobante' : this.serie_comprobante,
                    'num_comprobante' : this.num_comprobante,
                    'impuesto' : this.impuesto,
                    'total' : this.total,
                    'data': this.arrayDetalle

                }).then(function (response) {
                    me.listado=1;
                    me.listarIngreso(1,'','num_comprobante');
                    me.idproveedor=0;
                    me.tipo_comprobante='BOLETA';
                    me.serie_comprobante='';
                    me.num_comprobante='';
                    me.impuesto=0.18;
                    me.total=0.0;
                    me.idproducto=0;
                    me.producto='';
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle=[];

                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarIngreso(){
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso =[];

                if (this.idproveedor==0) this.errorMostrarMsjIngreso.push("Seleccione un Proveedor");
                if (this.tipo_comprobante==0) this.errorMostrarMsjIngreso.push("Seleccione el comprobante");
                if (!this.num_comprobante) this.errorMostrarMsjIngreso.push("Ingrese el número de comprobante");
                if (!this.impuesto) this.errorMostrarMsjIngreso.push("Ingrese el impuesto de compra");
                if (this.arrayDetalle.length<=0) this.errorMostrarMsjIngreso.push("Ingrese detalles");

                if (this.errorMostrarMsjIngreso.length) this.errorIngreso = 1;

                return this.errorIngreso;
            },
            mostrarDetalle(){
                let me=this;
                me.listado=0;

                me.idproveedor=0;
                me.tipo_comprobante='BOLETA';
                me.serie_comprobante='';
                me.num_comprobante='';
                me.impuesto=0.18;
                me.total=0.0;
                me.idproducto=0;
                me.producto='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verIngreso(id){
                let me=this;
                me.listado=2;

                //Obtener los datos del ingreso
                var arrayIngresoT=[];
                var url= '/egreso/obtenerCabecera?id=' + id;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayIngresoT = respuesta.ingreso;

                    me.proveedor = arrayIngresoT[0]['nombre'];
                    me.tipo_comprobante=arrayIngresoT[0]['tipo_comprobante'];
                    me.serie_comprobante=arrayIngresoT[0]['serie_comprobante'];
                    me.num_comprobante=arrayIngresoT[0]['num_comprobante'];
                    me.impuesto=arrayIngresoT[0]['impuesto'];
                    me.total=arrayIngresoT[0]['total'];
                    me.adelantoI = arrayIngresoT[0]['adelantoI'];
                    me.pendienteI = arrayIngresoT[0]['pendienteI'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles
                var urld= '/ingreso/obtenerDetalles?id=' + id;

                axios.get(urld).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            },

            desactivarIngreso(id){
               swal({
                title: 'Esta seguro de anular este ingreso?',
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

                    axios.put('/ingreso/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarIngreso(1,'','num_comprobante');
                        swal(
                        'Anulado!',
                        'El ingreso ha sido anulado con éxito.',
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
        },
        mounted() {
            this.listarMovimiento(1);
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
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>
