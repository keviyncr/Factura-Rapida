<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Factura Electronica</title>
        <style>
            @font-face {
                font-family: SourceSansPro;
            }

            .clearfix:after {
                content: "";
                display: table;
                clear: both;
            }
            #typeDoc{
                color: #660000;
                font-size: 1.5em;
                line-height: 1em;
                font-weight: normal;               
                text-align: center;
            }
            a {
                color: #0087C3;
                text-decoration: none;
            }

            body {
                position: relative;
                width: 100%;  
                height: 100%; 
                margin: 0 auto; 
                color: #555555;
                background: #FFFFFF; 
                font-family: Arial, sans-serif; 
                font-size: 14px; 
                font-family: SourceSansPro;
            }

            header {    
                margin-bottom: 20px;
                border-bottom: 1px solid #AAAAAA;
            }
            #logo {
                float: left;
                margin-top: 40px;
                margin-right: 10px;
            }

            #logo img {
                height: 80px;
                width: 80px;
            }

            #typeDoc img {
                height: 80px;
                width: 100px;
            }

            #company {  
                margin-top: 10px;
                padding-left: 6px;
                border-left: 6px solid #660000;
                float: left;
                text-align: left;
            }
            #logoC {
                width: 100%;
                position: relative;
                margin-top: 20px;
                float: right;
                text-align: right;
            }
            #logoC img{
                height: 50px;
            }

            #details { 
                margin-top: 10px; 
                margin-bottom: 20px; 
                width: 100%;
            }

            #client {
                padding-left: 6px;
                border-left: 6px solid #660000;
                float: left;
            }

            #client .to {
                color: #777777;
            }

            h2.name {
                font-size: 1.4em;
                font-weight: normal;
                margin: 0;
            }

            #invoice {
                width: 100%;
                position: relative;
                float: right;
                text-align: right;
            }
            #logoCompany {
                width: 100%;
                position: fixed;
                text-align: center;
            }

            #invoice h1 {
                color: #660000;
                font-size: 1.5em;
                line-height: 1em;
                font-weight: normal;
                margin: 0  0 0 0;
            }

            #invoice .date {
                font-size: 1.1em;
                color: #777777;
            }

            table {
                width: 100%;
                margin: 0 auto; 
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 20px;
            }

            table th,
            table td {
                padding: 5px 2px;
                background: #EEEEEE;
                text-align: center;
                border-bottom: 1px solid #FFFFFF;
            }

            table th {
                white-space: nowrap;        
                font-weight: normal;
                font-size: 0.6em;
            }

            table td {
                text-align: right;
            }

            table .no {
                color: #FFFFFF;
                font-size: 0.8em;
                background: #660000;
                text-align: center;
            }

            table .desc {
                text-align: center;
            }

            table .unit, 
            table .price,
            table .tax{
                text-align: center;
                background: #DDDDDD;
            }

            table .qty,
            table .discount,
            table .exo {
                text-align: center;
            }

            table .total {
                text-align: center;
                background: #660000;
                color: #FFFFFF;
            }

            table td.unit,
            table td.qty,
            table td.discount,
            table td.exo,
            table td.price,
            table td.tax,
            table td.total,
            table td.desc {
                font-size: 0.6em;
            }

            table tbody tr:last-child td {
                border: none;
            }

            table tfoot td {
                padding: 5px 10px;
                background: #FFFFFF;
                border-bottom: none;
                font-size: 1em;
                white-space: nowrap; 
                border-top: 1px solid #AAAAAA; 
            }

            table tfoot tr:first-child td {
                border-top: none; 
            }

            table tfoot tr:last-child td {
                color: #660000;
                font-size: 1em;
                border-top: 1px solid #660000; 

            }

            table tfoot tr td:first-child {
                border: none;
            }

            #thanks{
                color: #660000;
                font-size: 2em;
                margin-bottom: 50px;
            }

            #notices{
                padding-left: 6px;
                border-left: 6px solid #660000; 
                margin-bottom: 20px;
            }

            #notices .notice {
                font-size: 1.2em;
            }

            footer {
                color: #777777;
                width: 100%;
                height: 30px;
                bottom: 0;
                border-top: 1px solid #AAAAAA;
                text-align: center;
            }
        </style>
    </head>
    <body>
        
        <div id="typeDoc">
            @if($company->logo_url != "")
            <img src="{{ asset('laravel/storage/app/public/'.session('company')->logo_url) }}">
            <br>
            @endif
            Factura Electronica <br> N° {{ $p["consecutive"] }} <br> Clave: {{ $p["key"] }} 
        </div>
         <header class="clearfix">   
            <div id="logoC">
                 <img src="{{ asset('frontend/img/logoFR-2.png') }}">
            </div>
            <div id="company" >                
                <h4 class="name">{{ $company->name_company }}</h4>                
                <div>DIR: {{ $bo->nameProvince.', '.$bo->nameCanton.', '.$bo->nameDistrict }}</div>
                <div>{{ $bo->other_signs }}</div>
                <div>TEL: {{ $bo->phone }}</div>
                <div><a href="mailto:{{ $bo->emails }}">{{ $bo->emails }}</a></div>                
            </div>  
    </header>
    <main>
        <div id="details" class="clearfix">            
            <div id="client">
                <div class="to">CLIENTE:</div>
                <h4 class="name">{{ $client->name_client }}</h4>\
                <div class="email"><a href="mailto:{{ $client->emails }}">{{ $client->emails }}</a></div>
            </div>
            <div id="invoice">                
                <h1>{{ $doc["created_at"] }}</h1>  
                <div class="date">Medio de pago: Transferencia o Deposito</div>
                <div class="date">Condición venta: Contado</div>
                <div class="date">Plazo de credito: 1 días</div>
                <div class="date">Moneda: CRC</div>
                @if(isset($p["reference"]))
                <div class="date">Referencia: {{ $p["reference"] }}</div>
                @endif
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="no">N°</th>
                    <th class="desc">DESCRIPCIÓN</th>
                    <th class="unit">UNID</th>
                    <th class="qty">CANTIDAD</th>
                    <th class="price">PRECIO</th>
                    <th class="discount">DESCUENTO</th>
                    <th class="tax">IMPUESTO</th>
                    <th class="exo">EXONERADO</th>
                    <th class="total">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="no">1</td>
                    <td class="desc">{{ $p["descriptionProducts"] }}</td>
                    <td class="unit">Os</td>
                    <td class="qty">1</td>
                    <td class="price">{{ round($p["purchaseAmount"]/1.13,5) }}</td>
                    <td class="discount">0</td>
                    <td class="tax">{{ round($p["purchaseAmount"]-($p["purchaseAmount"]/1.13),5) }}</td>
                    <td class="exo">0</td>
                    <td class="total">{{ round($p["purchaseAmount"],5) }}</td>
                </tr>

            </tbody>
            <tfoot>
                <tr>   
                    <td colspan="6"></td>
                    <td colspan="2">SUBTOTAL</td>
                    <td>{{ round($p["purchaseAmount"]/1.13,5) }}</td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td colspan="2">DECUENTO TOTAL</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td colspan="2">IMPUESTO TOTAL</td>
                    <td>{{ round($p["purchaseAmount"]-($p["purchaseAmount"]/1.13),5) }}</td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td colspan="2">EXONERACIÓN TOTAL</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td colspan="2">TOTAL</td>
                    <td>{{ round($p["purchaseAmount"],5) }}</td>
                </tr>
            </tfoot>
        </table>
        <div id="thanks"></div>
        <div id="notices">
            <div>GRACIAS POR SU PREFERENCIA:</div>
            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i>Web: facturarapida.net</span><br>
            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> Teléfono: 8399-6444</span><br>
            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> Correo: info@facturarapida.net</span>
            <div class="notice"></div>
        </div>
    </main>
    <footer>
        "Autorizada mediante resolución N° DGT-R-033-2019 del 20-06-2019"
    </footer>
</body>
</html>