<?php
$medidaTicket = 340;

?>
<!DOCTYPE html>
<html>

<head>

    <style>
        * {
            font-size: 14px;
            font-family: 'DejaVu Sans', serif;
        }

        h1 {
            font-size: 14px;
        }
         h6 {
            font-size: 9px;
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
            margin: 0 auto;
        }

        td.precio {
            text-align: right;
            font-size: 14px;
        }

        td.cantidad {
            font-size: 14px;
        }

        td.producto {
            text-align: left;
        }

        th {
            text-align: center;
        }


        .centrado {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: <?php echo $medidaTicket ?>px;
            max-width: <?php echo $medidaTicket ?>px;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .ticket {
            margin: 0;
            padding: 0;
        }

        body {
           
            text-align: center;
            align-content: center;
        }
    </style>
</head>

<body>
    <div class="ticket centrado">
        <h1><?php echo $result["typeDoc"] ?></h1>
        <h1><?php echo $result["emisor"] ?></h1>
        <h6>Tel: <?php echo $result["telefono"] ?></h6>
        <h2>Consecutivo</h2>
        <h6><?php echo $result["consecutive"] ?></h6>
        <h2>Clave</h2>
        <h6><?php echo $result["key"] ?></h6>
        <h2><?php echo $result["date"] ?></h2>
        <?php
        # Recuerda que este arreglo puede venir de cualquier lugar; aquí lo defino manualmente para simplificar
        # Puedes obtenerlo de una base de datos, por ejemplo: https://parzibyte.me/blog/2019/07/17/php-bases-de-datos-ejemplos-tutoriales-conexion/

        $productos = $result["detail"];
        ?>

        <table>
            <thead>
                <tr class="centrado">
                    <th class="cantidad">CANT</th>
                    <th class="producto">PRODUCTO</th>
                    <th class="precio">PRECIO</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($productos->LineaDetalle as $producto) {
                    $total += $producto["cantidad"] * $producto["precio"];
                ?>
                    <tr>
                        <td class="cantidad"><?php echo $producto->Cantidad ?></td>
                        <td class="producto"><?php echo $producto->Detalle ?></td>
                        <td class="precio"><?php echo $producto->MontoTotal ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>SUBTOTAL</strong><br>
                    <strong>DESCUENTO</strong><br>
                    <strong>IMPUESTOS</strong><br>
                    <strong>TOTAL</strong>
                </td>
                <td class="precio">
                    <?php echo number_format($result["subTotal"], 2) ?><br>
                    <?php echo number_format($result["desc"], 2) ?><br>
                    <?php echo number_format($result["tax"], 2) ?><br>
                    <?php echo number_format($result["total"], 2) ?>
                </td>
                
            </tr>
        </table>
        <br>
        <p class="centrado">¡GRACIAS POR SU COMPRA!</p>
        <br>
        <br>
        <p class="centrado">SISTEMA USADO
        <br>Web: facturarapida.net
        <br>Teléfono: 8399-6444
        <br>Correo: info@facturarapida.net
        <br>
        <br>
        <br>"Autorizada mediante resolución N° DGT-R-033-2019 del 20-06-2019"</p>
    </div>
</body>

</html>