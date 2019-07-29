<template>
    <main class="main">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify" style= "margin-top: 8px;"></i> Filtros
                        <button class="btn btn-outline-secondary pull-right" @click='generetePDF()'  style="margin-top:1px">Generar reporte</button>
                    </div>
                    <div class="card-body">
                        <div class=" row">
                              <div class="form-group col-md-3">
                                <div class="form-group">
                                  <label for="">Fecha</label>
                                  <input id="dateCaja" placeholder="date" class="form-control"/>
                                </div>
                              </div>


                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="">Tipo comprobante</label>
                                  <select  v-model='tipo_comprobante' class="form-control">
                                    <option value="">seleccionar</option>
                                    <option value="boleta">Boleta</option>
                                    <option value="factura">Factura</option>
                                    <option value="ticket">Ticket</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <button style='margin-left: 5px' class="btn btn-outline-primary pull-right" @click='borrarFiltros()'>Borrar Filtros</button>
                                <button class="btn btn-primary pull-right" @click='filterCaja()'>Filtrar</button>

                              </div>

                        </div>
                      </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Entradas y Salidas de dinero
                    </div>
                    <template v-if="listado==1">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Usuario</th>
                                        <th>Movimiento</th>
                                        <th>Forma pago</th>
                                        <th>Total</th>
                                        <th>Adelanto</th>
                                        <!--<th>Pendiente</th>-->
                                        <th>Concepto / Descripcion</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">

                                        <td v-text="venta.date"></td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.movimiento"></td>
                                        <td v-text="venta.forma_pago"></td>
                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.adelanto"></td>
                                        <!--<td v-text="venta.pendiente"></td>-->
                                        <td>{{venta.serie_comprobante}} - {{venta.num_comprobante}}</td>
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
                    <!-- Ver Ingreso -->
                    <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p>
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
                                            <th>Descuento</th>
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
                                            <td v-text="detalle.descuento">
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad-descuento}}
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

                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- Fin Ver Ingreso -->
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
                venta_id: 0,
                idcliente:0,
                cliente:'',
                tipo_comprobante : '',
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
                criterio : 'num_comprobante',
                buscar : '',
                criterioP:'nombre',
                buscarP: '',
                arrayProducto: [],
                idproducto: 0,
                codigo: '',
                producto: '',
                precio: 0,
                cantidad:0,
                descuento: 0,
                stock:0,

                idproveedor: 0,
                arrayProveedor: [],

                idgraduacion: 0,
                arrayGraduacion: [],

                idcilindro: 0,
                arrayCilindro: [],

                ideje: 0,
                arrayEje: [],

                idadicion: 0,
                arrayAdicion: [],

                iddnp: 0,
                arrayDnp: [],

                idao: 0,
                arrayAo: [],

                prisma: '',
                prisma2: '',
                referencia: '',
                arrayClientes: [],
                filterClient: '',
                filterDate: '',
                forma_pago: '',
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
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad-this.arrayDetalle[i].descuento)
                }
                return resultado;
            }
        },
        methods : {
            borrarFiltros() {
              this.filterDate = '';
              this.forma_pago = '';
              this.tipo_comprobante = '';
              //this.listarVenta();
            },
            /*listarVenta (){
                let me=this;
                //var url= '/caja?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                let url = '/venta/filterSales?date='+this.filterDate+'&tipo_comprobante='+this.tipo_comprobante+'&forma_pago='+this.forma_pago+'&type='+'main' ;

                axios.get(url).then(function (response) {
                    //var respuesta= response.data;
                    //me.arrayVenta = respuesta.ventas.data;
                  //  me.pagination= respuesta.pagination;


                    let compra  = response.data.ingresos;
                    let venta = response.data.ventas;
                    compra.forEach((element) =>{
                      return element.movimiento  = 'compra'
                    });
                    venta.forEach((element) =>{
                      return element.movimiento  = 'venta'
                    });
                    let arr3 = [...compra, ...venta ];
                    me.arrayVenta = arr3
                })
                .catch(function (error) {
                    console.log(error);
                });
            },*/
            pdfVenta(id){
                window.open('http://localhost:8000/venta/pdf/'+ id +'/' +'BOLETA'  );
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual.
                me.pagination.current_page = page;
                //Envia la peticion para visualizar la data de esa pagina.
                me.filterCaja(page);
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
            setCurrentDate(){
             let d = new Date();
             let currDate = d.getDate();
             let currMonth = d.getMonth()+1;
             let currYear = d.getFullYear();
             let minutes = d.getMinutes() <10 ? '0'+d.getMinutes() : d.getMinutes();
             let hour = d.getHours()<10 ? '0'+d.getHours() : d.getHours();
             let date =currYear + "-" + ((currMonth<10) ? '0'+currMonth : currMonth ) + "-" + ((currDate<10) ? '0'+currDate : currDate )+'T'+hour+':'+minutes;
             console.log(date);
             $('.current-date').val(date);
             this.filterDateStart = date;
             this.filterDateEnd = date;
            },
            getClientes(){
              axios.get('/cliente').then((res) => {
                  this.arrayClientes = res.data.personas.data
              })
            },

            filterCaja(page){
              let me = this;

                let date = $('#dateCaja').val().split("-");
                console.log(date);
              let params = {};
              params.page = page;
              params.tipo = this.tipo_comprobante;
              params.dateStart = date[0];
              params.dateEnd = date[1];

              let url= '/venta/filtercaja';

              axios.post(url, params).then((res) => {
                me.arrayVenta = res.data.ventas.data
                me.pagination= res.data.pagination;
                console.log(me.pagination);

              })
            },
            generetePDF(){
              let date = $('#dateCaja').val().split("-");

              let params = {};
              let dateStart = date[0];
              let dateEnd = date[1];

              let url = '/venta/reportcaja?dateStart='+dateStart+'&tipo_comprobante='+this.tipo_comprobante+'&dateEnd='+dateEnd;
              window.open(url);
            },
            configDateRangePicker(){
              $('#dateCaja').daterangepicker({
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
                   "separator": "-",
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
          this.setCurrentDate();
          this.getClientes();
          this.filterCaja(1);
        //  this.listarVenta(1,this.buscar,this.criterio);
        }
    }
</script>
<style>


</style>
