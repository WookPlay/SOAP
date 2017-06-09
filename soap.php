<?php
	date_default_timezone_set ('America/Bogota');
    /*----------------------------------------------------------
    // WEB SERVICE SOAP V1.1
    /---------------------------------------------------------*/
    error_reporting(0);
        /*DESHABILITAR LA CACHE DEL WSDL*/
            ini_set("soap.wsdl_cache_enabled", "0");
        /*END DESHABILITAR LA CACHE DEL WSDL*/

        /*----------------------------------------------------------
        // URL WSDL
        /---------------------------------------------------------*/
            list($protocolo) = explode('/',strtolower($_SERVER['SERVER_PROTOCOL']));
            $url = $protocolo.'://'.$_SERVER['HTTP_HOST'].str_replace(end(explode('/',$_SERVER['PHP_SELF'])),'',$_SERVER['PHP_SELF']);
        /*----------------------------------------------------------
        // END URL WSDL
        /---------------------------------------------------------*/
        /*------------------------------------------------------------------
        // CREAMOS LA CONFIGURACIÓN Y LAS FUNCIONES PARA LA ESTRUCTURA WSDL
        /------------------------------------------------------------------*/
            /*FUNCIONES*/
                $funciones[count($funciones)] = [
                    'Name_Function' => 'WS_funcion1',
                    'Tipe_Data'     => 'string',
                    'Input_Params'  => array(array("name" => "dato1", "type" => "int"),
                                             array("name" => "dato2", "type" => "int"),
                                             array("name" => "dato3", "type" => "decimal"),
                                             array("name" => "dato4", "type" => "string"),
                                             array("name" => "dato5", "type" => "int")
                                            ),
                    'Output_Params' => array(array("name" => "Success", "type" => "string"),
                                             array("name" => "dato_respuesta1", "type" => "string"),
                                             array("name" => "dato_respuesta2", "type" => "string")
                                            )
                ];
                $funciones[count($funciones)] = [
                    'Name_Function' => 'WS_funcion2',
                    'Tipe_Data'     => 'string',
                    'Input_Params'  => array(array("name" => "dato1", "type" => "string"),
                                             array("name" => "dato2", "type" => "string")
                                            ),
                    'Output_Params' => array(array("name" => "Success",         "type" => "string"),
                                             array("name" => "dato_respuesta1", "type" => "string")
                                            )
                ];
            /*END FUNCIONES*/

            /*CONFIGURACIÓN WSDL*/
                $config = [
                    'Name_WebService' => 'NameWebServicePHP7',
                    'Func_WebService' => $funciones
                ];
            /*END CONFIGURACIÓN WSDL*/

            /*CREAMOS JSON QUE ALMACENA LA INFORMACIÓN DE LA CONFIGURACIÓN Y FUNCIONES WSDL*/
                $json                 = 'wsdl/func_wsdl.json';
                $json_wsdl            = fopen($json, "w+");
                fputs($json_wsdl,json_encode($config));
                fclose($json_wsdl);
            /*CREAMOS JSON QUE ALMACENA LA INFORMACIÓN DE LA CONFIGURACIÓN Y FUNCIONES WSDL*/
        /*----------------------------------------------------------------------
        // END CREAMOS LA CONFIGURACIÓN Y LAS FUNCIONES PARA LA ESTRUCTURA WSDL
        /----------------------------------------------------------------------*/
        /*----------------------------------------------------------
        // INICIALIZA SOAP
        /---------------------------------------------------------*/

            /* INICIAMOS EL SERVICIO INDICANDO EL ARCHIVO WSDL CREADOR DE LA ESTRUCTURA*/
                $server = new SoapServer($url."/wsdl/wsdl.php?WSDL", array('soap_version' => SOAP_1_2));
            /* END INICIAMOS EL SERVICIO INDICANDO EL ARCHIVO WSDL CREADOR DE LA ESTRUCTURA*/

            /* AGREGAMOS LAS FUNCIONES AL WS SOAP*/
                foreach ($funciones as $key => $value) {
                   $server->addFunction($value['Name_Function']);
                }
            /*END AGREGAMOS LAS FUNCIONES AL WS SOAP*/

            /*COMPROBAMOS QUE EL XML GENERADO ESTE CORRECTO*/
                $server->handle();
            /*END COMPROBAMOS QUE EL XML GENERADO ESTE CORRECTO*/

            /*ELIMINAMOS EL JSON DEL WSDL*/
                unlink($json);
            /*END ELIMINAMOS EL JSON DEL WSDL*/
        /*----------------------------------------------------------
        // END INICIALIZA SOAP
        /---------------------------------------------------------*/
        /*----------------------------------------------------------
        // FUNCIONES
        /---------------------------------------------------------*/

            /*NOTA: ES IMPORTANTE QUE EL NOMBRE DE LAS FUNCIONES Y LAS VARIABLES SEAN LAS MISMAS CONFIGURADAS EN LA ESTRUCTURA WSDL*/

            function WS_funcion1($formdata) {

                /*EXTRAEMOS PARÁMETROS DE LA CONEXIÓN SOAP*/
                    $formdata = get_object_vars($formdata);
                /*END EXTRAEMOS PÁRAMETROS DE LA CONEXIÓN SOAP*/

                /*ORDENA LOS PARÁMETROS Y TOMA SUS DATOS*/
                    $dato1      = $formdata['dato1'];
                    $dato2      = $formdata['dato2'];
                    $dato3      = $formdata['dato3'];
                    $dato4      = trim($formdata['dato4']);
                    $dato5      = $formdata['dato5'];
                /*END ORDENA LOS PARÁMETROS Y TOMA SUS DATOS*/
                /*CREAMOS EL CONTENIDO*/

                /*END CONTENIDO*/

                /*RESULTADO*/
                    $respuesta  = [
                        'Success'           => 'Ok',
                        'dato_respuesta1'   => $dato1,
                        'dato_respuesta2'   => $dato2
                    ];
                    return $respuesta;
                /*END RESULTADO*/
            }
            function WS_funcion2($formdata) {

                /*EXTRAEMOS PARÁMETROS DE LA CONEXIÓN SOAP*/
                    $formdata = get_object_vars($formdata);
                /*END EXTRAEMOS PÁRAMETROS DE LA CONEXIÓN SOAP*/

                /*ORDENA LOS PARÁMETROS Y TOMA SUS DATOS*/
                    $dato1     = $formdata['dato1'];
                    $dato2     = $formdata['dato2'];
                /*END ORDENA LOS PARÁMETROS Y TOMA SUS DATOS*/

                /*RESULTADO*/
                    $respuesta  = [
                        'Success'           => 'Ok',
                        'dato_respuesta1'   => $dato1
                    ];
                    return $respuesta;
                /*END RESULTADO*/
            }


        /*----------------------------------------------------------
        // END FUNCIONES
        /---------------------------------------------------------*/
    /*----------------------------------------------------------
    // END WEB SERVICE SOAP V1.1
    /---------------------------------------------------------*/
?>
