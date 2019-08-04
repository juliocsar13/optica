<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte por cliente</title>
    <link href="css/invoice.css" rel="stylesheet">
    <style media="screen">
    .page-break {
          page-break-after: always;
      }
      body{
        font-family: medium-content-sans-serif-font,"Lucida Grande","Lucida Sans Unicode","Lucida Sans",Geneva,Arial,sans-serif!important;

      }
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
           height: 1cm;
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
       .table-cobro {
         position:relative;
         height:35px;
         text-align:center;
         background-color:#212121;
         font-size: 14px;
         color:white;
       }
       .table-cobro .title {
         margin-top:5px;
       }
       .table-content {
         position:relative;
         height:35px;
         text-align:center;
         font-size: 14px;
       }
       .table-content .subtitle {
         margin-top: 8px;
         vertical-align:middle;
       }
    </style>
</head>
<body>
  <footer>
      <div class="">
          <div style="text-align:left">
          </div>
          <table>
            <tr>
              <td style="height:60px;width:8%;border-bottom: 1px solid white;border-top: 1px solid #bcbbbe;"><img src="./img/favicon.png" alt="" style="width:40px;height:40px;margin-top:10px"> </td>
              <td style="height:60px;width:30%;border-bottom: 1px solid white;border-top: 1px solid #bcbbbe;">
                <div style="border-left:1px solid #bcbbbe;line-height: normal">
                  <label style="position:absolute;margin-left:5px;font-weight:bold">Direccion:</label> <br>
                  <label style="position:absolute;margin-left:5px;font-size:13px"><?php echo e($sucursal->direccion_s); ?></label>
                </div>
              </td>
              <td style="height:60px;width:30%;border-bottom: 1px solid white;border-top: 1px solid #bcbbbe;">
                <div style="border-left:1px solid #bcbbbe;line-height: normal">
                  <label style="position:absolute;margin-left:5px; font-weight:bold">Telefono:</label> <br>
                  <label style="position:absolute;font-size:13px"><?php echo e($sucursal->telefono_s); ?></label>
                </div>
              </td>
              <td style="height:60px;width:30%;border-bottom: 1px solid white;border-top: 1px solid #bcbbbe;">
                <div style="border-left:1px solid #bcbbbe;line-height: normal">
                  <label style="position:absolute;margin-left:5px;font-weight:bold">Email:</label> <br>
                  <label style="position:absolute;margin-left:5px;font-size:13px"><?php echo e($sucursal->email_s); ?></label>
                </div>
              </td>
            </tr>
          </table>
      </div>
  </footer>
  <main>
  <div>
    <div class="row">
      <table>
        <tr>
          <td><img src="img/logo.png" style="width:200px" alt=""/></td>
          <td style="text-align:center">
            <div style="text-align:center;padding:28px;width:230px;height:250px;background-color:#212121;color:white;position:absolute;top:-65px;left:400px;">
              <h1 style="color:white;margin-top:20px; font-size:50px">CAJA</h1>
              <table style="margin-top:50px; line-height:normal">
                <tr>
                  <td style="text-align:left">Fecha consulta</td>
                  <td style="text-align:right"><?php echo e(Request::get('date')); ?></td>
                </tr>
                <tr>
                  <td style="text-align:left">Comprobante</td>
                  <td style="text-align:right"><?php echo e(Request::get('tipo_comprobante')); ?></td>
                </tr>
                <tr>
                  <td style="text-align:left">Fecha actual</td>
                  <td style="text-align:right"><?php echo date("d/m/Y");?></td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
        <tr>
      </tr>
    </table>
  </div>
    <div class="row">
      <div class="col-md-12">
        <table style="width: 100%;border-bottom: 0px solid #ddd;" >
          <tbody>
            <tr>
              <td style="width:60%">
                <strong class="">
                  <h5>REPORTE DE </h5>
                  <h4 style="font-weight: bold">CAJA POR DIA</h4>
                </strong>
              </td>
            </tr>
            <tr >
              <td class="invoice-title" ><div class="info-user"> <?php echo e($sucursal->razon_social_s); ?></div></td>
            </tr>
            <tr>
              <td class="invoice-title"><?php echo e($sucursal->direccion_s); ?></td>
            </tr>
            <tr>
              <td class="invoice-title"><?php echo e($sucursal->num_documento_s); ?></td>
            </tr>
            <tr>
              <td class="invoice-title"><?php echo e($cliente->telefono_s); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <?php if($count > 7): ?>
    <div class="row page-break" >
    <?php else: ?>
    <div class="row" >

    <?php endif; ?>
      <div class="col-md-12">
        VENTAS
  			<table class="table">
  				<tr>
  					<th class="table-cobro" style="width:50px"><div class="title">#</div></th>
            <th class="table-cobro" style="width:170px"><div class="title">USUARIO</th>
            <th class="table-cobro"><div class="title">DESCRIPCION</div></th>
            <th class="table-cobro"><div class="title">T.PAGO</div></th>
  					<th class="table-cobro"><div class="title">TOTAL</div></th>
  					<th class="table-cobro"><div class="title">ADELANTO</div></th>
            <th class="table-cobro"><div class="title">SALDO</div></th>
  				</tr>
          <?php $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle"><?php echo e($key+1); ?></div></td>
    					<td  class="content-td table-content" style="border-bottom: 1px solid #ddd;"><div class="subtitle"><?php echo e($venta->usuario); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle"><?php echo e($venta->serie_comprobante); ?> - <?php echo e($venta->num_comprobante); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;"><div class="subtitle"><?php echo e($venta->forma_pago); ?></div></td>
    					<td  class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($venta->total); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;"><div class="subtitle">S/<?php echo e($venta->adelanto); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($venta->pendiente); ?></div></td>
    				</tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  			</table>
  		</div>
  	</div>
    </div>
    <div class="row ">
      <div class="col-md-12" >
        <div class="">
          COMPRAS
        </div>
        <table class="table">
          <tr>
            <th class="table-cobro" style="width:50px"><div class="title">#</div></th>
            <th class="table-cobro" style="width:170px"><div class="title">USUARIO</div></th>
            <th class="table-cobro"><div class="title">DESCRIPCION</div></th>
            <th class="table-cobro"><div class="title">T.PAGO</div></th>
            <th class="table-cobro"><div class="title">TOTAL</div></th>
            <th class="table-cobro"><div class="title">ADELANTO</div></th>
            <!--<th class="table-cobro"><div class="title">SALDO</div></th>-->
          </tr>
          <?php $__currentLoopData = $ingresos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle"><?php echo e($key+1); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;"><div class="subtitle"><?php echo e($venta->usuario); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle"><?php echo e($venta->serie_comprobante); ?> - <?php echo e($venta->num_comprobante); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;"><div class="subtitle"><?php echo e($venta->forma_pago); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($venta->total); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;"><div class="subtitle">S/<?php echo e($venta->adelanto); ?></div></td>
              <!--<td  class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($venta->pendiente); ?></div></td>-->
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
      </div>
    </div>
    <?php if($count < 4 && $count_i < 4): ?>
    <div class="row " style="width: 100%;">
    <?php else: ?>
    <div class="row" style="width: 100%; margin-top:130px">
    <?php endif; ?>
  		<div class="col-md-12">
  			<table class="table "   >
  				<tr>
  					<th class="table-cobro" style="border-bottom: 1px solid black"><div class="title" >#</div></th>
            <th class="table-cobro"><div class="title">COMPRAS</div></th>
            <th class="table-cobro"><div class="title">VENTAS</div></th>
            <th class="table-cobro"><div class="title">MOVIMIENTOS</div></th>

            <th class="table-cobro"><div class="title">CAJA</div></th>
  				</tr>
          <tr>
            <th class="table-cobro" style="border-bottom: 1px solid black;text-align:center"><div class="title">EFECTIVO</div></th>
            <td class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($total_ingresos_contado); ?>.00</div></td>
            <td class="content-td table-content"><div class="subtitle">S/<?php echo e($total_cobros_contado); ?>.00</div></td>
            <td class="content-td table-content"><div class="subtitle">S/<?php echo e($total_movimientos); ?>.00</div></td>
            <td class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($total_cobros_contado-$total_ingresos_contado + $total_movimientos +$caja); ?>.00</div></td>

          </tr>
          <tr>
            <th class="table-cobro" style="border-bottom: 1px solid black;text-align:center"><div class="title">TARJETA</div></th>
            <td class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($total_ingresos_credito); ?>.00</div></td>
            <td class="content-td table-content" ><div class="subtitle">S/<?php echo e($total_cobros_credito); ?>.00</div></td>
            <td class="content-td table-content"><div class="subtitle">S/0.00</div></td>

            <td class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($total_cobros_credito-$total_ingresos_credito); ?>.00</div></td>
          </tr>
          <tr>
            <th class="table-cobro" style="border-bottom: 1px solid black"><div class="title">TOTAL</div></th>
            <td class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($total_ingresos); ?>.00</div></td>
            <td class="content-td table-content" ><div class="subtitle">S/<?php echo e($total_cobros); ?>.00</div></td>
            <td class="content-td table-content" ><div class="subtitle">S/<?php echo e($total_movimientos); ?>.00</div></td>

            <td class="content-td table-content" style="border-bottom: 1px solid #ddd;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($total_cobros-$total_ingresos+$total_movimientos+$caja); ?>.00</div></td>

          </tr>

        </table>
      </div>
    </div>
  </div>
  </main>

</body>
</html>
