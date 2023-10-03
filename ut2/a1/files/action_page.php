<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor1 = $_POST["valor1"];
    $valor2 = $_POST["valor2"];
    $operacion = $_POST["operacion"];
    $resultado = 0;

    switch ($operacion) {
        case "sumar":
            $resultado = $valor1 + $valor2;
            break;
        case "restar":
            $resultado = $valor1 - $valor2;
            break;
        case "multiplicar":
            $resultado = $valor1 * $valor2;
            break;
        case "dividir":
            if ($valor2 != 0) {
                $resultado = $valor1 / $valor2;
            } else {
                echo "<p id='resultado' style='color: red;'>No se puede dividir por 0.</p>";
            }
            break;
    }

    echo "<p id='resultado'>Resultado: $resultado</p>";
}
?>
