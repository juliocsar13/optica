<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orden de compra</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }

        #logo{
        float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }

        #imagen{
        width: 100px;
        }

        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        text-align: justify;
        }

        #encabezado{
        text-align: center;
        margin-left: 10%;
        margin-right: 35%;
        font-size: 15px;
        }

        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }

        section{
        clear: left;
        }

        #cliente{
        text-align: left;
        }

        #facliente{
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #facliente thead{
        padding: 20px;
        background: #2183E3;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        text-align: center;
        }

        #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        text-align: center;
        }

        #facarticulo thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #gracias{
        text-align: center; 
        }
    </style>
    <body>
        @foreach ($venta as $v)
        <header>
            <div id="logo">
                <img src="img/logo2.png" alt="#" id="imagen">
            </div>
            <div id="datos">
                <p id="encabezado">
                    <b>Optica Fashion Lens La Molina</b><br>Av. Constructores 424 Urb. Las Acacias - La Molina<br>Telefono: 4439090<br>Email: opticafashionlenslamolina@gmail.com
                </p>
            </div>
            <div id="fact">
                <p>ORDEN Nro:<br>
                {{$v->serie_comprobante}}-{{$v->num_comprobante}}</p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            <th id="fac">Proveedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><p id="cliente">Nombre: {{$v->nombre}}<br>
                            RUC: {{$v->num_documento}}<br>
                            Dirección: {{$v->direccion}}<br>
                            Teléfono: {{$v->telefono}}<br>
                            Email: {{$v->email}}</p></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        @endforeach
        <br>
        <section>
            <div>
                <table id="facvendedor">
                    <thead>
                        <tr id="fv">
                            <th>VENDEDOR</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$v->usuario}}</td>
                            <td>{{$v->created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                            <th></th>
                            <th>Esfera</th>
                            <th>Cilindro</th>
                            <th>Eje</th>
                            <th>Add.</th>
                            <th>Dip.</th>
                            <th>Av.</th>
                            <th>Prisma</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($venta as $v)
                        <tr>
                            <td>OD:</td>
                            <td>{{$v->esfera}}</td>
                            <td>{{$v->cilindro}}</td>
                            <td>{{$v->eje}}</td>
                            <td>{{$v->add}}</td>
                            <td>{{$v->dip}}</td>
                            <td>{{$v->av}}</td>
                            <td>{{$v->prisma}}</td>
                        </tr>
                        <tr>
                            <td>OI:</td>
                            <td>{{$v->esfera2}}</td>
                            <td>{{$v->cilindro2}}</td>
                            <td>{{$v->eje2}}</td>
                            <td>{{$v->add}}</td>
                            <td>{{$v->dip}}</td>
                            <td>{{$v->av2}}</td>
                            <td>{{$v->prisma2}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <!-- <tfoot>
                        @foreach ($venta as $v)
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>SUBTOTAL</th>
                            <td>S/. {{round($v->total-($v->total*$v->impuesto),2)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Impuesto</th>
                            <td>S/. {{round($v->total*$v->impuesto,2)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <td>S/. {{$v->total}}</td>
                        </tr>
                        @endforeach
                    </tfoot> -->
                </table>
            </div>
        </section>
        <br>
        <br>
        <!-- <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer> -->
    </body>
</html>