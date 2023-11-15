
@if(isset($bienes) && count($bienes) > 0)
        <table >
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Ingresos por venta de bienes</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Receptor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                    <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($bienes))
                    @foreach($bienes as $index=>$line)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $line["fecha"] }}</td>
                            <td>{{ $line["clave"] }}</td>
                            <td>{{ $line["receptor"] }}</td>
                            <td>{{ $line["monto"] }}</td>
                            <td>{{ $line["discount"] }}</td>
                            <td>{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td>{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td>{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td>{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td>{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td>{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                            <td>{{ $line["exo"] }}</td>
                            <td>{{ $line["total"] }}</td>
                            <td>{{ $line["tmoneda"] }}</td><td>{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($bienes))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoB }}</td>
                      <td>{{ $sumaDescuentoB }}</td>
                      <td>{{ $sumaImpuestoB0 }}</td>
                      <td>{{ $sumaImpuestoB1 }}</td>
                      <td>{{ $sumaImpuestoB2 }}</td>
                      <td>{{ $sumaImpuestoB4 }}</td>
                      <td>{{ $sumaImpuestoB8 }}</td>
                      <td>{{ $sumaImpuestoB13 }}</td>
                      <td>{{ $sumaExoB }}</td>
                      <td>{{ $sumaTotalB }}</td>
                       <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>
<br>
@endif

@if(isset($bienesCapital) && count($bienesCapital) > 0)
        <table >
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Ingresos por bienes de capital</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Receptor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($bienesCapital))
                    @foreach($bienesCapital as $index=>$line)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $line["fecha"] }}</td>
                            <td>{{ $line["clave"] }}</td>
                            <td>{{ $line["receptor"] }}</td>
                            <td>{{ $line["monto"] }}</td>
                            <td>{{ $line["discount"] }}</td>
                            <td>{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td>{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td>{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td>{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td>{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td>{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                            <td>{{ $line["exo"] }}</td>
                            <td>{{ $line["total"] }}</td>
                            <td>{{ $line["tmoneda"] }}</td><td>{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($bienesCapital))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoBC }}</td>
                      <td>{{ $sumaDescuentoBC }}</td>
                      <td>{{ $sumaImpuestoBC0 }}</td>
                      <td>{{ $sumaImpuestoBC1 }}</td>
                      <td>{{ $sumaImpuestoBC2 }}</td>
                      <td>{{ $sumaImpuestoBC4 }}</td>
                      <td>{{ $sumaImpuestoBC8 }}</td>
                      <td>{{ $sumaImpuestoBC13 }}</td>
                      <td>{{ $sumaExoBC }}</td>
                      <td>{{ $sumaTotalBC }}</td>
                       <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>
<br>
@endif

@if(isset($servicios) && count($servicios) > 0)
        <table >
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Igresos por venta de servicios</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Receptor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($servicios))
                    @foreach($servicios as $index=>$line)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $line["fecha"] }}</td>
                            <td>{{ $line["clave"] }}</td>
                            <td>{{ $line["receptor"] }}</td>
                            <td>{{ $line["monto"] }}</td>
                            <td>{{ $line["discount"] }}</td>
                            <td>{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td>{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td>{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td>{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td>{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td>{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                            <td>{{ $line["exo"] }}</td>
                            <td>{{ $line["total"] }}</td>
                            <td>{{ $line["tmoneda"] }}</td><td>{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($servicios))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoS }}</td>
                      <td>{{ $sumaDescuentoS }}</td>
                      <td>{{ $sumaImpuestoS0 }}</td>
                      <td>{{ $sumaImpuestoS1 }}</td>
                      <td>{{ $sumaImpuestoS2 }}</td>
                      <td>{{ $sumaImpuestoS4 }}</td>
                      <td>{{ $sumaImpuestoS8 }}</td>
                      <td>{{ $sumaImpuestoS13 }}</td>
                      <td>{{ $sumaExoS }}</td>
                      <td>{{ $sumaTotalS }}</td>
                       <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>
<br>
@endif

@if(isset($exento) && count($exento) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Igresos por ventas exentas</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Receptor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($exento))
                    @foreach($exento as $index=>$line)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $line["fecha"] }}</td>
                            <td>{{ $line["clave"] }}</td>
                            <td>{{ $line["receptor"] }}</td>
                            <td>{{ $line["monto"] }}</td>
                            <td>{{ $line["discount"] }}</td>
                            <td>{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td>{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td>{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td>{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td>{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td>{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                            <td>{{ $line["exo"] }}</td>
                            <td>{{ $line["total"] }}</td>
                            <td>{{ $line["tmoneda"] }}</td><td>{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($exento))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoE }}</td>
                      <td>{{ $sumaDescuentoE }}</td>
                      <td>{{ $sumaImpuestoE0 }}</td>
                      <td>{{ $sumaImpuestoE1 }}</td>
                      <td>{{ $sumaImpuestoE2 }}</td>
                      <td>{{ $sumaImpuestoE4 }}</td>
                      <td>{{ $sumaImpuestoE8 }}</td>
                      <td>{{ $sumaImpuestoE13 }}</td>
                      <td>{{ $sumaExoE }}</td>
                      <td>{{ $sumaTotalE }}</td>
                       <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>
@endif

@if(isset($noSujeto) && count($noSujeto) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Igresos por ventas no sujetas</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Receptor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($noSujeto))
                    @foreach($noSujeto as $index=>$line)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $line["fecha"] }}</td>
                            <td>{{ $line["clave"] }}</td>
                            <td>{{ $line["receptor"] }}</td>
                            <td>{{ $line["monto"] }}</td>
                            <td>{{ $line["discount"] }}</td>
                            <td>{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td>{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td>{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td>{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td>{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td>{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                            <td>{{ $line["exo"] }}</td>
                            <td>{{ $line["total"] }}</td>
                            <td>{{ $line["tmoneda"] }}</td><td>{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($noSujeto))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoNS }}</td>
                      <td>{{ $sumaDescuentoNS }}</td>
                      <td>{{ $sumaImpuestoNS0 }}</td>
                      <td>{{ $sumaImpuestoNS1 }}</td>
                      <td>{{ $sumaImpuestoNS2 }}</td>
                      <td>{{ $sumaImpuestoNS4 }}</td>
                      <td>{{ $sumaImpuestoNS8 }}</td>
                      <td>{{ $sumaImpuestoNS13 }}</td>
                      <td>{{ $sumaExoNS }}</td>
                      <td>{{ $sumaTotalNS }}</td>
                       <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>
<br>
@endif
@if(isset($fueraAE) && count($fueraAE) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Igresos por ventas fuera de la actividad economica</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Receptor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($fueraAE))
                    @foreach($fueraAE as $index=>$line)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $line["fecha"] }}</td>
                            <td>{{ $line["clave"] }}</td>
                            <td>{{ $line["receptor"] }}</td>
                            <td>{{ $line["monto"] }}</td>
                            <td>{{ $line["discount"] }}</td>
                            <td>{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td>{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td>{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td>{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td>{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td>{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                            <td>{{ $line["exo"] }}</td>
                            <td>{{ $line["total"] }}</td>
                            <td>{{ $line["tmoneda"] }}</td><td>{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($fueraAE))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoFAE }}</td>
                      <td>{{ $sumaDescuentoFAE }}</td>
                      <td>{{ $sumaImpuestoFAE0 }}</td>
                      <td>{{ $sumaImpuestoFAE1 }}</td>
                      <td>{{ $sumaImpuestoFAE2 }}</td>
                      <td>{{ $sumaImpuestoFAE4 }}</td>
                      <td>{{ $sumaImpuestoFAE8 }}</td>
                      <td>{{ $sumaImpuestoFAE13 }}</td>
                      <td>{{ $sumaExoFAE }}</td>
                      <td>{{ $sumaTotalFAE }}</td>
                       <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>
<br>
@endif
@if(isset($sinClasificar) && count($sinClasificar) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Igresos por ventas no clasifiacdas</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Receptor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($sinClasificar))
                    @foreach($sinClasificar as $index=>$line)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $line["fecha"] }}</td>
                            <td>{{ $line["clave"] }}</td>
                            <td>{{ $line["receptor"] }}</td>
                            <td>{{ $line["monto"] }}</td>
                            <td>{{ $line["discount"] }}</td>
                            <td>{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td>{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td>{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td>{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td>{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td>{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                            <td>{{ $line["exo"] }}</td>
                            <td>{{ $line["total"] }}</td>
                            <td>{{ $line["tmoneda"] }}</td><td>{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($sinClasificar))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoSC }}</td>
                      <td>{{ $sumaDescuentoSC }}</td>
                      <td>{{ $sumaImpuestoSC0 }}</td>
                      <td>{{ $sumaImpuestoSC1 }}</td>
                      <td>{{ $sumaImpuestoSC2 }}</td>
                      <td>{{ $sumaImpuestoSC4 }}</td>
                      <td>{{ $sumaImpuestoSC8 }}</td>
                      <td>{{ $sumaImpuestoSC13 }}</td>
                      <td>{{ $sumaExoSC }}</td>
                      <td>{{ $sumaTotalSC }}</td>
                       <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>
@endif
@if(isset($bienesG) && count($bienesG) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Gastos por bienes</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Emisor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($bienesG))
                    @foreach($bienesG as $index2=>$lineG)
                        <tr>
                            <td>{{ $index2+1 }}</td>
                            <td>{{ $lineG["fechaG"] }}</td>
                            <td>{{ $lineG["claveG"] }}</td>
                            <td>{{ $lineG["emisor"] }}</td>
                            <td>{{ $lineG["montoG"] }}</td>
                            <td>{{ $lineG["discountG"] }}</td>
                            <td>{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td>{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td>{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td>{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td>{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td>{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td>{{ $lineG["exoG"] }}</td>
                            <td>{{ $lineG["totalG"] }}</td>
                            <td>{{ $lineG["tmoneda"] }}</td><td>{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($bienesG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoBG }}</td>
                      <td>{{ $sumaDescuentoBG }}</td>
                      <td>{{ $sumaImpuestoBG0 }}</td>
                      <td>{{ $sumaImpuestoBG1 }}</td>
                      <td>{{ $sumaImpuestoBG2 }}</td>
                      <td>{{ $sumaImpuestoBG4 }}</td>
                      <td>{{ $sumaImpuestoBG8 }}</td>
                      <td>{{ $sumaImpuestoBG13 }}</td>
                      <td>{{ $sumaExoBG }}</td>
                      <td>{{ $sumaTotalBG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>
<br>
@endif
@if(isset($bienesCapitalG) && count($bienesCapitalG) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Gastos por bienes de capital</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Emisor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($bienesCapitalG))
                    @foreach($bienesCapitalG as $index2=>$lineG)
                        <tr>
                            <td>{{ $index2+1 }}</td>
                            <td>{{ $lineG["fechaG"] }}</td>
                            <td>{{ $lineG["claveG"] }}</td>
                            <td>{{ $lineG["emisor"] }}</td>
                            <td>{{ $lineG["montoG"] }}</td>
                            <td>{{ $lineG["discountG"] }}</td>
                            <td>{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td>{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td>{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td>{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td>{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td>{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td>{{ $lineG["exoG"] }}</td>
                            <td>{{ $lineG["totalG"] }}</td>
                            <td>{{ $lineG["tmoneda"] }}</td><td>{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($bienesCapitalG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoBCG }}</td>
                      <td>{{ $sumaDescuentoBCG }}</td>
                      <td>{{ $sumaImpuestoBCG0 }}</td>
                      <td>{{ $sumaImpuestoBCG1 }}</td>
                      <td>{{ $sumaImpuestoBCG2 }}</td>
                      <td>{{ $sumaImpuestoBCG4 }}</td>
                      <td>{{ $sumaImpuestoBCG8 }}</td>
                      <td>{{ $sumaImpuestoBCG13 }}</td>
                      <td>{{ $sumaExoBCG }}</td>
                      <td>{{ $sumaTotalBCG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>
@endif
@if(isset($serviciosG) && count($serviciosG) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Gastos por servicios</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Emisor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($serviciosG))
                    @foreach($serviciosG as $index2=>$lineG)
                        <tr>
                            <td>{{ $index2+1 }}</td>
                            <td>{{ $lineG["fechaG"] }}</td>
                            <td>{{ $lineG["claveG"] }}</td>
                            <td>{{ $lineG["emisor"] }}</td>
                            <td>{{ $lineG["montoG"] }}</td>
                            <td>{{ $lineG["discountG"] }}</td>
                            <td>{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td>{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td>{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td>{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td>{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td>{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td>{{ $lineG["exoG"] }}</td>
                            <td>{{ $lineG["totalG"] }}</td>
                            <td>{{ $lineG["tmoneda"] }}</td><td>{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($serviciosG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoSG }}</td>
                      <td>{{ $sumaDescuentoSG }}</td>
                      <td>{{ $sumaImpuestoSG0 }}</td>
                      <td>{{ $sumaImpuestoSG1 }}</td>
                      <td>{{ $sumaImpuestoSG2 }}</td>
                      <td>{{ $sumaImpuestoSG4 }}</td>
                      <td>{{ $sumaImpuestoSG8 }}</td>
                      <td>{{ $sumaImpuestoSG13 }}</td>
                      <td>{{ $sumaExoSG }}</td>
                      <td>{{ $sumaTotalSG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>
@endif
@if(isset($exentoG) && count($exentoG) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Gastos exentos</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Emisor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($exentoG))
                    @foreach($exentoG as $index2=>$lineG)
                        <tr>
                            <td>{{ $index2+1 }}</td>
                            <td>{{ $lineG["fechaG"] }}</td>
                            <td>{{ $lineG["claveG"] }}</td>
                            <td>{{ $lineG["emisor"] }}</td>
                            <td>{{ $lineG["montoG"] }}</td>
                            <td>{{ $lineG["discountG"] }}</td>
                            <td>{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td>{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td>{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td>{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td>{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td>{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td>{{ $lineG["exoG"] }}</td>
                            <td>{{ $lineG["totalG"] }}</td>
                            <td>{{ $lineG["tmoneda"] }}</td><td>{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($exentoG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoEG }}</td>
                      <td>{{ $sumaDescuentoEG }}</td>
                      <td>{{ $sumaImpuestoEG0 }}</td>
                      <td>{{ $sumaImpuestoEG1 }}</td>
                      <td>{{ $sumaImpuestoEG2 }}</td>
                      <td>{{ $sumaImpuestoEG4 }}</td>
                      <td>{{ $sumaImpuestoEG8 }}</td>
                      <td>{{ $sumaImpuestoEG13 }}</td>
                      <td>{{ $sumaExoEG }}</td>
                      <td>{{ $sumaTotalEG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>
@endif
@if(isset($noSujetoG) && count($noSujetoG) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Gastos no sujetos</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Emisor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($noSujetoG))
                    @foreach($noSujetoG as $index2=>$lineG)
                        <tr>
                            <td>{{ $index2+1 }}</td>
                            <td>{{ $lineG["fechaG"] }}</td>
                            <td>{{ $lineG["claveG"] }}</td>
                            <td>{{ $lineG["emisor"] }}</td>
                            <td>{{ $lineG["montoG"] }}</td>
                            <td>{{ $lineG["discountG"] }}</td>
                            <td>{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td>{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td>{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td>{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td>{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td>{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td>{{ $lineG["exoG"] }}</td>
                            <td>{{ $lineG["totalG"] }}</td>
                            <td>{{ $lineG["tmoneda"] }}</td><td>{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($noSujetoG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoNSG }}</td>
                      <td>{{ $sumaDescuentoNSG }}</td>
                      <td>{{ $sumaImpuestoNSG0 }}</td>
                      <td>{{ $sumaImpuestoNSG1 }}</td>
                      <td>{{ $sumaImpuestoNSG2 }}</td>
                      <td>{{ $sumaImpuestoNSG4 }}</td>
                      <td>{{ $sumaImpuestoNSG8 }}</td>
                      <td>{{ $sumaImpuestoNSG13 }}</td>
                      <td>{{ $sumaExoNSG }}</td>
                      <td>{{ $sumaTotalNSG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>
@endif
@if(isset($fueraAEG) && count($fueraAEG) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Gastos fuera de la actividad economica</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Emisor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($fueraAEG))
                    @foreach($fueraAEG as $index2=>$lineG)
                        <tr>
                            <td>{{ $index2+1 }}</td>
                            <td>{{ $lineG["fechaG"] }}</td>
                            <td>{{ $lineG["claveG"] }}</td>
                            <td>{{ $lineG["emisor"] }}</td>
                            <td>{{ $lineG["montoG"] }}</td>
                            <td>{{ $lineG["discountG"] }}</td>
                            <td>{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td>{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td>{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td>{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td>{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td>{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td>{{ $lineG["exoG"] }}</td>
                            <td>{{ $lineG["totalG"] }}</td>
                            <td>{{ $lineG["tmoneda"] }}</td><td>{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($fueraAEG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoFAEG }}</td>
                      <td>{{ $sumaDescuentoFAEG }}</td>
                      <td>{{ $sumaImpuestoFAEG0 }}</td>
                      <td>{{ $sumaImpuestoFAEG1 }}</td>
                      <td>{{ $sumaImpuestoFAEG2 }}</td>
                      <td>{{ $sumaImpuestoFAEG4 }}</td>
                      <td>{{ $sumaImpuestoFAEG8 }}</td>
                      <td>{{ $sumaImpuestoFAEG13 }}</td>
                      <td>{{ $sumaExoFAEG }}</td>
                      <td>{{ $sumaTotalFAEG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>
@endif
@if(isset($sinClasificarG) && count($sinClasificarG) > 0)

        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Gastos sin clasificar</th>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Numero Factura</th>
                    <th>Emisor</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Impuesto 0%</th>
                    <th>Impuesto 1%</th>
                    <th>Impuesto 2%</th>
                    <th>Impuesto 4%</th>
                    <th>Impuesto 8%</th>
                    <th>Impuesto 13%</th>
                     <th>Exoneracion</th>
                    <th>Total</th> 
                    <th>Moneda</th><th>Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($sinClasificarG))
                    @foreach($sinClasificarG as $index2=>$lineG)
                        <tr>
                            <td>{{ $index2+1 }}</td>
                            <td>{{ $lineG["fechaG"] }}</td>
                            <td>{{ $lineG["claveG"] }}</td>
                            <td>{{ $lineG["emisor"] }}</td>
                            <td>{{ $lineG["montoG"] }}</td>
                            <td>{{ $lineG["discountG"] }}</td>
                            <td>{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td>{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td>{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td>{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td>{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td>{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td>{{ $lineG["exoG"] }}</td>
                            <td>{{ $lineG["totalG"] }}</td>
                            <td>{{ $lineG["tmoneda"] }}</td><td>{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($sinClasificarG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoSCG }}</td>
                      <td>{{ $sumaDescuentoSCG }}</td>
                      <td>{{ $sumaImpuestoSCG0 }}</td>
                      <td>{{ $sumaImpuestoSCG1 }}</td>
                      <td>{{ $sumaImpuestoSCG2 }}</td>
                      <td>{{ $sumaImpuestoSCG4 }}</td>
                      <td>{{ $sumaImpuestoSCG8 }}</td>
                      <td>{{ $sumaImpuestoSCG13 }}</td>
                      <td>{{ $sumaExoSCG }}</td>
                      <td>{{ $sumaTotalSCG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>
        @endif
        

        <table>
            <thead>
                <tr>
                    <th colspan="14" style="text-align: center; font-weight:bold">Resumen por categoria</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th >Clasificación</th>
                    <th  >Monto</th>
                    <th  >Impuesto 0%</th>
                    <th  >Impuesto 1%</th>
                    <th  >Impuesto 2%</th>
                    <th  >Impuesto 4%</th>
                    <th  >Impuesto 8%</th>
                    <th  >Impuesto 13%</th>
                    <th  >Total</th> 
                </tr>
            </thead>
            <tbody>
                    <tr >
                        <td ></td>
                        <td ></td>
                        <td ></td>
                    <td  >Gastos de bienes</td>
                    <td >{{ $result=(isset($sumaMontoBG))?$sumaMontoBG:0 }}</td>
                    <td  >{{ $result=(isset($sumaMontoBG0))?$sumaMontoBG0:0 }}</td>
                    <td  >{{ $result=(isset($sumaMontoBG1))?$sumaMontoBG1:0 }}</td>
                    <td  >{{ $result=(isset($sumaMontoBG2))?$sumaMontoBG2:0 }}</td>
                    <td  >{{ $result=(isset($sumaMontoBG4))?$sumaMontoBG4:0 }}</td>
                    <td  >{{ $result=(isset($sumaMontoBG8))?$sumaMontoBG8:0 }}</td>
                    <td  >{{ $result=(isset($sumaMontoBG13))?$sumaMontoBG13:0 }}</td>
                    <td  >{{ $result=(isset($sumaTotalBG))?$sumaTotalBG:0 }}</td>
                    </tr>
                    <tr >
                        <td ></td>
                        <td ></td>
                        <td ></td>
                    <td  >Impuestos por bienes</td>
                    
                    <td ></td>
                    <td  >{{ $result=(isset($sumaImpuestoBG0))?$sumaImpuestoBG0:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBG1))?$sumaImpuestoBG1:1 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBG2))?$sumaImpuestoBG2:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBG4))?$sumaImpuestoBG4:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBG8))?$sumaImpuestoBG8:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBG13))?$sumaImpuestoBG13:0 }}</td>
                    <td  ></td>
                    </tr>
                        <tr >
                            <td ></td>
                        <td ></td>
                        <td ></td>
                            <td  >Gastos de Bienes de capital</td>
                            <td >{{ $result=(isset($sumaMontoBCG))?$sumaMontoBCG:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoBCG0))?$sumaMontoBCG0:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoBCG1))?$sumaMontoBCG1:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoBCG2))?$sumaMontoBCG2:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoBCG4))?$sumaMontoBCG4:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoBCG8))?$sumaMontoBCG8:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoBCG13))?$sumaMontoBCG13:0 }}</td>
                      <td  >{{ $result=(isset($sumaTotalBCG))?$sumaTotalBCG:0 }}</td>
                        </tr>
                        <tr >
                            <td ></td>
                        <td ></td>
                        <td ></td>
                    <td  >Impuestos por bienes de capital</td>
                    
                    <td ></td>
                    <td  >{{ $result=(isset($sumaImpuestoBCG0))?$sumaImpuestoBCG0:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBCG1))?$sumaImpuestoBCG1:1 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBCG2))?$sumaImpuestoBCG2:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBCG4))?$sumaImpuestoBCG4:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBCG8))?$sumaImpuestoBCG8:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoBCG13))?$sumaImpuestoBCG13:0 }}</td>
                    <td  ></td>
                    </tr>
                        <tr >
                            <td ></td>
                        <td ></td>
                        <td ></td>
                            <td  >Gastos de Servicios</td>
                            <td >{{ $result=(isset($sumaMontoSG))?$sumaMontoSG:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoSG0))?$sumaMontoSG0:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoSG1))?$sumaMontoSG1:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoSG2))?$sumaMontoSG2:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoSG4))?$sumaMontoSG4:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoSG8))?$sumaMontoSG8:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoSG13))?$sumaMontoSG13:0 }}</td>
                      <td  >{{ $result=(isset($sumaTotalSG))?$sumaTotalSG:0 }}</td>
                        </tr>
                          <tr >
                              <td ></td>
                        <td ></td>
                        <td ></td>
                    <td  >Impuestos por servicios</td>
                    <td ></td>
                    <td  >{{ $result=(isset($sumaImpuestoSG0))?$sumaImpuestoSG0:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoSG1))?$sumaImpuestoSG1:1 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoSG2))?$sumaImpuestoSG2:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoSG4))?$sumaImpuestoSG4:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoSG8))?$sumaImpuestoSG8:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoSG13))?$sumaImpuestoSG13:0 }}</td>
                    <td  ></td>
                    </tr>
                        <tr >
                            <td ></td>
                        <td ></td>
                        <td ></td>
                            <td  >Gastos Exentos</td>
                           <td >{{ $result=(isset($sumaMontoEG))?$sumaMontoEG:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoEG0))?$sumaMontoEG0:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoEG1))?$sumaMontoEG1:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoEG2))?$sumaMontoEG2:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoEG4))?$sumaMontoEG4:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoEG8))?$sumaMontoEG8:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoEG13))?$sumaMontoEG13:0 }}</td>
                      <td  >{{ $result=(isset($sumaTotalEG))?$sumaTotalEG:0 }}</td>
                        </tr>
                        <tr >
                            <td ></td>
                        <td ></td>
                        <td ></td>
                    <td  >Impuestos de gastos exentos</td>
                    <td ></td>
                    <td  >{{ $result=(isset($sumaImpuestoEG0))?$sumaImpuestoEG0:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoEG1))?$sumaImpuestoEG1:1 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoEG2))?$sumaImpuestoEG2:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoEG4))?$sumaImpuestoEG4:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoEG8))?$sumaImpuestoEG8:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoEG13))?$sumaImpuestoEG13:0 }}</td>
                    <td  ></td>
                    </tr>
                        <tr >
                            <td ></td>
                        <td ></td>
                        <td ></td>
                            <td  >Gastos no sujetos</td>
                            <td >{{  $result=(isset($sumaMontoNSG))?$sumaMontoNSG:0 }}</td>
                      <td  >{{  $result=(isset($sumaMontoNSG0))?$sumaMontoNSG0:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoNSG1))?$sumaMontoNSG1:0 }}</td>
                      <td  >{{  $result=(isset($sumaMontoNSG2))?$sumaMontoNSG2:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoNSG4))?$sumaMontoNSG4:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoNSG8))?$sumaMontoNSG8:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoNSG13))?$sumaMontoNSG13:0 }}</td>
                      <td  >{{ $result=(isset($sumaTotalNSG))?$sumaTotalNSG:0 }}</td>
                        </tr>
                         <tr >
                             <td ></td>
                        <td ></td>
                        <td ></td>
                    <td  >Impuestos de gastos no sujetos</td>
                    <td ></td>
                    <td  >{{ $result=(isset($sumaImpuestoNSG0))?$sumaImpuestoNSG0:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoNSG1))?$sumaImpuestoNSG1:1 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoNSG2))?$sumaImpuestoNSG2:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoNSG4))?$sumaImpuestoNSG4:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoNSG8))?$sumaImpuestoNSG8:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoNSG13))?$sumaImpuestoNSG13:0 }}</td>
                    <td  ></td>
                    </tr>
                        <tr >
                            <td ></td>
                        <td ></td>
                        <td ></td>
                            <td  >Gastos fuera actividad econ.</td>
                             <td >{{ $result=(isset($sumaMontoFAEG))?$sumaMontoFAEG:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoFAEG0))?$sumaMontoFAEG0:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoFAEG1))?$sumaMontoFAEG1:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoFAEG2))?$sumaMontoFAEG2:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoFAEG4))?$sumaMontoFAEG4:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoFAEG8))?$sumaMontoFAEG8:0 }}</td>
                      <td  >{{ $result=(isset($sumaMontoFAEG13))?$sumaMontoFAEG13:0 }}</td>
                      <td  >{{ $result=(isset($sumaTotalFAEG))?$sumaTotalFAEG:0 }}</td>
                        </tr>
                         <tr >
                             <td ></td>
                        <td ></td>
                        <td ></td>
                    <td  >Impuestos de gastos fuera de la actividad economica</td>
                    <td ></td>
                    <td  >{{ $result=(isset($sumaImpuestoFAEG0))?$sumaImpuestoFAEG0:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoFAEG1))?$sumaImpuestoFAEG1:1 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoFAEG2))?$sumaImpuestoFAEG2:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoFAEG4))?$sumaImpuestoFAEG4:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoFAEG8))?$sumaImpuestoFAEG8:0 }}</td>
                    <td  >{{ $result=(isset($sumaImpuestoFAEG13))?$sumaImpuestoFAEG13:0 }}</td>
                    <td  ></td>
                    </tr>
                
            </tbody>
             <tfoot>
              </tfoot>
        </table>
