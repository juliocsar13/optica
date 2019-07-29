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
                        <i class="fa fa-align-justify"></i> Ventas
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!-- Listado-->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="tipo_comprobante">Tipo Comprobante</option>
                                      <option value="num_comprobante">Número Comprobante</option>
                                      <option value="created_at">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
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
                                        <th>Cliente</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie - Nro Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <!--<th>Impuesto</th>-->
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td style="width:22%">

                                            <button type="button" title="Ver Detalle" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button>
                                            <button type="button" title="Ver reporte" @click="pdfVenta(venta.id, venta.tipo_comprobante)" class="btn btn-info btn-sm">
                                            <i class="icon-doc"></i>
                                            </button>
                                            <template v-if="venta.idproveedor>='1'">
                                            <button type="button"  @click="pdfVenta2(venta.id)" class="btn btn-info btn-sm">
                                            <i class="icon-note"></i>
                                            </button>
                                            </template>
                                            <template v-if="venta.estado=='Registrado'">
                                                <button type="button" title="Eliminar" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                            <template v-if="venta.pendiente>'1'">
                                                <button type="button" title="Cancelar" class="btn btn-success btn-sm" @click="desactivarPendiente(venta.id)">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </template>
                                            <template v-if="venta.pendiente>'1'">
                                              <button type="button" title='Ver Ticket Adelanto' @click="pdfTicketAdelanto(venta.id, venta.tipo_comprobante)" class="btn btn-info btn-sm">
                                                <i class="icon-doc"></i>
                                              </button>
                                            </template>
                                            <template v-if="venta.pendiente==0 ">
                                              <a  title="Descargar Archivos Planos" class="btn btn-success btn-sm"  @click='DescargarArchivos(venta.id)' >
                                                <i class="icon-doc"></i>
                                              </a>
                                              <!--v-bind:href="'/venta/descargar/'+ venta.id"-->
                                            </template>

                                        </td>
                                        <td style="text-align:center" v-text="venta.usuario"></td>
                                        <td style="text-align:center" v-text="venta.nombre"></td>
                                        <td style="text-align:center" v-text="venta.tipo_comprobante"></td>
                                        <td style="text-align:center">{{venta.serie_comprobante}} - {{venta.num_comprobante}}</td>
                                        <td style="text-align:center" v-text="venta.created_at"></td>
                                        <td style="text-align:center" v-text="venta.total"></td>
                                        <!--<td v-text="venta.impuesto"></td>-->
                                        <td v-text="venta.estado"></td>
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
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <v-select
                                        :on-search="selectCliente"
                                        label="nombre"
                                        :options="arrayCliente"
                                        placeholder="Buscar Clientes..."
                                        :onChange="getDatosCliente">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Sucursal(*)</label>
                                    <v-select
                                        :on-search="selectSucursal"
                                        label="razon_social_s"
                                        :options="arraySucursal"
                                        placeholder="Buscar Sucursales..."
                                        :onChange="getDatosSucursal">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="impuesto" maxlength="4">
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
                                    <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x" maxlength="7">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <label for="">Graduación</label>
                        <div class="form-group row border">
                            <label class="col-md-1 text-center" for=""> <br> OD:</label>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">Esfera</label>
                                    <v-select
                                        :on-search="selectEsfera"
                                        label="valor"
                                        :options="arrayEsfera"
                                        placeholder=""
                                        :onChange="getDatosEsfera">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">Cilindro</label>
                                    <v-select
                                        :on-search="selectCilindro"
                                        label="valor"
                                        :options="arrayCilindro"
                                        placeholder=""
                                        :onChange="getDatosCilindro">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">Eje</label>
                                    <v-select
                                        :on-search="selectEje"
                                        label="valor"
                                        :options="arrayEje"
                                        placeholder=""
                                        :onChange="getDatosEje">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">Add.</label>
                                    <v-select
                                        :on-search="selectAdd"
                                        label="valor"
                                        :options="arrayAdd"
                                        placeholder=""
                                        :onChange="getDatosAdd">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">D.I.P</label>
                                    <v-select
                                        :on-search="selectDip"
                                        label="valor"
                                        :options="arrayDip"
                                        placeholder=""
                                        :onChange="getDatosDip">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">A.V.</label>
                                    <v-select
                                        :on-search="selectAv"
                                        label="valor"
                                        :options="arrayAv"
                                        placeholder=""
                                        :onChange="getDatosAv">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Prisma</label>
                                    <input type="text" class="form-control" v-model="prisma" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <v-select
                                        :on-search="selectProveedor"
                                        label="nombre"
                                        :options="arrayProveedor"
                                        placeholder="Buscar Proveedores..."
                                        :onChange="getDatosProveedor">
                                    </v-select>
                                </div>
                            </div>
                            <label class="col-md-1 text-center" for=""> <br> OI:</label>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">Esfera</label>
                                    <v-select
                                        :on-search="selectEsfera"
                                        label="valor"
                                        :options="arrayEsfera"
                                        placeholder=""
                                        :onChange="getDatosEsfera2">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">Cilindro</label>
                                    <v-select
                                        :on-search="selectCilindro"
                                        label="valor"
                                        :options="arrayCilindro"
                                        placeholder=""
                                        :onChange="getDatosCilindro2">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">Eje</label>
                                    <v-select
                                        :on-search="selectEje"
                                        label="valor"
                                        :options="arrayEje"
                                        placeholder=""
                                        :onChange="getDatosEje2">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-1"></div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="">A.V.</label>
                                    <v-select
                                        :on-search="selectAv"
                                        label="valor"
                                        :options="arrayAv"
                                        placeholder=""
                                        :onChange="getDatosAv2">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Prisma</label>
                                    <input type="text" class="form-control" v-model="prisma2" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Fecha de entrega:</label>
                                    <input type="date" class="form-control" v-model="referencia" placeholder="Ref. de fecha de entrega">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Forma de pago</label>
                                    <select class="form-control" v-model="forma_pago">
                                        <option value="0">Seleccione</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Visa">Visa</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row border">
                            <div class="col-md-4">
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
                                    <label>Descuento</label>
                                    <input type="number" value="0" class="form-control" v-model="descuento">
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
                                            <th>Descuento</th>
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
                                                <input v-model="detalle.precio" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span>
                                                <input v-model="detalle.cantidad" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red" v-show="detalle.descuento>(detalle.precio*detalle.cantidad)">Descuento Superior</span>
                                                <input v-model="detalle.descuento" type="number" class="form-control">
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad-detalle.descuento}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                                            <td>S/. {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Impuesto:</strong></td>
                                            <!--<td>S/. {{totalImpuesto=(total*impuesto).toFixed(2)}}</td>-->
                                            <td>S/. {{totalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                                            <td>S/. {{total=calcularTotal}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Adelanto:</strong></td>
                                            <td>S/. <input type="text" v-model="adelanto"></td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Adelanto 2 (Solo Referencial):</strong></td>
                                            <td>S/. <input type="text" v-model="adelanto_v"></td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Pendiente:</strong></td>
                                            <td>S/. {{pendiente=total-adelanto}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Material:</strong></td>
                                            <td><input type="text" v-model="material"></td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Costo Material:</strong></td>
                                            <td>S/. <input type="text" v-model="cmaterial"></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
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
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
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
                                                {{detalle.precio*detalle.cantidad-detalle.descuento}}
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
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Adelanto:</strong></td>
                                            <td>S/. {{adelanto}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Adelanto 2(Solo Referencial):</strong></td>
                                            <td>S/. {{adelanto_v}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Pendiente:</strong></td>
                                            <td>S/. {{pendiente}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Material:</strong></td>
                                            <td>{{material}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Costo Material:</strong></td>
                                            <td>S/. {{cmaterial}}</td>
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
                        <div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y: auto;">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterioP">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Código</option>
                                        </select>
                                        <input type="text" v-model="buscarP" @keyup.enter="listarProducto(buscarP,criterioP)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarProducto(buscarP,criterioP)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" >
                                <table  class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Familia</th>
                                            <th>Precio Venta</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="producto in arrayProducto" :key="producto.id">
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(producto)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="producto.codigo"></td>
                                            <td v-text="producto.nombre"></td>
                                            <td v-text="producto.nombre_familia"></td>
                                            <td v-text="producto.precio_venta"></td>
                                            <td v-text="producto.stock"></td>
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
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
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

                idproveedor: '',
                arrayProveedor: [],

                esfera: '',
                arrayEsfera: [],

                cilindro: '',
                arrayCilindro: [],

                eje: '',
                arrayEje: [],

                add: '',
                arrayAdd: [],

                dip: '',
                arrayDip: [],

                av: '',
                arrayAv: [],

                prisma: '',

                esfera2: '',
                cilindro2: '',
                eje2: '',
                av2: '',
                prisma2: '',
                referencia: '',

                adelanto:'',
                pendiente:'',

                forma_pago:'Efectivo',

                adelanto_v:'',

                arraySucursal: [],

                material: '',
                cmaterial: ''
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
            listarVenta (page,buscar,criterio){
                let me=this;
                var url= '/venta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayVenta = respuesta.ventas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
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
            pdfVenta(id, tipo){
              let url = 'http://localhost:8000/venta/pdf';
              if (tipo == 'TICKET') window.open(url+'/'+id+'/'+tipo);
              if (tipo == 'BOLETA') window.open(url+'/'+id+'/'+tipo);
              if (tipo == 'FACTURA') window.open(url+'/'+id+'/'+tipo);
            },
            pdfTicketAdelanto(id, tipo){
              let url = 'http://localhost:8000/venta/pdf/ticket/adelanto';
              if (tipo == 'TICKET') window.open(url+'/'+id+'/'+tipo);
              if (tipo == 'BOLETA') window.open(url+'/'+id+'/'+tipo);
              if (tipo == 'FACTURA') window.open(url+'/'+id+'/'+tipo);
            },
            pdfVenta2(id){
              window.open('http://localhost:8000/venta/pdf2/'+ id + ',' + '_blank');
            },
            pdfTicket(id) {
              window.open('http://localhost:8000/venta/pdf/ticket'+ id + ',' + '_blank');

            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual.
                me.pagination.current_page = page;
                //Envia la peticion para visualizar la data de esa pagina.
                me.listarVenta(page,buscar,criterio);
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
                                stock: me.stock,
                                cmaterial: me.cmaterial
                            });
                            me.codigo="";
                            me.idproducto=0;
                            me.producto="";
                            me.cantidad=0;
                            me.precio=0;
                            me.descuento=0;
                            me.stock=0
                            me.cmaterial=0;
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
                    else {
                        me.arrayDetalle.push({
                            idproducto: data['id'],
                            producto: data['nombre'],
                            cantidad: 1,
                            precio: data['precio_venta'],
                            descuento: 0,
                            codigo: data['codigo'],
                            descripcion: data['descripcion'],
                            stock:data['stock'],
                        });
                    }
            },
            listarProducto (buscar,criterio){
                let me=this;
                var url= '/producto/listarProductoVenta?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                  console.log(response);
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
                    'forma_pago': this.forma_pago,
                    'adelanto_v': this.adelanto_v,
                    'idsucursal': this.idsucursal,
                    'material': this.material,
                    'cmaterial': this.cmaterial
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
                    me.adelanto_v='';
                    me.idsucursal = '';
                    me.material = '';
                    me.cmaterial = '';

                    window.open('http://127.0.0.1:8000/venta/pdf/'+ response.data.id + ',' + '_blank');

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
                me.material='';
                me.cmaterial='';
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
                    me.adelanto = arrayVentaT[0]['adelanto'];
                    me.pendiente = arrayVentaT[0]['pendiente'];
                    me.adelanto_v = arrayVentaT[0]['adelanto_v'];
                    me.material = arrayVenta[0]['material'];
                    me.cmaterial = arrayVenta[0]['cmaterial'];
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
            abrirModal(modelo, accion, data = []){
                this.arrayProducto=[];
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios productos';
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
            selectEsfera(search,loading){
                let me=this;
                loading(true)

                var url= '/graduacion/selectEsfera?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayEsfera=respuesta.esferas;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosEsfera(val3){
                let me = this;
                me.loading = true;
                me.esfera = val3.valor;
            },
            selectCilindro(search,loading){
                let me=this;
                loading(true)

                var url= '/graduacion/selectCilindro?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayCilindro = respuesta.cilindros;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCilindro(val4){
                let me = this;
                me.loading = true;
                me.cilindro = val4.valor;
            },
            selectEje(search,loading){
                let me=this;
                loading(true)

                var url= '/graduacion/selectEje?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayEje = respuesta.ejes;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosEje(val5){
                let me = this;
                me.loading = true;
                me.eje = val5.valor;
            },
            selectAdd(search,loading){
                let me=this;
                loading(true)

                var url= '/graduacion/selectAdd?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayAdd=respuesta.adds;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosAdd(val6){
                let me = this;
                me.loading = true;
                me.add = val6.valor;
            },
            selectDip(search,loading){
                let me=this;
                loading(true)

                var url= '/graduacion/selectDip?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayDip=respuesta.dips;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosDip(val7){
                let me = this;
                me.loading = true;
                me.dip = val7.valor;
            },
            selectAv(search,loading){
                let me=this;
                loading(true)

                var url= '/graduacion/selectAv?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayAv=respuesta.avs;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosAv(val8){
                let me = this;
                me.loading = true;
                me.av = val8.valor;
            },
            getDatosEsfera2(val9){
                let me = this;
                me.loading = true;
                me.esfera2 = val9.valor;
            },
            getDatosCilindro2(val10){
                let me = this;
                me.loading = true;
                me.cilindro2 = val10.valor;
            },
            getDatosEje2(val11){
                let me = this;
                me.loading = true;
                me.eje2 = val11.valor;
            },
            getDatosAv2(val12){
                let me = this;
                me.loading = true;
                me.av2 = val12.valor;
            },
            desactivarPendiente(id){
               swal({
                title: 'Esta seguro de cancelar el pendiente de esta venta?',
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

                    axios.put('/venta/desactivarPendiente',{
                        'id': id
                    }).then(function (response) {
                        me.listarVenta(1,'','num_comprobante');
                        swal(
                        'Cancelado!',
                        'El pendiente de la venta ha sido cancelado con éxito.',
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
            selectSucursal(search,loading){
                let me=this;
                loading(true);

                var url= '/sucursal/selectSucursal?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arraySucursal=respuesta.sucursales;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosSucursal(val1){
                let me = this;
                me.loading = true;
                me.idsucursal = val1.id;
            },
            DescargarArchivos(id) {
              var origin = window.location.origin;
              var url= origin+'/venta/descargar/' + id;
              location.href = url;

              setTimeout(()=>{
                var url2= origin+'/venta/descargar2/' + id;
                location.href = url2;
              }, 1000);


            }
        },
        mounted() {
            this.listarVenta(1,this.buscar,this.criterio);
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
        /*position: absolute !important;
        */background-color: #3c29297a !important;
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
