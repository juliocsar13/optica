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
                        <i class="fa fa-align-justify" style= "margin-top: 8px;"></i> Filtros

                        <button type="button" @click="reportPDF()" class="btn btn-outline-secondary pull-right"  style="margin-top:1px">
                         <i class="icon-plus"></i>&nbsp;Generar PDF
                         </button>
                         <button type="button" @click="reportExcel()" class="btn btn-outline-info pull-right"  style="margin-top:1px">
                         <i class="icon-doc"></i>&nbsp;Generar Excel
                         </button>
                    </div>
                    <div class="card-body">
                        <div class=" row">
                              <div class="form-group col-md-3">
                                <div class="form-group">
                                  <label for="">Rango de Fecha</label>
                                  <input id="datekardex" class="form-control" style="height: 39px;"/>
                                </div>
                              </div>

                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="">Producto</label>
                                  <v-select style="height:40px"
                                            v-model="filtrarProducto"
                                            placeholder="Seleccionar producto"
                                            label="nombre"
                                            :options="productos"></v-select>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <button style='margin-left: 5px' class="btn btn-outline-primary pull-right" @click='borrarFiltros()'>Borrar Filtros</button>
                                <button class="btn btn-primary pull-right" @click='filterKardex()'>Filtrar</button>

                              </div>

                        </div>
                      </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Kardex Valorizado

                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped table-sm table-responsive" >
                            <thead>
                                <tr>
                                  <th colspan="3" scope="colgroup">COMPROBANTE DE PAGO,<br>DOCUMENTO INTERNO O SIMILAR</th>
                                  <th colspan="3" scope="colgroup" style="text-align:center">ENTRADAS</th>
                                  <th colspan="3" scope="colgroup" style="text-align:center">SALIDAS</th>
                                  <th colspan="3" scope="colgroup" style="text-align:center">SALDO FINAL</th>
                                </tr>
                                <tr>
                                  <th style="text-align:center">FECHA</th>
                                  <th style="text-align:center">SERIE</th>
                                  <th style="text-align:center">NUMERO</th>


                                  <th style="text-align:center">CANT.</th>
                                  <th style="text-align:center">COSTO<br>   UNITARIO</th>
                                  <th style="text-align:center">TOTAL</th>

                                  <th style="text-align:center">CANT.</th>
                                  <th style="text-align:center">COSTO<br> UNITARIO</th>
                                  <th style="text-align:center">TOTAL</th>

                                  <th style="text-align:center">CANT.</th>
                                  <th style="text-align:center">COSTO<br>
                                    UNITARIO</th>
                                  <th style="text-align:center">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in arrayProducto" :key="producto.id">
                                  <td style="text-align:center" v-text="producto.fecha"></td>
                                  <td style="text-align:center" v-text="producto.serie_comprobante"></td>
                                  <td style="text-align:center" v-text="producto.num_comprobante"></td>
                                  <td style="text-align:center" v-text="producto.i_cantidad"></td>
                                  <td style="text-align:center">{{producto.i_precio}}</td>
                                  <td style="text-align:center" v-text="producto.i_total"></td>
                                  <td style="text-align:center" v-text="producto.v_cantidad"></td>
                                  <td style="text-align:center" v-text="producto.v_precio"></td>
                                  <td style="text-align:center" v-text="producto.v_total"></td>
                                  <td style="text-align:center" v-text="producto.t_cantidad"></td>
                                  <td style="text-align:center" v-text="producto.t_precio"></td>
                                  <td style="text-align:center" v-text="producto.t_total"></td>
                                </tr>
                                <tr>
                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center"> </td>
                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center"></td>
                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center">TOTAL</td>

                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center">{{total_ingreso_cantidad}}</td>
                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center"></td>
                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center">{{total_ingreso}}</td>

                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center">{{total_salida_cantidad}}</td>
                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center"></td>
                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center">{{total_salida}}</td>

                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center"></td>
                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center"></td>
                                  <td class="content-td" style="border-bottom: 1px solid #ddd;text-align:center"></td>
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

        </main>
</template>

<script>

    import vSelect from 'vue-select';

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
                filtrarProducto:'',
                filterDate:'',
                productos: [],
                total_ingreso: '',
                total_ingreso_cantidad: '',
                total_salida: '',
                total_salida_cantidad: '',
            }
        },
        components: {
            vSelect,
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

            generatePDF(){

            },
            borrarFiltros(){
              this.filterDate = '';
              this.filtrarProducto ='';
              //this.listarProducto(1,this.filtrarProducto.nombre,this.filterDate);

            },
            filterKardex() {
              let me=this;

              let date = $('#datekardex').val().split("-");
              //.split('-');
              console.log("dada",date);
              let dateStart = date[0].trim();
              let dateEnd = date[1].trim();
              var url= '/kardex/filter?producto='+this.filtrarProducto.id + '&dateEnd='+ dateEnd+ '&dateStart='+dateStart;
              axios.get(url).then((respuesta) => {
                console.log(respuesta);
                me.arrayProducto = respuesta.data.productos;
                me.total_ingreso = respuesta.data.total_ingreso
                me.total_ingreso_cantidad = respuesta.data.total_ingreso_cantidad
                me.total_salida = respuesta.data.total_salida
                me.total_salida_cantidad = respuesta.data.total_salida_cantidad
              })
            },
            reportPDF(){
              let date = $('#datekardex').val().split("-");
              let dateStart = date[0].trim();
              let dateEnd = date[1].trim();
              let url = '/kardex/pdf?producto='+ this.filtrarProducto.id + '&dateEnd='+ dateEnd+ '&dateStart='+dateStart;
              window.open(url);

              //window.open('http://localhost:8000/kardex/pdf/'+'?producto='+ this.filtrarProducto.nombre + '&date='+ this.filterDate);
            },
            reportExcel() {
              let date = $('#datekardex').val().split("-");
              let dateStart = date[0].trim();
              let dateEnd = date[1].trim();
              let url = '/kardex/excel?producto='+ this.filtrarProducto.id + '&dateEnd='+ dateEnd+ '&dateStart='+dateStart;

              window.open(url);
            },
            listarProducto (page,filtrarProducto,filterDate){
                let me=this;
                var url= '/kardex?page=' + page + '&producto='+ filtrarProducto + '&date='+ filterDate;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                  //  me.arrayProducto = respuesta.productos.data;
                    me.arrayProducto = respuesta.productos;

                    //me.pagination= respuesta.pagination;
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
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayFamilia = respuesta.familias;
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
                    'descripcion': this.descripcion
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarProducto(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
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
                this.errorProducto=0;
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
                                break;
                            }
                        }
                    }
                }
                this.selectFamilia();
            },
            listProducts(){


              let me=this;
              var url= '/kardex/productos';
              axios.get(url).then(function (res) {
                  console.log(res);
                  me.productos = res.data.productos.data;
                  console.log(me.productos);
              })
              .catch(function (error) {
                  console.log(error);
              });


            },
            configDateRangePicker(){
              $('#datekardex').daterangepicker({
                  ranges: {
                      'Hoy': [moment(), moment()],
                      'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                      'Ultimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                      'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                      'Mes Actual': [moment().startOf('month'), moment().endOf('month')],
                      'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                  },
                  "alwaysShowCalendars": true,

                  "locale": {
                   "format": "YYYY/MM/DD",
                   "separator": " - ",
                   "applyLabel": "Aplicar",
                   "cancelLabel": "Cancelar",
                   "fromLabel": "de",
                   "toLabel": "Hasta",
                   "customRangeLabel": "Personalizado",
                   "daysOfWeek": [
                       "Do",
                       "Lu",
                       "Ma",
                       "Mie",
                       "Jue",
                       "Vie",
                       "Sa"
                   ],
                   "monthNames": [
                       "Enero",
                       "Febrero",
                       "Marzo",
                       "Abril",
                       "Mayo",
                       "Junio",
                       "Julio",
                       "Agosto",
                       "Septiembre",
                       "Octubre",
                       "Noviembre",
                       "Diciembre"
                   ],
                   "firstDay": 1
               }
              }, function(start, end, label) {
              });
            }
        },
        mounted() {
          this.configDateRangePicker();
          this.listProducts();
          //this.listarProducto(1,this.filtrarProducto.nombre,this.filterDate);
        }
    }
</script>
<style>
    th{
      text-align:center;

    }
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
    .dropdown-toggle.clearfix {
      height: 40px;
    }
</style>
