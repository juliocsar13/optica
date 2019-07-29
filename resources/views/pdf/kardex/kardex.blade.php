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
                  <label style="position:absolute;margin-left:5px;font-size:13px">{{$sucursal->direccion_s}}</label>
                </div>
              </td>
              <td style="height:60px;width:30%;border-bottom: 1px solid white;border-top: 1px solid #bcbbbe;">
                <div style="border-left:1px solid #bcbbbe;line-height: normal">
                  <label style="position:absolute;margin-left:5px; font-weight:bold">Telefono:</label> <br>
                  <label style="position:absolute;font-size:13px">{{$sucursal->telefono_s}}</label>
                </div>
              </td>
              <td style="height:60px;width:30%;border-bottom: 1px solid white;border-top: 1px solid #bcbbbe;">
                <div style="border-left:1px solid #bcbbbe;line-height: normal">
                  <label style="position:absolute;margin-left:5px;font-weight:bold">Email:</label> <br>
                  <label style="position:absolute;margin-left:5px;font-size:13px">{{$sucursal->email_s}}</label>
                </div>
              </td>
            </tr>
          </table>
      </div>
  </footer>
  <main>
  <div >
    <div class="row">
      <table>
        <tr>
          <td><img src="img/logo.png" style="width:200px;margin-left:50px" alt=""/></td>
          <td style="text-align:center">
            <div style="text-align:center;padding:28px;width:230px;background-color:#212121;color:white;position:absolute;top:-65px;left:650px;">
              <h1 style="color:white; font-size:50px;margin-top:50px">KARDEX</h1>
              <table style="line-height:normal">
                <tr>
                  <td style="text-align:left">Fecha inicio</td>
                  <td style="text-align:right">{{Request::get('dateStart')}}</td>
                </tr>
                <tr>
                  <td style="text-align:left">Fecha final</td>
                  <td style="text-align:right">{{Request::get('dateEnd')}}</td>
                </tr>
                <tr>
                  <td style="text-align:left">Productos</td>
                  <td style="text-align:right">{{$producto}}</td>
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
    <div class="row" style="padding: 10px;margin-left:50px">
      <div class="col-md-12">
        <table style="width: 100%;border-bottom: 0px solid #ddd;" >
          <tbody>
            <tr>
              <td style="width:60%">
                <strong class="">
                  <h5>REPORTE DE </h5>
                  <h4 style="font-weight: bold">KARDEX</h4>
                </strong>
              </td>
            </tr>
            <tr>
              <td class="invoice-title" ><div class="info-user"> {{$sucursal->razon_social_s}}</div></td>
            </tr>
            <tr>
              <td class="invoice-title">{{$sucursal->direccion_s}}</td>
            </tr>
            <tr>
              <td class="invoice-title">{{$sucursal->num_documento_s}}</td>
            </tr>
            <tr>
              <td class="invoice-title">{{$cliente->telefono_s}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  	<div class="row" style="margin-top:-40px;padding:25px">
  		<div class="col-md-12" >
  			<table class=" table table-bordered table-striped table-sm table-responsive ">
            <tr>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px" colspan="3" scope="colgroup">COMPROBANTE DE PAGO,<br>DOCUMENTO INTERNO O SIMILAR</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px" colspan="3" scope="colgroup">ENTRADAS</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px" colspan="3" scope="colgroup">SALIDAS</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px" colspan="3" scope="colgroup">SALDO FINAL</th>

            </tr>
            <tr>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px;width:180px;padding:10px">FECHA</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">SERIE</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">NUMERO</th>

              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">CANT.</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">PRECIO</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">TOTAL</th>

              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">CANT.</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">PRECIO</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">TOTAL</th>

              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">CANT.</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">PRECIO</th>
              <th style="text-align:center;background-color:#f2f2f2;font-size: 15.5px">TOTAL</th>
            </tr>
          @foreach($productos as $producto)
            <tr>
    					<td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->fecha}} </td>
              <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->serie_comprobante }}</td>
              <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->num_comprobante}}</td>

    					<td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->i_cantidad}}</td>
    					<td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->i_precio}}</td>
              <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->i_total}}</td>

              <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->v_cantidad}}</td>
              <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->v_precio}}</td>
              <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->v_total}}</td>

              <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->t_cantidad}}</td>
              <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->t_precio}}</td>
              <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$producto->t_total}}</td>
          	</tr>
          @endforeach
          <tr>
            <td class="content-td" style="border-bottom: 1px solid #ddd;"> </td>
            <td class="content-td" style="border-bottom: 1px solid #ddd;"></td>
            <td class="content-td" style="border-bottom: 1px solid #ddd;">TOTAL</td>

            <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$total_ingreso_cantidad}}</td>
            <td class="content-td" style="border-bottom: 1px solid #ddd;"></td>
            <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$total_ingreso}}</td>

            <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$total_salida_cantidad}}</td>
            <td class="content-td" style="border-bottom: 1px solid #ddd;"></td>
            <td class="content-td" style="border-bottom: 1px solid #ddd;">{{$total_salida}}</td>

            <td class="content-td" style="border-bottom: 1px solid #ddd;"></td>
            <td class="content-td" style="border-bottom: 1px solid #ddd;"></td>
            <td class="content-td" style="border-bottom: 1px solid #ddd;"></td>
          </tr>
  			</table>

  		</div>
  	</div>
  </div>
  </main>

</body>
</html>
