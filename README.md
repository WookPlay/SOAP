# WebServicePHP7
###### NOTA:
Este WebService esta Desarrollado en PHP utilizando Tecnologia SOAP 1.1


-----

**SOAP** (**Simple Object Access Protocol**) es un protocolo estándar que define cómo dos objetos en diferentes procesos pueden comunicarse por medio de intercambio de datos XML.

-----

###### REQUERIMIENTOS:
Es Importante para tener un buen funcionamiento, activar los siguientes modulos PHP:
- PHP 5.6 o Superior.
- SOAP
- XML

###### FUNCIONAMIENTO:
Para utilizar el WebService debemos llamar el archivo '**WebService.php?wsdl**' en caso de que solo queramos probar el WebService solo llamamos el Archivo '**WebService.php**' y nos mostrara la pagina para realizar un test de nuestro WebService para probarlo antes de utilizarlo.

###### CONFIGURACIÓN:
Las Funciones son programadas en el Archivo **soap.php**.

Dentro del Comentario de 'FUNCIONES' registraremos todas las funciones que se pueden ejecutar con el WebService, A continuación se explica el significado de cada campo del array de la función:


| Campo               |  Descripción                             |
| --------            |  --------                                |
| 'Name_Function'     | Nombre de la Función.                    |
| 'Tipe_Data'         | Tipo de Datos (Siempre String).          |
| 'Input_Params'      | Parametros que recibira el WebService.   |
| 'Output_Params'     | Parametros que Retornara el WebService.  |

Dentro del Comentario de 'CONFIGURACIÓN WSDL' registraremos la configuración del archivo WSDL del WebService, A continuación se explica el significado de cada campo del array de la función:


| Campo               |  Descripción                                                                                |
| --------            |  --------                                                                                   |
| 'Name_WebService'   | Nombre del WebService.                                                                      |
| 'Func_WebService'   | Funciones del WebService (Aquí Indicamos el array que almacena las funciones registradas).  |

Dentro de este archivo encontraremos los siguientes fragmentos de Código:

```
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
```

###### FUNCIONES:
- Dentro del Archivo **soap.php** tambien agregaremos las funciones (BackEnd) que se procesaran cuando se consuma el WebService, es preciso aclarar que el archivo **soap.php** solo se puede usar el **include**, **include_once**, **require**, **require_once** dentro de una función declarada, ya que si se utiliza el include afuera de una función esto incurre en problemas para realizar el consumo del WebService.

- Los nombres de las funciones (BackEnd) debe ser los mismos nombres de las funciones indicadas en la configuración del WSDL.

```
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
```