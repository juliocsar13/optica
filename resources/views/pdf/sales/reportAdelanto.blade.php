<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte por cliente</title>
    <link href="css/invoice.css" rel="stylesheet">
    <style media="screen">
      * {
        font-size: 11px;
      }
      body {
        font-size: 11px;

        font-family: medium-content-sans-serif-font,"Lucida Grande","Lucida Sans Unicode","Lucida Sans",Geneva,Arial,sans-serif!important;
      }
      td,
      th,
      tr,
      table {
        border-top: 1px solid black;
        border-collapse: collapse;
      }

      td.producto,
      th.producto {
        width: 75px;
        max-width: 75px;
      }

      td.cantidad,
      th.cantidad {
        width: 40px;
        max-width: 40px;
        word-break: break-all;
      }

      td.precio,
      th.precio {
        width: 40px;
        max-width: 40px;
        word-break: break-all;
      }

      .centrado {
        text-align: center;
        align-content: center;
      }

      .ticket {
        width: 155px;
        max-width: 155px;
      }

      img {
        max-width: inherit;
        width: inherit;
      }

      @media print{
        .oculto-impresion, .oculto-impresion *{
          display: none !important;
        }
      }
    </style>
</head>
<body class="centrado">
  <main>
    <div class="ticket centrado">
        <div style="text-align:center;margin-left:5px">

          <img src="./img/logo.png" style="width: 150px;">
          <p class="centrado" style="margin-top:10px">TICKET DE ADELANTO
            <br>{{$sucursal->razon_social_s}}
            <br>{{$sucursal->direccion_s}}
            <br>{{$sucursal->telefono_s}}

            <br><?php echo date("d/m/Y");?></p>
        </div>
        <table style="margin-top:-20px">
          <thead>
            <tr>
              <th class="cantidad centrado" style="border-bottom: 1px solid black; font-size:13px">CANT.</th>
              <th class="producto centrado" style="border-bottom: 1px solid black; font-size:13px"> PRODUCTO</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ventas as $venta)
              <tr>
                <td class="cantidad" style="text-align:center;padding: 10px; position: relative; outline: 0; ">{{$venta->cantidad}}</td>
                <td class="producto" style="text-align:center;padding: 10px; position: relative; outline: 0;">{{$venta->producto}}</td>
              </tr>
            @endforeach

            <tr>
              <td class=" centrado" style="text-align:center; border-bottom: 1px solid white;padding: 10px; position: relative; outline: 0;">Total</td>
              <td class=" centrado" style="text-align:center;border-bottom: 1px solid white;padding: 10px; position: relative; outline: 0;">S/{{$totales->total}}</td>
            </tr>
            <tr>
              <td class=" centrado" style="text-align:center; border-bottom: 1px solid white;padding: 10px; position: relative; outline: 0;">Pendiente</td>
              <td class=" centrado" style="text-align:center;border-bottom: 1px solid white;padding: 10px; position: relative; outline: 0;">S/{{$pendiente->pendiente}}</td>
            </tr>
            <tr>
              <td class=" centrado" style="text-align:center; border-bottom: 1px solid white;padding: 10px; position: relative; outline: 0;">Adelanto</td>
              <td class=" centrado" style="text-align:center;border-bottom: 1px solid white;padding: 10px; position: relative; outline: 0;">S/{{$adelanto->adelanto}}</td>
            </tr>
          </tbody>
        </table>
        <div class="">
          {{$sucursal->razon_social_s}}
          <p class="centrado">¡GRACIAS POR SU COMPRA!
          </p>
        </div>
      </div>

  </main>
</body>
</html>
