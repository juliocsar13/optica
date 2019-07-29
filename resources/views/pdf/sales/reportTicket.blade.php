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
          <p class="centrado" style="margin-top:10px">TICKET DE VENTA
            <br>{{$sucursal->razon_social_s}}
            <br>{{$sucursal->direccion_s}}
            <br>paciente: <span>{{$paciente->paciente}}</span>
            <br><?php echo date("d/m/Y");?></p>
        </div>
        <table style="margin-top:-30px" >
          <thead>
            <tr>
              <th style="font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">CANT.</th>
              <th style="font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">ARTICULO</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ventas as $venta)
              <tr>
                <td style="font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">{{$venta->cantidad}}</td>
                <td style="font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">{{$venta->producto}}</td>
              </tr>
            @endforeach

          </tbody>
          <tr class=" centrado" style="margin-top:-10px;border-top: 1px solid black;">
            <td class=" centrado" style="border-bottom: 1px solid white;font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">Total</td>
            <td class=" centrado" style="border-bottom: 1px solid white;font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">S/{{$totales->total}}</td>
          </tr>
          <tr class=" centrado" style="border-top: 2px solid gray; margin-top:-10px">
            <td class=" centrado" style="border-bottom: 1px solid white;font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">Adelanto</td>
            <td class=" centrado" style="border-bottom: 1px solid white;font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">S/{{$adelanto->adelanto}}</td>
          </tr>
          <tr class=" centrado" style="border-top: 2px solid gray; margin-top:-10px">
            <td class=" centrado" style="border-bottom: 1px solid white;font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">Pendiente</td>
            <td class=" centrado" style="border-bottom: 1px solid white;font-size:13px;text-align:center;padding:5px;padding: 10px;position: relative;outline: 0;">S/{{$pendiente->pendiente}}</td>
          </tr>
        </table>

        <div>
          <p class="centrado">
            <br>¡GRACIAS POR SU COMPRA!
            <br>{{$sucursal->razon_social_s}}
            <br>{{$sucursal->telefono_s}}
        </div>
      </div>

  </main>
</body>
</html>
