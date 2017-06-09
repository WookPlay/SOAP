    function parametros (){
        /* Almacenamos los parametros a enviar*/
        var parametros = {
            "funcion"         : $('#funcion').val(),
        };

        $.ajax({
                type    : 'post',
                data    : parametros,
                url     : 'assets/php/parametros.php',
                cache   : true,
                async   : false,
                beforeSend: function () {
                    $('#parametros').html('<span> Cargando Parametros.</span>');
                },
                success:  function (response) {
                    $('#parametros').html(response);
                    $("#result").html('<h5>Resultado:</h5> <br/> ');

                     $(function(){
                        $("#test_webservice").on("submit", function(e){
                            /*ENVIAMOS EL FORMULARIO*/
                            e.preventDefault();
                            var f        = $(this);
                            var formData = new FormData(document.getElementById("test_webservice"));

                            $.ajax({
                                url         : 'assets/php/respuesta.php',
                                type        : "post",
                                dataType    : "html",
                                data        : formData,
                                cache       : false,
                                contentType : false,
                                processData : false,
                                beforeSend  : function () {
                                    $("#result").html('<h5>Resultado:</h5> <br/> <b class="fa fa-spinner fa-pulse"></b> <b>Procesando.');

                                }
                            })
                            .done(function(res){
                                $("#result").html(res);
                            });
                        });
                    });


                },
                error:function(response){
                }
        });
    }
