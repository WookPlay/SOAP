<h5>Resultado:</h5> <br/>
<table class="table table-hover" >
    <thead>
        <tr>
            <th>#</th>
            <th>Parametro</th>
            <th>Respuesta</th>
        </tr>
    </thead>
    <tbody>
<?php

    extract($_POST);

    @session_start();
    $ws             = $_SESSION['ws'];
    $client         = new SoapClient($ws);
    $parametros     = $client->__getTypes();
    $params = $_POST;
    try {
        $response = $client->__soapCall($funcion, array($params));
        $i=0;
        foreach ($response as $key => $value) {
            $i++;
?>
        <tr>
            <th scope="row"><?php echo $i; ?></th>
            <td><?php echo $key; ?></td>
            <td><?php echo $value; ?></td>
        </tr>
<?php
        }
    } catch (Exception $e) {

?>
        <tr>
            <th scope="row">1</th>
            <td><b>Error al Llamar la Función:</b></td>
            <td><?php echo $funcion; ?></td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td><b>Descripción del Error:</b></td>
            <td><?php echo $e->getMessage(); ?></td>
        </tr>
<?php
		$error_log = '../../php_errors.log';
		if (is_file($error_log)){
			$fp                 = fopen($error_log, "r");
			//leemos el archivo
			$inf_log = fread($fp, (filesize($error_log)+1000));
			fclose($fp);

?>
		<tr>
            <th scope="row">3</th>
            <td><b>Log de Error:</b></td>
            <td><?php echo $inf_log; ?></td>
        </tr>
<?php
			$i = (is_file($error_log)) ? 3 : 2;
			unlink($error_log);
		}else{
			$i = (is_file($error_log)) ? 3 : 2;
		}

?>
<?php

        foreach ($parametros as $key => $value) {
        	if (stristr($value, $funcion."_Response")) {
                $variables = str_replace( '{' , '', stristr($value, '{'));
                $variables = explode(';' , stristr($variables, '}', true));
                foreach ($variables as $key1 => $value1) {
                    list($type, $parametro) = explode(' ', trim($value1));
                    if($parametro<>''){
                        $i++;
?>
                        <tr>
                            <th scope="row"><?php echo $i; ?></th>
                            <td><?php echo $parametro; ?></td>
                            <td>Error</td>
                        </tr>
<?php
                    }
                }
            }
        }

    }
?>

    </tbody>
</table>
