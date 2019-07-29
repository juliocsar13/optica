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
      body{
        font-family: medium-content-sans-serif-font,"Lucida Grande","Lucida Sans Unicode","Lucida Sans",Geneva,Arial,sans-serif!important;

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
           bottom: 2cm;
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
         border-bottom-color:white;
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
         margin-top:5px;
       }
    </style>
</head>
<body>


  <footer>
      <div class="">
          <div style="text-align:left">
            GRACIAS!
          </div>
          <table>
            <tr>
              <td style="height:60px;width:8%;border-bottom: 1px solid white;border-top: 1px solid #bcbbbe;"><img src="./img/favicon.png" alt="" style="width:40px;height:40px"> </td>
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
  <div id="invoice">
  	<div class="row">
      <table>
        <tr>
          <td><img src="img/logo.png" style="width:200px" alt=""/></td>
          <td style="text-align:center">
            <div style="text-align:center;padding:28px;width:230px;height:250px;background-color:#212121;color:white;position:absolute;top:-65px;left:400px;">
              <h1 style="color:white;margin-top:50px; font-size:50px">PAGOS</h1>
              <table style="margin-top:50px">

                <tr>
                  <td style="text-align:left">Usuario</td>
                  <td style="text-align:right"><?php echo e($cliente->nombre); ?></td>
                </tr>
                <tr>
                  <td style="text-align:left">Fecha</td>
                  <td style="text-align:right"><?php echo date("d/m/Y");?></td>
                </tr>
                <tr>
                  <td style="text-align:left">Monto Total</td>
                  <td style="text-align:right">S/.<?php echo e($totales); ?></td>
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
                <strong class="margin-bottom-5">
                  <h5>REPORTE DE </h5>
                  <h4 style="font-weight: bold">CUENTAS POR PAGAR</h4>
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
  	<div class="row">
  		<div class="col-md-12">
  			<table class=" table  ">
  				<tr style="position:relative">
            <th class="table-cobro" style="width:50px"><div class="title">#</div></th>
  					<th class="table-cobro"><div class="title">PROVEEDOR</div></th>
  					<th class="table-cobro"><div class="title">DNI/RUC</div></th>
  					<th class="table-cobro"><div class="title">COMPROBANTE</div></th>
  					<th class="table-cobro"><div class="title">MONTO</div></th>
            <th class="table-cobro"><div class="title">ABONO</div></th>
            <th class="table-cobro"><div class="title">SALDO</div></th>
  				</tr>
          <?php $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td  class="content-td table-content" style="border-bottom: 1px solid #bcbbbe;background-color:#d4d4d4"><div class="subtitle"><?php echo e($key+1); ?></div></td>
    					<td  class="content-td table-content" style="border-bottom: 1px solid #bcbbbe;"><div class="subtitle"><?php echo e($venta->nombre); ?></div></td>
    					<td  class="content-td table-content" style="border-bottom: 1px solid #bcbbbe;background-color:#d4d4d4"><div class="subtitle"><?php echo e($venta->num_documento); ?></div></td>
    					<td  class="content-td table-content" style="border-bottom: 1px solid #bcbbbe;"><div class="subtitle"><?php echo e($venta->serie_comprobante); ?> - <?php echo e($venta->num_comprobante); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #bcbbbe;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($venta->total); ?></div></td>
    					<td  class="content-td table-content" style="border-bottom: 1px solid #bcbbbe;"><div class="subtitle">S/<?php echo e($venta->adelantoI); ?></div></td>
              <td  class="content-td table-content" style="border-bottom: 1px solid #bcbbbe;background-color:#d4d4d4"><div class="subtitle">S/<?php echo e($venta->pendienteI); ?></div></td>
    				</tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <tr  style="margin-top:100px;position:absolute;top:20px">
            <td style="border-bottom: 1px solid white"></td>
            <td style="border-bottom: 1px solid white"></td>
            <td style="border-bottom: 1px solid white"></td>
            <td style="border-bottom: 1px solid white"></td>

            <td style="border-bottom: 1px solid white"></td>
            <td class="total-bottom table-content" style="border: 1px solid #bcbbbe;"><div class="subtitle">TOTAL</div></td>
            <td class="total-bottom table-content" style="border: 1px solid #bcbbbe;"><span><div class="subtitle">S/<?php echo e($totales); ?></div></span></td>
          </tr>
  			</table>

  		</div>
  	</div>
  </div>
  </main>

</body>
</html>
