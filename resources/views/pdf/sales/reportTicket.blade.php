<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte por cliente</title>
    <link href="css/invoice.css" rel="stylesheet">
    <style media="screen">
      * {
        font-size: 11px;
        font-family: medium-content-sans-serif-font,"Lucida Grande","Lucida Sans Unicode","Lucida Sans",Geneva,Arial,sans-serif!important;

      }



      /*border: 1px solid black;*/
      table {
        border-top: 1px solid black;
      border-spacing: 0;
      border-collapse: collapse;
  }
    td {
      padding: 5px;
      text-align: center;
      font-size: 11px;
  }
    .ticket {
        width: 155px;
        max-width: 155px;
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
  <main class="ticket">
    <div class="ticket centrado">
        <div style="text-align:center;margin-left:5px">

          <img src="./img/logo.png" style="width: 150px;">
          <p class="centrado" style="margin-top:10px">TICKET DE VENTA
            <br>{{$sucursal->razon_social_s}}
            <br>{{$sucursal->direccion_s}}
            <br>paciente: <span>{{$paciente->paciente}}</span>
            <br><?php echo date("d/m/Y");?></p>
        </div>
        <table style="margin-top:-30px;font-size: 12px;"    >
          <thead>
            <tr>
              <th class="cantidad centrado" style="font-size: 12px;border-bottom: 1px dashed black;border-top: 1px solid white">CANT.</th>
              <th class="producto centrado" style="font-size: 12px;border-bottom: 1px dashed black;border-top: 1px solid white">ARTICULO</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ventas as $venta)
              <tr>
                <td class="cantidad centrado" >{{$venta->cantidad}}</td>
                <td class="producto centrado">{{$venta->producto}}</td>
              </tr>
            @endforeach
              <tr>
                <td class="cantidad centrado" style="border-top: 1px dashed black">Total</td>
                <td class="producto centrado" style="border-top: 1px dashed black">S/{{$totales->total}}</td>
              </tr>
              <tr>
                <td class="cantidad centrado">Adelanto</td>
                <td class="producto centrado">S/{{$adelanto->adelanto}}</td>
              </tr>
              <tr style="border-bottom: 1px dashed black">
                <td class="cantidad centrado" style="border-bottom: 1px dashed black">Pendiente</td>
                <td class="producto centrado" style="border-bottom: 1px dashed black">S/{{$pendiente->pendiente}}</td>
              </tr>
          </tbody>

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
