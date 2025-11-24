<?php
$base_url = '/MINIPROYECTO1';
include_once '../includes/utilities.php';
include_once '../includes/header.php';

$resultados = [];
$notas = [];
$error = '';

if ($_POST) {
    $cantidad = Utilities::sanitizarNumero($_POST['cantidad'] ?? '');
    
    if (Utilities::validarEnteroPositivo($cantidad) && $cantidad > 0) {
        $cantidad = (int)$cantidad;
        $notas = [];
        $valido = true;
        
        for ($i = 1; $i <= $cantidad; $i++) {
            $nota = Utilities::sanitizarNumero($_POST["nota$i"] ?? '');
            
            // PERMITE NOTA 0 - solo valida que sea número entre 0-100
            if (!is_numeric($nota) || $nota < 0 || $nota > 100) {
                $error = "Por favor ingrese notas válidas entre 0 y 100.";
                $valido = false;
                break;
            }
            $notas[] = (float)$nota;
        }
        
        if ($valido && count($notas) === $cantidad) {
            $promedio = array_sum($notas) / $cantidad;
            $desviacion = Utilities::calcularDesviacionEstandar($notas);
            
            if ($promedio >= 90) $categoria = "Excelente";
            elseif ($promedio >= 80) $categoria = "Muy Bueno";
            elseif ($promedio >= 70) $categoria = "Bueno";
            elseif ($promedio >= 60) $categoria = "Regular";
            else $categoria = "Necesita Mejorar";
            
            $resultados = [
                'notas' => $notas,
                'promedio' => $promedio,
                'desviacion' => $desviacion,
                'minima' => min($notas),
                'maxima' => max($notas),
                'categoria' => $categoria,
                'cantidad' => $cantidad
            ];
        }
    } else {
        $error = "Por favor ingrese una cantidad válida de notas.";
    }
}
?>

<div class="container">
    <div class="problem-header">
        <h1>Problema #7: Calculadora de Notas</h1>
        <p>Calcular promedio, desviación estándar, nota mínima y máxima</p>
        <?php echo Utilities::generarEnlace($base_url . '/index.php'); ?>
    </div>

    <div class="form-container">
        <form method="POST" id="notasForm">
            <div class="form-group">
                <label>Cantidad de notas:</label>
                <input type="number" id="cantidad" name="cantidad" min="1" max="20" required onchange="generarCamposNotas()">
            </div>
            <div id="camposNotas"></div>
            <button type="submit" class="btn" id="btnCalcular" style="display: none;">Calcular Estadísticas</button>
        </form>
    </div>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($resultados)): ?>
    <div class="resultados">
        <h3>Resultados Estadísticos</h3>
        
        <div class="stats-summary">
            <div class="summary-card">
                <h4>Promedio General</h4>
                <p class="stat-value <?php echo $resultados['promedio'] >= 70 ? 'good' : 'bad'; ?>">
                    <?php echo number_format($resultados['promedio'], 2); ?>
                </p>
                <p class="stat-category"><?php echo $resultados['categoria']; ?></p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h5>Desviación Estándar</h5>
                    <p class="stat-number"><?php echo number_format($resultados['desviacion'], 2); ?></p>
                </div>
                <div class="stat-card">
                    <h5>Nota Mínima</h5>
                    <p class="stat-number"><?php echo number_format($resultados['minima'], 2); ?></p>
                </div>
                <div class="stat-card">
                    <h5>Nota Máxima</h5>
                    <p class="stat-number"><?php echo number_format($resultados['maxima'], 2); ?></p>
                </div>
                <div class="stat-card">
                    <h5>Total Notas</h5>
                    <p class="stat-number"><?php echo $resultados['cantidad']; ?></p>
                </div>
            </div>
        </div>

        <div class="notes-list">
            <h4>Notas Ingresadas</h4>
            <div class="notes-grid">
                <?php foreach ($resultados['notas'] as $index => $nota): ?>
                    <div class="note-item <?php echo $nota >= 70 ? 'approved' : 'failed'; ?>">
                        <span class="note-index">Nota <?php echo $index + 1; ?></span>
                        <span class="note-value"><?php echo number_format($nota, 2); ?></span>
                        <span class="note-status"><?php echo $nota >= 70 ? 'Aprobado' : 'Reprobado'; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function generarCamposNotas() {
    const cantidad = parseInt(document.getElementById('cantidad').value);
    const container = document.getElementById('camposNotas');
    const btnCalcular = document.getElementById('btnCalcular');
    
    container.innerHTML = '';
    
    if (cantidad > 0 && cantidad <= 20) {
        for (let i = 1; i <= cantidad; i++) {
            const div = document.createElement('div');
            div.className = 'form-group';
            div.innerHTML = `
                <label>Nota ${i}:</label>
                <input type="number" name="nota${i}" step="0.01" min="0" max="100" required placeholder="0-100">
            `;
            container.appendChild(div);
        }
        btnCalcular.style.display = 'block';
    } else {
        btnCalcular.style.display = 'none';
    }
}
</script>

<?php include_once '../footer.php'; ?>