
        <table>
            <thead>
                <tr>
                    <th colspan="12" style="text-align: center; font-weight:bold">Detalle de Ventas</th>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Emisor</th>
                    <th>Item</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto</th>  
                    <th>Total</th>      
                    <th>Moneda</th>      
                    <th>Tipo Impuesto</th>
                    <th>Act. Econom.</th>
                    <th>Estado MH</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($detail))
                    @foreach($detail as $index=>$line)
                        <tr >
                            <td>{{ $line["fecha"] }}</td>
                            <td>{{ $line["clave"] }}</td>
                            <td>{{ $line["receptor"] }}</td>
                            <td>{{ $line["item"] }}</td>
                            <td>{{ $line["monto"] }}</td>
                            <td>{{ $line["discount"] }}</td>
                            <td>{{ $line["tax"] }}</td>
                            <td>{{ $line["total"] }}</td>
                            <td>{{ $line["tmoneda"] }}</td>
                            <td>{{ $line["typeTax"]."%" }}</td>
                            <td>{{ $line["ae"] }}</td>
                            <td>{{ $line["state"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($detail))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMonto }}</td>
                      <td>{{ $sumaDescuento }}</td>
                      <td>{{ $sumaImpuesto }}</td>
                      <td>{{ $sumaTotal }}</td>
                      <td></td>
                      <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>

