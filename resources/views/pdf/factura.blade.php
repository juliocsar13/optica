<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de venta</title>
    <style>
        body {
          font-family: medium-content-sans-serif-font,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Open Sans","Helvetica Neue",sans-serif;
        }
        .bind-text {
          display: inline-block;
        }
        .data-sucursal {
          font-size: 11px;
          text-align: center;
          width:300px;
          margin-top: -20px;
        }
        .img-logo {
          width: 120px;
          height: 120px;
        }
        .img-logo-text {
          text-align: center;
          font-size: 9px;
          margin-top:-20px;
        }
        .data-name {
          margin-top: -40px;
          text-align: center;
          font-size: 9px;
        }
        .data-credentials {
          width: 260px;
          height: 120px;
          border: solid 1px;
          padding: 15px;
          margin-left: -20px;
          text-align: center;
          border-radius: 10px 10px 10px 10px;
        }
        .data-credentials-number {
          margin-top: 10px;
          font-size: 22px;
        }
        .data-credentials-boleta {
          text-align: center;
          margin-top: 10px;
          background-color: grey;
          font-size: 22px;
        }
        .data-credentials-ruc {
          margin-top: 10px;
          font-size: 24px;
        }
        .data-cliente {
          width: 100%;
          font-size: 13px;
          text-align: justify;
          font-weight: inherit;
          margin-top: 20px;
        }
        .data-cliente-day {
          width: 100px;
          text-align: center;
          display: inline-block;
          border-bottom: 1px black dashed;
        }
        .data-cliente-month {
          width: 140px;
          text-align: center;
          display: inline-block;
          border-bottom: 1px black dashed;
        }
        .data-cliente-year {
          width: 100px;
          text-align: center;
          display: inline-block;
          border-bottom: 1px black dashed;
        }
        .data-cliente-name {
          width: 360px;
          white-space: pre-line;
          text-align: center;
          display: inline-block;
          border-bottom: 1px black dashed;
          line-height: 1.5;
        }
        .table-detalle table, {
          width: 100%;
          text-align: center;
          margin-top: 10px;
        }
        .table-header-ruc{
          border: solid 1px;

        }
        .table-header-client{
          font-size: 9px;
          border: solid 1px;
            text-align: center;
        }
        .table-header-total{
          border: solid 1px;

        }
        .table-cliente-day {
          display: inline-block;
          border-bottom: 1px black dashed;
        }
        .table-cliente-month {
          display: inline-block;
          border-bottom: 1px black dashed;
        }
        .table-cliente-year {
          display: inline-block;
          border-bottom: 1px black dashed;
        }
    </style>
    <body>
        <section>
          <table>
            <thead>
              <tr>
                <th style="width:100px">
                  <img class="img-logo" src="img/logo2.png">
                  <div class="img-logo-text">
                    {{$info->razon_social_s}}
                  </div>
                </th>
                <th class="data-sucursal">
                  <strong>De:</strong> {{$info->razon_social_s}}<br>
                  <strong>Psje.</strong> {{$info->direccion_s}}<br>
                  <strong>Telf.:</strong> {{$info->telefono_s}} / Cel. {{$info->telefono_s}}<br>
                  <strong>E-Mail:</strong> {{$info->email_s}}<br>
                </th>
                <th>
                  <div style="position:absolute; margin-top:0px" class="data-credentials" >
                    <div class="data-credentials-ruc">R.U.C: {{$info->num_documento_s}}</div>
                    <div class="data-credentials-boleta"> FACTURA</div>
                    <div class="data-credentials-number"> {{$info->serie_comprobante}}-{{$info->num_comprobante}}</div>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </section>
        <div>
          <table class="data-cliente" style="width:100%; margin-top:40px">
            <thead>
              <tr>
                <th>Lima, <div class="data-cliente-day">{{$info->day}}</div> de
                          <div class="data-cliente-month">{{$info->month}}</div> del
                          <div class="data-cliente-year">{{$info->year}}</div>
                        </th>
                <th ><div style="margin-right:150px">
                    RUC:
                  <span style="display: inline-block; border-bottom: 1px black dashed;">
                   {{$info->num_documento}}
                  </span>

                  </div>
                </th>
              </tr>
              <tr>
                <th>Señor(es):<div class="data-cliente-name">
                   {{$info->nombre}}
                </div>
                </th>
                <th>
                  <div class=""> Fecha de Emision:
                    <span style="display: inline-block; border-bottom: 1px black dashed;">
                      {{$info->created_at}}
                    </span>
                  </div>
                </th>
              </tr>
              <tr>
                <th>Dirección:
                  <div class="data-cliente-name">
                    {{$info->direccion}}
                  </div>
                 </th>
                 <th>
                   <div class=""> G. Remision:
                     <span style="display: inline-block; border-bottom: 1px black dashed;">
                      gaaaaaaaaaa
                     </span>
                   </div>
                 </th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="table-detalle">
          <table style="border-collapse: separate;border-spacing: 0px;border:solid black 1px;border-radius:8px;">
              <tr>
                <th style="border-right:solid black 1px; border-bottom: solid black 1px">CANT.</th>
                <th style="border-right:solid black 1px; border-bottom: solid black 1px">DESCRIPCION</th>
                <th style="border-right:solid black 1px; border-bottom: solid black 1px">P.UNITARIO</th>
                <th style="border-bottom: solid black 1px">IMPORTE</th>
              </tr>
              @foreach($ventas as $venta)
                <tr>

                  <td style="border-right:solid black 1px; border-top: solid black 1px">{{$venta->cantidad}}</td>
                  <td style="border-right:solid black 1px; border-top: solid black 1px">{{$venta->producto}}</td>
                  <td style="border-right:solid black 1px; border-top: solid black 1px">{{$venta->precio}}</td>
                    <td style="border-top: solid black 1px">{{$venta->cantidad*$venta->precio}}</td>
                </tr>
              @endforeach
          </table>
        </div>
        <div >
          <table style=" width: 100%; margin-top: 15px">
            <tr>
              <th class="table-header-ruc" style="width:20%; border-radius: 10px;padding:10px;margin-top:15px;">
                <div style="font-size:11px">
                  {{$info->razon_social_s}} <br>
                  {{$info->tipo_documento_s}}:{{$info->num_documento_s}}
                </div>
              </th>
              <th  style="width:55%; ">
                <div  style="text-align:center">
                  CANCELADO
                </div>
                <div style="font-size:10px;text-align: center;width:100%;margin-left:130px;">
                  <div class="table-cliente-day" >Lima, {{$info->day}}</div>
                  <div class="table-cliente-month"> de {{$info->month}}</div>
                  <div class="table-cliente-year">del {{$info->year}}</div>
                </div>
                <div style="text-align:center;border-top: 1px black dashed; width: 200px;margin-left:80px; top:80px; position:absolute">
                    FIRMA
                </div>
              </th>
              <th class="table-header-total" style="width:25%;border-radius: 30px;padding:20px">
                Total S/. <div style="margin-top:3px;display:inline-block; text-align: center">{{$total->total}}</div>
              </th>
            </tr>

          </table>
        </div>

    </body>
</html>
