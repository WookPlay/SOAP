<div style=" max-height: 500px; overflow: auto;">
   <?php
    @session_start();
    extract($_POST);
    $ws             = $_SESSION['ws'];
    $client         = new SoapClient($ws);
    $parametros     = $client->__getTypes();

    foreach ($parametros as $key => $value) {
        if (stristr($value, $funcion."_Request")) {
            $variables = str_replace( '{' , '', stristr($value, '{'));
            $variables = explode(';' , stristr($variables, '}', true));
            foreach ($variables as $key1 => $value1) {
                list($type, $parametro) = explode(' ', trim($value1));
                if($parametro<>''){
?>
                    <fieldset>
                      <span>Parametro: <b> <?php echo $parametro; ?></b> Tipo: <b> <?php echo $type; ?></b></span>
                      <input name="<?php echo $parametro; ?>" placeholder="..." type="text" tabindex="2" autofocus autocomplete="off">
                    </fieldset>
<?php
                }
            }

        }
    }
?>
</div>
<fieldset>
  <button name="submit" type="submit" id="contact-submit" data-submit="...Sending"><b class="fa fa-shield"></b> Test</button>
</fieldset>
