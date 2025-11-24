<?php
$base_url = '/MINIPROYECTO1';
include_once '../includes/utilities.php';
include_once '../includes/header.php';

$estaciones = [
    'Verano' => ['inicio' => '12-21', 'fin' => '03-20', 'imagen' => 'verano.jpg'],
    'Otoño' => ['inicio' => '03-21', 'fin' => '06-21', 'imagen' => 'otono.jpg'],
    'Invierno' => ['inicio' => '06-22', 'fin' => '09-22', 'imagen' => 'invierno.jpg'],
    'Primavera' => ['inicio' => '09-23', 'fin' => '12-20', 'imagen' => 'primavera.jpg']
];

function estaEnRango($fecha, $inicio, $fin) {
    return ($inicio > $fin) ? ($fecha >= $inicio || $fecha <= $fin) : ($fecha >= $inicio && $fecha <= $fin);
}

function formatFecha($fecha) {
    $meses = ['01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr', '05' => 'May', '06' => 'Jun',
              '07' => 'Jul', '08' => 'Ago', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'];
    list($mes, $dia) = explode('-', $fecha);
    return $dia . ' de ' . $meses[$mes];
}

$resultado = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_input = Utilities::sanitizarTexto($_POST['fecha'] ?? '');
    
    if (Utilities::validarFecha($fecha_input)) {
        list($mes, $dia, $año) = explode('/', $fecha_input);
        $fecha_actual = sprintf('%02d-%02d', (int)$mes, (int)$dia);
        
        foreach ($estaciones as $estacion => $rango) {
            if (estaEnRango($fecha_actual, $rango['inicio'], $rango['fin'])) {
                $resultado = [
                    'fecha' => $fecha_input,
                    'estacion' => $estacion,
                    'rango' => $rango,
                    'imagen' => $rango['imagen']
                ];
                break;
            }
        }
        
        if (!$resultado) {
            $error = "No se pudo determinar la estacion para la fecha ingresada.";
        }
    } else {
        $error = "Por favor ingrese una fecha valida en formato MM/DD/YYYY.";
    }
}
?>

<div class="container">
    <div class="problem-header">
        <h1>Problema #8: Estacion del Año</h1>
        <p>Determinar la estacion del año segun la fecha ingresada</p>
        <?php echo Utilities::generarEnlace('../index.php'); ?>
    </div>

    <div class="form-container">
        <form method="POST" action="">
            <div class="form-group">
                <label for="fecha">Ingrese una fecha (MM/DD/YYYY):</label>
                <input type="text" id="fecha" name="fecha" required 
                       placeholder="Ej: 09/25/2024"
                       pattern="(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/([0-9]{4})"
                       title="Formato: MM/DD/YYYY">
                <small>Formato: MM/DD/YYYY (Ej: 09/25/2024)</small>
            </div>
            
            <button type="submit" class="btn">Determinar Estacion</button>
        </form>
    </div>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($resultado)): ?>
        <div class="resultados">
            <div class="season-result">
                <div class="season-card <?php echo strtolower($resultado['estacion']); ?>" 
                     style="background-image: url('../imagenes/<?php echo $resultado['imagen']; ?>');">
                    <div class="season-info-overlay">
                        <h3><?php echo $resultado['estacion']; ?></h3>
                        <p class="season-date">Fecha ingresada: <?php echo $resultado['fecha']; ?></p>
                        <p class="season-range">
                            La estación es: <strong><?php echo $resultado['estacion']; ?></strong>
                        </p>
                        <p class="season-period">
                            Del <?php echo formatFecha($resultado['rango']['inicio']); ?> 
                            al <?php echo formatFecha($resultado['rango']['fin']); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="seasons-info">
                <h4>Calendario de Estaciones</h4>
                <div class="seasons-grid">
                    <?php foreach ($estaciones as $estacion => $datos): ?>
                        <div class="season-info-card <?php echo strtolower($estacion); echo ($estacion === $resultado['estacion']) ? ' current' : ''; ?>"
                             style="background-image: url('../imagenes/<?php echo $datos['imagen']; ?>');">
                            <div class="season-mini-overlay">
                                <h5><?php echo $estacion; ?></h5>
                                <p class="season-dates">
                                    <?php echo formatFecha($datos['inicio']); ?> -<br>
                                    <?php echo formatFecha($datos['fin']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('fecha').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) value = value.substring(0, 2) + '/' + value.substring(2);
    if (value.length >= 5) value = value.substring(0, 5) + '/' + value.substring(5, 9);
    e.target.value = value;
});
</script>


<?php include_once '../footer.php'; ?>