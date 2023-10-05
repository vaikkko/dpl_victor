<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <title>Aplicación PHP</title>
  </head>

  <body>
    <div class="Calculadora">
    <h1>Calculadora en entorno nativo</h1>

    <form action="/action_page.php">
      <label for="fname">Valor 1: </label>
      <input type="text" id="fname" name="fname" /><br />

      <label for="lname">Valor 2: </label>
      <input type="text" id="lname" name="lname" /><br />

      <label for="operacion">Operación:</label>
      <select name="operacion" id="operacion">
        <option value="sumar">Sumar</option>
        <option value="restar">Restar</option>
        <option value="multiplicar">Multiplicar</option>
        <option value="dividir">Dividir</option></select
      ><br />

      <img src="calculadoraPeq.png" alt="calculadora" /><br />

      <button type="submit">Calcular</button>
    </form>
    
    </div>
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
  </body>
</html>