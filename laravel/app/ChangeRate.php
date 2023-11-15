<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 * Description of ChangeRate
 *
 * @author kcamp_ufhajjh
 */
class ChangeRate {

    function etiqueta_final($parser, $name) {
        global $info, $datos, $contador;
        $info[$name][] = $datos;
    }

    function extractor_datos($parser, $data) {
        global $datos;
        $datos = $data;
    }

    function extractor_datos_tags($parser, $data) {
        global $datos;
        $datos .= $data;
    }

    function parser_extractor($cadena, $tags = true) {

        // Definiendo variables 
        global $info, $datos, $contador;
        $info = array();
        $datos = "";
        $contador = 0;

        // Creando el parser
        $xml_parser = xml_parser_create();

        // Definiendo la funciones controladoras
        xml_set_character_data_handler($xml_parser, ($tags ? "extractor_datos" : "extractor_datos_tags"));
        xml_set_element_handler($xml_parser, "", "etiqueta_final");

        // Procesando el archivo
        if (!xml_parse($xml_parser, $cadena)) {
            die(sprintf("XML error: %s at line %d",
                            xml_error_string(xml_get_error_code($xml_parser)),
                            xml_get_current_line_number($xml_parser)));
        }

        // Liberando recursos
        xml_parser_free($xml_parser);
        return $info;
    }

    /*
      La siguiente Funcion debe recibir por parametro la fecha en formato dd/mm/YYYY
     */

    function tipo_cambio($fecha) {
        // Rutas de los archivos XML con el tipo de cambio
        $correo = "k.campos@contafast.net";
        $file["compra"] = "http://gee.bccr.fi.cr/Indicadores/Suscripciones/WS/wsindicadoreseconomicos.asmx/ObtenerIndicadoresEconomicos?Indicador=317&FechaInicio=$fecha&FechaFinal=$fecha&Nombre=KevilynCampos&SubNiveles=N&CorreoElectronico=$correo&Token=5OEPKOITVE"; // Archivo XML 
        $file["venta"] = "http://gee.bccr.fi.cr/Indicadores/Suscripciones/WS/wsindicadoreseconomicos.asmx/ObtenerIndicadoresEconomicos?Indicador=318&FechaInicio=$fecha&FechaFinal=$fecha&Nombre=KevilynCampos&SubNiveles=N&CorreoElectronico=$correo&Token=5OEPKOITVE"; // Archivo XML 
        // Extrae el tipo cambio con el valor de COMPRA
        $data_file = file_get_contents($file["compra"]);
        $ainfo = parser_extractor($data_file, false);
        $fuente = parser_extractor($ainfo["STRING"][0]);
        if (isset($fuente["NUM_VALOR"])) {
            $tipo["compra"] = $fuente["NUM_VALOR"][0];
        } else {
            $tipo["compra"] = "N/R";
        }
        // Extrae el tipo cambio con el valor de VENTA
        $data_file = file_get_contents($file["venta"]);
        $ainfo = parser_extractor($data_file, false);
        $fuente = parser_extractor($ainfo["STRING"][0]);
        if (isset($fuente["NUM_VALOR"])) {
            $tipo["venta"] = $fuente["NUM_VALOR"][0];
        } else {
            $tipo["venta"] = "N/R";
        }
        // Retornando valor de compra y venta del dolar
        if ($tipo["compra"] == "" or $tipo["venta"] == "") {
            return false;
        } else {
            return $tipo;
        }
    }

}
