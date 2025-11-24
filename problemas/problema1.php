<?php
$base_url = '/MINIPROYECTO1';
include_once '../includes/utilities.php';
include_once '../includes/header.php';

$resultados = [];
$numeros = [];

if ($_POST) {
    for ($i = 1; $i <= 5; $i++) {
        $num = Utilities::sanitizarNumero($_POST["numero$i"] ?? '');
        if ($num !== '' && Utilities::validarDecimalPositivo($num)) {
            $numeros[] = (float)$num;
        }
    }
    
    if (count($numeros) === 5) {
        $resultados = [
            'numeros' => $numeros,
            'media' => array_sum($numeros) / 5,
            'desviacion' => Utilities::calcularDesviacionEstandar($numeros),
            'minimo' => min($numeros),
            'maximo' => max($numeros)
        ];
    } else {
        $error = "Debe ingresar exactamente 5 números positivos.";
    }
}
?>

<div class="container">
    <div class="problem-header">
        <h1>Problema #1: Calculadora de Datos Estadísticos</h1>
        <p>Calcular media, desviación estándar, mínimo y máximo de 5 números</p>
        <?php echo Utilities::generarEnlace($base_url . '/index.php'); ?>
    </div>

    <div class="form-container">
        <form method="POST">
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <div class="form-group">
                <label>Número <?php echo $i; ?>:</label>
                <input type="number" name="numero<?php echo $i; ?>" step="any" min="0" required>
            </div>
            <?php endfor; ?>
            <button type="submit" class="btn">Calcular Estadísticas</button>
        </form>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($resultados)): ?>
    <div class="resultados">
        <h3>Resultados Estadísticos</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <h4>Números</h4>
                <p><?php echo implode(', ', $resultados['numeros']); ?></p>
            </div>
            <div class="stat-card">
                <h4>Media</h4>
                <p><?php echo number_format($resultados['media'], 2); ?></p>
            </div>
            <div class="stat-card">
                <h4>Desviación Estándar</h4>
                <p><?php echo number_format($resultados['desviacion'], 2); ?></p>
            </div>
            <div class="stat-card">
                <h4>Mínimo</h4>
                <p><?php echo number_format($resultados['minimo'], 2); ?></p>
            </div>
            <div class="stat-card">
                <h4>Máximo</h4>
                <p><?php echo number_format($resultados['maximo'], 2); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include_once '../footer.php'; ?>