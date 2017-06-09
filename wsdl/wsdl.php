<?php
// ----------------------------------------------------------------------------
// PARAMETERS SET UP
// ----------------------------------------------------------------------------

    /*CREAMOS LA URL SOAP*/
        list($protocolo) = explode('/',strtolower($_SERVER['SERVER_PROTOCOL']));
        $url = $protocolo.'://'.$_SERVER['HTTP_HOST'].str_replace('/wsdl/'.end(explode('/',$_SERVER['PHP_SELF'])),'',$_SERVER['PHP_SELF']).'soap.php';
    /*END CREAMOS LA URL SOAP*/

    /*LEEMOS LA CONFIGURACIÓN Y LAS FUNCIONES DEL ARCHIVO JSON*/
        $json       = 'func_wsdl.json';
        $json_wsdl  = fopen($json, "r");
        $inf_json   = fread($json_wsdl, (filesize($json)));
        fclose($json_wsdl);
        $config     = json_decode($inf_json,true);
    /*END LEEMOS LA CONFIGURACIÓN Y LAS FUNCIONES DEL ARCHIVO JSON*/

    /*CARGAMOS LA CONFIGURACIÓN Y LAS FUNCIONES*/
        $serviceName    = $config['Name_WebService'];
        $func           = $config['Func_WebService'];
        $functions      = array();

        foreach ($func as $key => $value) {
            $functions[count($functions)]   = [
                "funcName"      => $value['Name_Function'],
                "doc"           => "Envia los Parametros Requeridos.",
                "inputParams"   => $value['Input_Params'],
                "outputParams"  => $value['Output_Params'],
                "soapAddress"   => $url
            ];
        }
    /*END CARGAMOS LA CONFIGURACIÓN Y LAS FUNCIONES*/

// ----------------------------------------------------------------------------
// END OF PARAMETERS SET UP
// ----------------------------------------------------------------------------

/*****************************************************************************
 * Process Page / _Request
 *****************************************************************************/

    if (stristr($_SERVER['QUERY_STRING'], "wsdl")) {
        // WSDL _Request - output raw XML
        header("Content-Type: application/soap+xml; charset=utf-8");
        echo wsdl();
    }

    exit;

/*****************************************************************************
 * Create WSDL XML
 * @PARAM xmlformat=true - Display output in HTML friendly format if set false
 *****************************************************************************/
function wsdl($xmlformat=true) {
    $url_owner                  = "http://url_sitio/";
    global $functions;         // Functions that this web service supports
    global $serviceName;       // Web Service ID
    $i = 0;                    // For traversing functions array
    $j = 0;                    // For traversing parameters arrays
    $str = '';                 // XML String to output

    // Tab spacings
    $t1 = '    ';
    if (!$xmlformat) $t1 = '&nbsp;&nbsp;&nbsp;&nbsp;';
    $t2 = $t1 . $t1;
    $t3 = $t2 . $t1;
    $t4 = $t3 . $t1;
    $t5 = $t4 . $t1;

    $serviceID = str_replace(" ", "", $serviceName);

    // Declare XML format
    $str .= '<?xml version="1.0" encoding="UTF-8" standalone="no"?>' . "\n\n";

    // Declare definitions / namespaces
    $str .= '<wsdl:definitions ' . "\n";
    $str .= $t1 . 'xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" ' . "\n";
    $str .= $t1 . 'xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" ' . "\n";
    $str .= $t1 . 'xmlns:s="http://www.w3.org/2001/XMLSchema" ' . "\n";
    $str .= $t1 . 'targetNamespace="'.$url_owner.'" ' . "\n";
    $str .= $t1 . 'xmlns:tns="'.$url_owner.'" ' . "\n";
    $str .= $t1 . 'name="' . $serviceID . '" ' . "\n";
    $str .= '>' . "\n\n";

    // Declare Types / Schema
    $str .= '<wsdl:types>' . "\n";
    $str .= $t1 . '<s:schema elementFormDefault="qualified" targetNamespace="'.$url_owner.'">' . "\n";
    for ($i=0;$i<count($functions);$i++) {
        // Define _Request Types
        if (array_key_exists("inputParams", $functions[$i])) {
           $str .= $t2 . '<s:element name="' . $functions[$i]['funcName'] . '_Request">' . "\n";
           $str .= $t3 . '<s:complexType><s:sequence>' . "\n";
           for ($j=0;$j<count($functions[$i]['inputParams']);$j++) {
               $str .= $t4 . '<s:element minOccurs="1" maxOccurs="1" ';
               $str .= 'name="' . $functions[$i]['inputParams'][$j]['name'] . '" ';
               $str .= 'type="s:' . $functions[$i]['inputParams'][$j]['type'] . '" />' . "\n";
           }
           $str .= $t3 . '</s:sequence></s:complexType>' . "\n";
           $str .= $t2 . '</s:element>' . "\n";
        }
        // Define _Response Types
        if (array_key_exists("outputParams", $functions[$i])) {
           $str .= $t2 . '<s:element name="' . $functions[$i]['funcName'] . '_Response">' . "\n";
           $str .= $t3 . '<s:complexType><s:sequence>' . "\n";
           for ($j=0;$j<count($functions[$i]['outputParams']);$j++) {
               $str .= $t4 . '<s:element minOccurs="1" maxOccurs="1" ';
               $str .= 'name="' . $functions[$i]['outputParams'][$j]['name'] . '" ';
               $str .= 'type="s:' . $functions[$i]['outputParams'][$j]['type'] . '" />' . "\n";
           }
           $str .= $t3 . '</s:sequence></s:complexType>' . "\n";
           $str .= $t2 . '</s:element>' . "\n";
        }
    }
    $str .= $t1 . '</s:schema>' . "\n";
    $str .= '</wsdl:types>' . "\n\n";

    // Declare Messages
    for ($i=0;$i<count($functions);$i++) {
        // Define _Request Messages
        if (array_key_exists("inputParams", $functions[$i])) {
            $str .= '<wsdl:message name="' . $functions[$i]['funcName'] . '_Request">' . "\n";
            $str .= $t1 . '<wsdl:part name="parameters" element="tns:' . $functions[$i]['funcName'] . '_Request" />' . "\n";
            $str .= '</wsdl:message>' . "\n";
        }
        // Define _Response Messages
        if (array_key_exists("outputParams", $functions[$i])) {
            $str .= '<wsdl:message name="' . $functions[$i]['funcName'] . '_Response">' . "\n";
            $str .= $t1 . '<wsdl:part name="parameters" element="tns:' . $functions[$i]['funcName'] . '_Response" />' . "\n";
            $str .= '</wsdl:message>' . "\n\n";
        }
    }

    // Declare Port Types
    for ($i=0;$i<count($functions);$i++) {
        $str .= '<wsdl:portType name="' . $functions[$i]['funcName'] . '_PortType">' . "\n";
        $str .= $t1 . '<wsdl:operation name="' . $functions[$i]['funcName'] . '">' . "\n";
        if (array_key_exists("inputParams", $functions[$i]))
           $str .= $t2 . '<wsdl:input message="tns:' . $functions[$i]['funcName'] . '_Request" />' . "\n";
        if (array_key_exists("outputParams", $functions[$i]))
           $str .= $t2 . '<wsdl:output message="tns:' . $functions[$i]['funcName'] . '_Response" />' . "\n";
        $str .= $t1 . '</wsdl:operation>' . "\n";
        $str .= '</wsdl:portType>' . "\n\n";
    }

    // Declare Bindings
    for ($i=0;$i<count($functions);$i++) {
        $str .= '<wsdl:binding name="' . $functions[$i]['funcName'] . '_Binding" type="tns:' . $functions[$i]['funcName'] . '_PortType">' . "\n";
        $str .= $t1 . '<soap:binding style="document" transport="http://schemas.xmlsoap.org/soap/http" />' . "\n";
        $str .= $t1 . '<wsdl:operation name="' . $functions[$i]['funcName'] . '">' . "\n";
        $str .= $t2 . '<soap:operation soapAction="' . $functions[$i]['soapAddress'] . '#' . $functions[$i]['funcName'] . '" style="document" />' . "\n";
        if (array_key_exists("inputParams", $functions[$i]))
            $str .= $t2 . '<wsdl:input><soap:body use="literal" /></wsdl:input>' . "\n";
        if (array_key_exists("outputParams", $functions[$i]))
            $str .= $t2 . '<wsdl:output><soap:body use="literal" /></wsdl:output>' . "\n";
        $str .= $t2 . '<wsdl:documentation>' . $functions[$i]['doc'] . '</wsdl:documentation>' . "\n";
        $str .= $t1 . '</wsdl:operation>' . "\n";
        $str .= '</wsdl:binding>' . "\n\n";
    }

    // Declare Service
    $str .= '<wsdl:service name="' . $serviceID . '">' . "\n";
    for ($i=0;$i<count($functions);$i++) {
        $str .= $t1 . '<wsdl:port name="' . $functions[$i]['funcName'] . '_Port" binding="tns:' . $functions[$i]['funcName'] . '_Binding">' . "\n";
        $str .= $t2 . '<soap:address location="' . $functions[$i]['soapAddress'] . '" />' . "\n";
        $str .= $t1 . '</wsdl:port>' . "\n";
    }
    $str .= '</wsdl:service>' . "\n\n";

    // End Document
    $str .= '</wsdl:definitions>' . "\n";

    if (!$xmlformat) $str = str_replace("<", "&lt;", $str);
    if (!$xmlformat) $str = str_replace(">", "&gt;", $str);
    if (!$xmlformat) $str = str_replace("\n", "<br />", $str);
    return $str;
}

?>
