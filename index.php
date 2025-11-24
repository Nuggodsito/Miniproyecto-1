// === archivo: index.php ===
<?php
require_once __DIR__ . '/utilities.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mini Proyecto - Menú</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; max-width:900px; margin:2rem auto; padding:0 1rem; }
        nav a { display:inline-block; margin:0.25rem 0; }
        ul { list-style: none; padding-left: 0; }
        li { margin: 0.5rem 0; }
        .note { font-size:0.9rem; color:#333; margin-top:1rem; }
    </style>
</head>
<body>
    <h1>Mini Proyecto — Menú principal</h1>
    <p>Coloca todos los archivos en la misma carpeta. Copia cada bloque en su respectivo archivo.</p>

    <nav>
        <ul>
            <li><a href="problema1.php">Problema 1 — Media, desviación, min/max (5 números)</a></li>
            <li><a href="problema2.php">Problema 2 — Suma 1 a 1000</a></li>
            <li><a href="problema3.php">Problema 3 — N primeros múltiplos de 4</a></li>
            <li><a href="problema4.php">Problema 4 — Suma pares e impares 1 a 200</a></li>
            <li><a href="problema5.php">Problema 5 — Clasificar edades (5 personas)</a></li>
            <li><a href="problema6.php">Problema 6 — Presupuesto hospital</a></li>
            <li><a href="problema7.php">Problema 7 — Estación del año por fecha</a></li>
            <li><a href="problema8.php">Problema 8 — Potencias (1..15) de número (1-9)</a></li>
            <li><a href="problema9.php">Problema 9 — Calculadora estadística (N notas)</a></li>
            <li><a href="problema10.php">Problema 10 — Ventas (arreglo bidimensional)</a></li>
        </ul>
    </nav>

    <div class="note">
        <strong>Notas:</strong>
        <ul>
            <li>Todos los formularios procesan en la misma página del problema (POST).</li>
            <li>Se usará <code>utilities.php</code> para sanitizar/validar y generar el enlace de regreso.</li>
            <li>Al final de cada archivo debes incluir <code>footer.php</code> para mostrar la fecha.</li>
        </ul>
    </div>

    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
