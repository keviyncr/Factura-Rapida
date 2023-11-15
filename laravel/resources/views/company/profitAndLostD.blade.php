
        <table>
            <thead>
                <tr>
                    <th colspan="11" style="text-align: center; font-weight:bold">INGRESOS</th>
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
<br>
        <table >
            <thead>
                 <tr>
                    <th colspan="13" style="text-align: center; font-weight:bold">GASTOS</th>
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
                            <td>{{ $lineG["typeTaxG"]."%" }}</td>
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
<br>
        <table>
            <thead>
                <tr>
                    <th>Descripcion</th>
                    <th>Subtotal</th>
                    <th>Descuentos</th>
                    <th>Impuestos</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($detailG))
                        <tr >
                            <td>Ingresos</td>
                            <td>{{ $sumaMonto }}</td>
                            <td>{{ $sumaDescuento }}</td>
                            <td>{{ $sumaImpuesto }}</td>
                            <td>{{ $sumaTotal }}</td>
                        </tr>
                        <tr >
                            <td>Gastos</td>
                            <td>{{ $sumaMontoG*-1 }}</td>
                            <td>{{ $sumaDescuentoG*-1 }}</td>
                            <td>{{ $sumaImpuestoG*-1 }}</td>
                            <td>{{ $sumaTotalG*-1 }}</td>
                        </tr>
                        @endif
                
            </tbody>
             <tfoot>
                 @if(isset($detailG))
                    <tr>
                      <td>Totales</td>
                      <td>{{ $sumaMonto - $sumaMontoG }}</td>
                      <td>{{ $sumaDescuento - $sumaDescuentoG }}</td>
                      <td>{{ $sumaImpuesto - $sumaImpuestoG }}</td>
                      <td>{{ $sumaTotal - $sumaTotalG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>
<!-- end panel -->
