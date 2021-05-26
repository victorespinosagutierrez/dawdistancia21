<!DOCTYPE html>
<!--
DWES curso 2020-2021. Exámen de Febrero 2021
Alumno: Víctor Espinosa Gutiérrez
Ejercicio 1A

TAREA5 DAW. 26/05/2021
SE INTRODUCE NUEVO COMENTARIO EN ESTA LÍNEA PARA PROBAR 
LOS COMMITS DE GITHUB

-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            .error{
                color:red;
            }
        </style>
    </head>
    <body>
        <h2>DWES. Exámen Febrero 2021. Ejercicio 1A</h2>
        <h3>Alumno: Víctor Espinosa Gutiérrez</h3>

        <?php
        session_start();   //inicia la sesión

        $error = "";
        $textoRecibido = "";
        $textoTransformado = "";

        if (!empty($_POST['texto'])) {
            $textoRecibido = getParametroPost('texto');   //obtiene el texto


            if (!validarTextoRecibido($textoRecibido)) {
                $error = "El texto recibido no es válido, debe tener una longitud entre 2 y 50 caracteres";
            } else {
                $textoTransformado = transformarCadenaRecibida($textoRecibido);
            }


            if (empty($error)) {
                actualizarArraySession($textoTransformado);
                print 'El texto introducido es: ' . $textoRecibido . '<br>';
                print 'El texto transformado es: ' . $textoTransformado . '<br>';
            } else {
                print '<span class="error"> ' . $error . "</span><br>";
            }
        }

        print '<br>';
        print mostrarArrayAlmacenadoSesion();
        print '<br>';
        ?>

        <br>
        <form action="ejercicio1A.php" method="POST">
            <input type="text" name="texto"/>
            <input type="submit" value="Enviar"/>
        </form>

    </body>
</html>




<?php
//------DECLARACIÓN FUNCIONES--------

/**
 * Función que obtiene y sanea un parámetro POST
 * @author Víctor Espinosa Gutiérrez
 * @version 1.0
 * @param type $parametro nombre del parámetro post que se quiere capturar
 * @return type valor del parámetro post consulado
 * @access private
 * @internal Información para desarrolladores: mucho cuidado al modificar esta funcíon, se utiliza desde muchos puntos del código fuente.
 */
function getParametroPost($parametro) {
    $resultado = isset($_POST[$parametro]) ? trim(filter_input(INPUT_POST, $parametro, FILTER_SANITIZE_STRING)) : "";
    return $resultado;
}

/**
 * Función que transforma la cadena. Añade un % delante de un carácter si es alguno de los especificados {}=%
 * @author Víctor Espinosa Gutiérrez
 * @version 1.0
 * @param type $cadena para transformar
 * @return type la cadena transformada
 * @access private
 */
function transformarCadenaRecibida($cadena) {
    $arrayCaracteresEscapar = array('{', '}', '=', '%');
    $resultado = "";
    for ($i = 0; $i < strlen($cadena); $i++) {   // Recorremos cada carácter de la cadena
        if (in_array($cadena[$i], $arrayCaracteresEscapar)) {   //si encuentra alguno de los buscados añade % delante
            $resultado .= '%';
        }
        $resultado .= $cadena[$i];    //añade el carácter
    }
    return $resultado;
}

/**
 * Función que valida que el texto recibido no sea vacio y tenga una longitud mayor de 2 y menor de 50
 * @author Víctor Espinosa Gutiérrez
 * @version 1.0
 * @return type true o false si es válido o no
 * @access public
 */
function validarTextoRecibido($texto) {
    return !empty($texto) && strlen($texto) > 2 && strlen($texto) < 50;
}

/**
 * Función que añade la última transformación a la sesión
 * Solo se almacena en sesión las 10 últimas transformaciones
 * @author Víctor Espinosa Gutiérrez
 * @version 1.0
 * @param type $texto
 * @access public
 */
function actualizarArraySession($texto) {
    $arrayTransformaciones = array();
    if (isset($_SESSION['arrayTransformaciones'])) {
        $arrayTransformaciones = $_SESSION['arrayTransformaciones'];   //se obtiene el array de sesión si existe, si no existe nos quedamos con un array vacio que se inicializó antes
    }

    if (count($arrayTransformaciones) > 9) {
        array_shift($arrayTransformaciones);   //si hay 10 eliminamos el primero
    }

    array_push($arrayTransformaciones, $texto);   //se añade al final

    $_SESSION['arrayTransformaciones'] = $arrayTransformaciones;    //se actualiza el array en sesión
}

/**
 * Función que muestra el array de transformaciones almacenado en sesión
 * Las muestra en forma de cadena separadas por |
 * En el caso de querer modificar el aspecto visual con el que se muestra solo
 * sería necesario cambiar esta función
 * @author Víctor Espinosa Gutiérrez
 * @version 1.0
 * @return string cadena con las transformaciones efectuadas
 * @access public
 */
function mostrarArrayAlmacenadoSesion() {
    $resultado = "Últimas 10 transformaciones realizadas: <br>";

    $arrayTransformaciones = array();
    if (isset($_SESSION['arrayTransformaciones'])) {
        $arrayTransformaciones = $_SESSION['arrayTransformaciones'];   //obtiene el array de transformaciones de sesión
    }

    foreach ($arrayTransformaciones as $valor) {    //recorre el array de transformaciones y lo muestra separados por |
        $resultado .= $valor . '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
    }

    return $resultado;
}

//------FIN DECLARACIÓN FUNCIONES--------
?>
