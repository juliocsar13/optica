<template>
    <main class="main">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify" style="margin-top: 8px;"></i> Caja
                      <button type="button" @click="abrirModal()" class="btn btn-secondary">
                          <i class="icon-plus"></i>&nbsp;Nuevo
                      </button>
                    </div>
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select style="height:39px" class="form-control col-md-3" v-model="criterio">

                                      <option value="updated_at">Fecha-Hora</option>
                                    </select>
                                    <input type="date" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="">
                                    <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Fecha Apertura</th>
                                        <th>Fecha Cierre</th>
                                        <th>Monto Inicial</th>
                                        <th>Monto Final</th>

                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayCaja.length">
                                    <tr v-for="caja in arrayCaja" :key="caja.id">
                                        <td >
                                          <template v-if="caja.estado == 1">
                                              <button type="button" class="btn btn-danger btn-sm" @click="desactivarCaja(caja.id)">
                                                  <i class="icon-trash"></i>
                                              </button>
                                          </template>
                                          <template v-else>
                                              <button type="button" class="btn btn-info btn-sm" @click="activarCaja(caja.id)">
                                                  <i class="icon-check"></i>
                                              </button>
                                          </template>
                                        </td>
                                        <td v-text="caja.usuario"></td>
                                        <td v-text="caja.fecha_apertura"></td>
                                        <td v-text="caja.fecha_cierre"></td>
                                        <td v-text="caja.monto_inicial"></td>
                                        <td v-text="caja.monto_final"></td>

                                        <td v-if='caja.estado == 0'>
                                          <div><span class="badge badge-danger  ">Cerrada</span></div>
                                        </td>
                                        <td v-else>
                                          <div><span class="badge badge-success">Activa</span></div>
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
                </div>
            </div>
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
                                  label="nombre"
                                  placeholder="Buscar Clientes..."
                                  :on-search="selectCliente"
                                  :options="arrayCliente"
                                  :onChange="getDatosCliente">
                                </v-select>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Monto</label>
                                <input type="number" min='0' class="form-control" v-model="monto">
                              </div>
                            </div>
                          </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button"  class="btn btn-primary" @click="registrarCaja()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
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
                monto: '',
                arrayCaja:[],
                venta_id: 0,
                idcliente:0,
                cliente:'',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto: 0.18,
                total:0.0,
                totalImpuesto: 0.0,
                totalParcial: 0.0,
                arrayVenta : [],
                arrayCliente: [],
                arrayDetalle : [],
                listado:1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorVenta : 0,
                errorMostrarMsjVenta : [],
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
                buscar : '',
                criterioP:'nombre',
                buscarP: '',
                arrayCilindro: [],

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
                for(var i=0;i<this.arrayVenta.length;i++){
                    resultado = resultado - this.arrayVenta[i].adelanto*-1
                }
                return resultado;
            },
        },
        methods : {
            desactivarCaja(id){
                swal({
                title: 'Esta seguro que quiere cerrar caja?',
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

                    axios.put('/caja/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCaja(1);
                        swal(
                        'Caja cerrada!',
                        'El accion a sido realizada con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (

                    result.dismiss === swal.DismissReason.cancel
                ) {  } })
            },
            async verificarCaja(id) {
              const response = await  axios.post('/caja/verificar',{id:id})
              if (response.data.result == 1) {
                swal(
                  'Caja Error!',
                  'Solo una caja puede estar activa.',
                  'error'
                )
                return 1;
              } else {
                return 0;
              }
            },
            async activarCaja(id){
                let result = await this.verificarCaja(id);
                console.log(result);
                if (result == 0) {
                    swal({
                      title: 'Esta seguro que quiere Aperturar caja?',
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

                      axios.put('/caja/activar',{
                      'id': id
                      }).then(function (response) {
                          me.listarCaja(1,'','nombre');
                          swal(
                            'Caja Aperturada!',
                            'La Accion a sido realizada con éxito.',
                            'success'
                          )
                        }).catch(function (error) {
                        console.log(error);
                      });

                      } else if ( result.dismiss === swal.DismissReason.cancel ) {
                         }
                    })
                }
            },
            registrarCaja() {
              let me = this;
              let data = {};
              data.idpersona = this.idcliente;
              data.monto = this.monto;
              axios.post('/caja/registrar', data).then(function (response) {
                me.modal = 0;
                me.monto = 0;
                me.idcliente = '';
                me.listarCaja(1)
              }).catch(function (error) {
                  console.log(error);
              });
            },
            listarCaja (page){
                let me=this;
                var url= '/caja?page=' + page;
                axios.get(url).then(function (response) {
                    console.log(response);
                    var respuesta= response.data;
                    me.arrayCaja = respuesta.cajas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarVenta (page,buscar,criterio){
                let me=this;
                var url= '/caja?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayVenta = respuesta.ventas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrirModal(modelo, accion, data = []){

                this.modal = 1;
                this.tituloModal = 'Crear Caja';
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
            getDatosCliente(val1){
                let me = this;
                me.loading = true;
                me.idcliente = val1.id;
            },
            buscarProducto(){
                let me=this;
                var url= '/producto/buscarProductoVenta?filtro=' + me.codigo;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos;

                    if (me.arrayProducto.length>0){
                        me.producto=me.arrayProducto[0]['nombre'];
                        me.idproducto=me.arrayProducto[0]['id'];
                        me.precio=me.arrayProducto[0]['precio_venta'];
                        me.stock=me.arrayProducto[0]['stock'];
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
            pdfVenta(id){
                window.open('http://127.0.0.1:8000/venta/pdf/'+ id + ',' + '_blank');
            },
            pdfVenta2(id){
                window.open('http://127.0.0.1:8000/venta/pdf2/'+ id + ',' + '_blank');
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual.
                me.pagination.current_page = page;
                //Envia la peticion para visualizar la data de esa pagina.
                me.listarCaja(page);
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
                        if (me.cantidad>me.stock){
                            swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'No hay stock disponible!',
                            })
                        }
                        else{
                            me.arrayDetalle.push({
                                idproducto: me.idproducto,
                                producto: me.producto,
                                cantidad: me.cantidad,
                                precio: me.precio,
                                descuento: me.descuento,
                                stock: me.stock
                            });
                            me.codigo="";
                            me.idproducto=0;
                            me.producto="";
                            me.cantidad=0;
                            me.precio=0;
                            me.descuento=0;
                            me.stock=0
                        }
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
                            precio: data['precio_venta'],
                            descuento: 0,
                            stock:data['stock']
                        });
                    }
            },
            listarProducto (buscar,criterio){
                let me=this;
                var url= '/producto/listarProductoVenta?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarVenta(){
                if (this.validarVenta()){
                    return;
                }

                let me = this;

                axios.post('/venta/registrar',{
                    'idcliente': this.idcliente,
                    'tipo_comprobante': this.tipo_comprobante,
                    'serie_comprobante' : this.serie_comprobante,
                    'num_comprobante' : this.num_comprobante,
                    'impuesto' : this.impuesto,
                    'total' : this.total,
                    'data': this.arrayDetalle,
                    'idproveedor': this.idproveedor,
                    'esfera': this.esfera,
                    'cilindro': this.cilindro,
                    'eje': this.eje,
                    'add': this.add,
                    'dip': this.dip,
                    'av': this.av,
                    'prisma': this.prisma,
                    'esfera2': this.esfera2,
                    'cilindro2': this.cilindro2,
                    'eje2': this.eje2,
                    'av2': this.av2,
                    'prisma2': this.prisma2,
                    'referencia': this.referencia,
                    'adelanto': this.adelanto,
                    'pendiente': this.pendiente,
                    'forma_pago': this.forma_pago
                }).then(function (response) {
                    me.listado=1;
                    me.listarVenta(1,'','num_comprobante');
                    me.idcliente=0;
                    me.tipo_comprobante='BOLETA';
                    me.serie_comprobante='';
                    me.num_comprobante='';
                    me.impuesto=0.18;
                    me.total=0.0;
                    me.idproducto=0;
                    me.producto='';
                    me.cantidad=0;
                    me.precio=0;
                    me.stock=0;
                    me.codigo='';
                    me.descuento=0;
                    me.arrayDetalle=[];
                    me.idproveedor='';
                    me.esfera=0;
                    me.cilindro='';
                    me.prisma='';
                    me.prisma2='';
                    me.referencia='';
                    me.adelanto='';
                    me.pendiente='';
                    me.forma_pago='Efectivo';
                    //window.open('http://127.0.0.1:8000/venta/pdf/'+ response.data.id + ',' + '_blank');

                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarVenta(){
                let me=this;
                me.errorVenta=0;
                me.errorMostrarMsjVenta =[];
                var pro;

                me.arrayDetalle.map(function(x){
                    if (x.cantidad>x.stock) {
                        pro=x.producto + " con stock insuficiente"
                        me.errorMostrarMsjVenta.push(pro);
                    }
                });

                if (me.idcliente==0) me.errorMostrarMsjVenta.push("Seleccione un Cliente");
                if (me.tipo_comprobante==0) me.errorMostrarMsjVenta.push("Seleccione el comprobante");
                if (!me.num_comprobante) me.errorMostrarMsjVenta.push("Ingrese el número de comprobante");
                if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra");
                //if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Ingrese detalles");

                if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                return me.errorVenta;
            },
            mostrarDetalle(){
                let me=this;
                me.listado=0;

                me.idproveedor='';
                me.tipo_comprobante='BOLETA';
                me.serie_comprobante='';
                me.num_comprobante='';
                me.impuesto=0.18;
                me.total=0.0;
                me.idproducto=0;
                me.articulo='';
                me.cantidad='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verVenta(id){
                let me=this;
                me.listado=2;

                //Obtener los datos del ingreso
                var arrayVentaT=[];
                var url= '/venta/obtenerCabecera?id=' + id;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayVentaT = respuesta.venta;

                    me.cliente = arrayVentaT[0]['nombre'];
                    me.tipo_comprobante=arrayVentaT[0]['tipo_comprobante'];
                    me.serie_comprobante=arrayVentaT[0]['serie_comprobante'];
                    me.num_comprobante=arrayVentaT[0]['num_comprobante'];
                    me.impuesto=arrayVentaT[0]['impuesto'];
                    me.total=arrayVentaT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles
                var urld= '/venta/obtenerDetalles?id=' + id;

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
            desactivarVenta(id){
               swal({
                title: 'Esta seguro de anular esta venta?',
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

                    axios.put('/venta/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarVenta(1,'','num_comprobante');
                        swal(
                        'Anulado!',
                        'La venta ha sido anulado con éxito.',
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
            getDatosProveedor(val2){
                let me = this;
                me.loading = true;
                me.idproveedor = val2.id;
            },
        },
        mounted() {
            this.listarCaja(1);
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
