<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte por cliente</title>
    <link href="css/invoice.css" rel="stylesheet">
    <style media="screen">
      td,th {
        padding: 5px 0 !important;
      }
      #invoice {
          background: white;
          width: auto;
          padding: 10px;
          margin: auto;
          border-radius: 4px;
      }
      .margin-left-5{
        margin-left: 40px;
      }
      .content-td {
        padding: 5px;
        font-size: 14px;
        text-align: center;
      }
      .border-th {
        text-align: center;
        font-size: 10px;
        width: 12.5%;
        border-bottom: 0px solid #ddd;
      }
      .invoice-title {
        padding: 10px;
        line-height: 15px;
        font-family: medium-content-sans-serif-font,"Lucida Grande","Lucida Sans Unicode","Lucida Sans",Geneva,Arial,sans-serif!important;
        font-size: 15px!important;
      }
      footer {
           position: fixed;
           bottom: 0cm;
           left: 0cm;
           right: 0cm;
           height: 2cm;
           background-color: white;
           color: black;
           text-align: center;
           line-height: 1.5cm;
       }
       .info-user {
         display: inline-block;
         font-weight: lighter;
       }
       .table-sales tr:nth-child(even) {
         background-color: #f2f2f2;
       }
       .table-child-info tr:nth-child(even) {
         background-color: white;

       }
       .total-bottom {
         border-bottom: 0px;
       }
    </style>
</head>
<body>


  <footer>
     <?php echo date("d/m/Y") ?>
  </footer>
  <main>
  <div id="invoice">
  	<div class="row">

  		<div class="col-md-6 ">
  			<p id="details">
          <table style="width: 100%;border-bottom: 0px solid #ddd;" >
            <tbody>
              <tr>
            		<td ><div id="logo"><img src="img/logo.png" alt=""></div></td>
                <td style="text-align: right;padding: 0 !important;"> </td>
              </tr>
              <tr>
                <td><strong class="margin-bottom-5"></strong></td>
                <td style="text-align: right;padding: 0 !important;">Fecha:  <?php echo date("d/m/Y");?></td>
              </tr>
            </tbody>
          </table>
  			</p>

  		</div>
  	</div>
  	<div class="row">
  		<div class="col-md-12">
  			<h2>Historia clínica</h2>
  		</div>
      <div class="col-md-12">
        <table style="width: 100%;border-bottom: 0px solid #ddd;">
          <tbody>
            <tr>
              <td style="width:60%"><strong class="margin-bottom-5"><h4>Proveedor</h4></strong></td>
              <td style="width:40%"><strong class="margin-bottom-5"><h4>Cliente</h4></strong></td>
            </tr>
            <tr >
              <td class="invoice-title"><strong class="place-user">Empresa:</strong> <div class="info-user"> <?php echo e($sucursal->razon_social_s); ?></div></td>
              <td class="invoice-title">Nombre(s): <?php echo e($cliente->nombre); ?></td>
            </tr>
            <tr>
              <td class="invoice-title"><?php echo e($sucursal->tipo_documento_s); ?>: <?php echo e($sucursal->num_documento_s); ?></td>
              <td class="invoice-title"><?php echo e($cliente->tipo_documento); ?>: <?php echo e($cliente->num_documento); ?></td>
            </tr>
            <tr>
              <td class="invoice-title">Telefono: <?php echo e($cliente->telefono_s); ?></td>
              <td class="invoice-title">Telefono: <?php echo e($cliente->telefono); ?></td>
            </tr>

            <tr>
              <td class="invoice-title">Direccion: <?php echo e($sucursal->direccion_s); ?></td>
              <td class="invoice-title">Email: <?php echo e($cliente->email); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
  	</div>
  	<div class="row">
  		<div class="col-md-12">
  			<table class="margin-top-20 ">
  				<tr>
  					<th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">Cliente</th>
  					<th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">Documento</th>
            <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">Detalle</th>

  					<th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">Luna</th>
  					<th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">Precio</th>
  				</tr>
          <?php $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
    					<td class="content-td" style="border-bottom: 1px solid #ddd;"><?php echo e($venta->nombre); ?></td>
    					<td  class="content-td" style="border-bottom: 1px solid #ddd;"><?php echo e($venta->tipo_documento); ?> - <?php echo e($venta->num_documento); ?></td>
    					<td  class="content-td" style="border-bottom: 1px solid #ddd;"><?php echo e($venta->tipo_comprobante); ?> - <?php echo e($venta->serie_comprobante); ?> - <?php echo e($venta->num_comprobante); ?></td>
              <td  class="content-td" style="border-bottom: 1px solid #ddd;"><?php echo e($venta->usuario); ?></td>

    					<td  class="content-td" style="border-bottom: 1px solid #ddd;"><?php echo e($venta->total); ?></td>
    				</tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <tr class="total-bottom">
            <td class="total-bottom"></td>
            <td class="total-bottom"></td>
            <td class="total-bottom">Total</td>
            <td class="total-bottom"><span><?php echo e($totales); ?></span></td>
          </tr>
  			</table>

  		</div>
  	</div>
  </div>
  </main>

</body>
</html>
