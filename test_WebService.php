<?php
    @session_start();

    $soap   = (!extension_loaded('soap')) ? 'false' : 'true';
    $xml    = (!extension_loaded('xml')) ? 'false' : 'true';

    if($soap == 'true' && $xml == 'true'){
        list($protocolo) = explode('/',strtolower($_SERVER['SERVER_PROTOCOL']));
        $url = $protocolo.'://'.$_SERVER['HTTP_HOST'].str_replace(end(explode('/',$_SERVER['PHP_SELF'])),'',$_SERVER['PHP_SELF']);
        $ws             = $url.'WebService.php?wsdl';
        $client         = new SoapClient($ws);
        $funciones      = $client->__getFunctions();
        $_SESSION['ws'] = $ws;

        $xml = simpleXML_load_file($ws,"SimpleXMLElement",LIBXML_NOCDATA);
        $WebServiceName = $xml['name'];
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Test WebService</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/css/style.min.css" />
        <link rel="stylesheet" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css" />
        <link rel="shortcut icon" href="/favicon.ico" />
    </head>

    <body>
        <div class="container">
          <form class="form" id="test_webservice" name="test_webservice" action="" method="post">
            <h3>Test WebService</h3>
            <h4><?php if($soap == 'true' && $xml == 'true'){ echo $WebServiceName; } ?></h4>
            <div class="row">
               <?php if($soap == 'false' || $xml == 'false'){ ?>
                <div class="col-md-4">
                    <p>
                        Activar los siguientes modulos PHP: <br>
                       <b> <?php echo ($soap=='false')? '- SOAP <br>' : ''?>
                         <?php echo ($xml=='false')? '- XML <br>' : ''?>  </b>
                    </p>
                </div>
                <?php }else{ ?>
                <div class="col-md-4">
                    <fieldset>
                      <span>Funciones:</span>
                      <select name="funcion" id="funcion" tabindex="1">
                         <?php
                                foreach ($funciones as $key => $value) {
                                    list($funcion) = explode(' ', $value);
                                    $funcion = str_replace('_Response','',$funcion);
                                    $funcion = str_replace('Response','',$funcion);
                          ?>
                                    <option value="<?php echo $funcion; ?>"><?php echo $funcion; ?></option>
                          <?php
                                }
                          ?>

                      </select>
                      <a href="javascript: void(0);" class="btn btn-primary" onclick="javascript: parametros();"> <b class="fa fa-search"></b> Ver</a>
                    </fieldset>
                    <hr>
                    <div id="parametros"></div>


                </div>
                <div class="col-md-8">
                    <div id="result" class="result"><h5>Resultado:</h5></div>
                </div>
                <?php } ?>
            </div>
          </form>
        </div>
        <script type="text/javascript" src="assets/js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.js"></script>
        <script type="text/javascript" src="assets/js/app.min.js"></script>
    </body>
</html>
