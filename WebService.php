<?php

    if (stristr($_SERVER['QUERY_STRING'], "wsdl")) {
        header ('Location: soap.php?wsdl');
    }else{
        include_once 'test_WebService.php';
    }

?>
