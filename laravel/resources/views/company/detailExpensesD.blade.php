<table >
            <thead>
                <tr>
                    <th colspan="12" style="text-align: center; font-weight:bold">Detalle de Gastos</th>
                </tr>
                <tr>
                    <th >Fecha</th>
                    <th >Numero Factura</th>
                    <th >Emisor</th>
                    <th >Item</th>
                    <th >Monto</th>
                    <th >Descuento</th>
                    <th >Impuesto</th>  
                    <th >Total</th>   
                    <th >Moneda</th>
                    <th >Tipo Impuesto</th>
                    <th >Categoria</th>
                    <th >Act. Econom.</th>
                    <td>Condicion</td>
                    <th >Estado MH</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($detailG))
                    @foreach($detailG as $index2=>$lineG)
                        <tr >
                            <td>{{ $lineG["fechaG"] }}</td>
                            <td>{{ $lineG["claveG"] }}</td>
                            <td>{{ $lineG["emisor"] }}</td>
                            <td>{{ $lineG["itemG"] }}</td>
                            <td>{{ $lineG["montoG"] }}</td>
                            <td>{{ $lineG["discountG"] }}</td>
                            <td>{{ $lineG["taxG"] }}</td>
                            <td>{{ $lineG["totalG"] }}</td>
                            <td>{{ $lineG["tmoneda"] }}</td>
                            <td>{{ (isset($lineG["typeTaxG"])?$lineG["typeTaxG"]:0)."%" }}</td>
                            <td>{{ $lineG["category"] }}</td>
                            <td>{{ $lineG["aeG"] }}</td>
                            <td>{{ $lineG["condition"] }}</td>
                            <td>{{ $lineG["state"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($detailG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoG }}</td>
                      <td>{{ $sumaDescuentoG }}</td>
                      <td>{{ $sumaImpuestoG }}</td>
                      <td>{{ $sumaTotalG }}</td>
                      <td></td>
                      <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>
        