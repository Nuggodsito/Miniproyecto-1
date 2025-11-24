<?php
$base_url = '/MINIPROYECTO1';
include_once '../includes/utilities.php';
include_once '../includes/header.php';

$categorias = [
    'niño' => ['min' => 0, 'max' => 12, 'contador' => 0, 'edades' => []],
    'adolescente' => ['min' => 13, 'max' => 17, 'contador' => 0, 'edades' => []],
    'adulto' => ['min' => 18, 'max' => 64, 'contador' => 0, 'edades' => []],
    'adulto_mayor' => ['min' => 65, 'max' => 150, 'contador' => 0, 'edades' => []]
];

$estadisticas = [];
$error = '';

if ($_POST) {
    $edades = [];
    $valido = true;
    
    for ($i = 1; $i <= 5; $i++) {
        $edad = Utilities::sanitizarNumero($_POST["edad$i"] ?? '');
        
        if (!Utilities::validarEnteroPositivo($edad) || $edad > 150) {
            $error = "Por favor ingrese 5 edades válidas (0-150 años).";
            $valido = false;
            break;
        }
        $edades[] = (int)$edad;
    }
    
    if ($valido && count($edades) === 5) {
        foreach ($edades as $edad) {
            foreach ($categorias as $categoria => $rango) {
                if ($edad >= $rango['min'] && $edad <= $rango['max']) {
                    $categorias[$categoria]['contador']++;
                    $categorias[$categoria]['edades'][] = $edad;
                    break;
                }
            }
        }
        
        $estadisticas['total'] = count($edades);
        $estadisticas['promedio'] = array_sum($edades) / count($edades);
        $estadisticas['minima'] = min($edades);
        $estadisticas['maxima'] = max($edades);
        $estadisticas['moda'] = array_count_values($edades);
        arsort($estadisticas['moda']);
        $estadisticas['moda'] = key($estadisticas['moda']);
    }
}
?>

<div class="container">
    <div class="problem-header">
        <h1>Problema #5: Clasificación de Edades</h1>
        <p>Clasificar 5 edades: Niño (0-12), Adolescente (13-17), Adulto (18-64), Adulto Mayor (65+)</p>
        <?php echo Utilities::generarEnlace($base_url . '/index.php'); ?>
    </div>

    <div class="form-container">
        <form method="POST">
            <div class="form-row">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="form-group">
                    <label>Edad Persona <?php echo $i; ?>:</label>
                    <input type="number" name="edad<?php echo $i; ?>" min="0" max="150" required placeholder="0-150">
                </div>
                <?php endfor; ?>
            </div>
            <button type="submit" class="btn">Clasificar Edades</button>
        </form>
    </div>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($estadisticas)): ?>
    <div class="resultados">
        <h3>Resultados de Clasificación</h3>
        
        <div class="general-stats">
            <h4>Estadísticas Generales</h4>
            <div class="stats-grid">
                <div class="stat-card">
                    <h5>Total Personas</h5>
                    <p class="stat-number"><?php echo $estadisticas['total']; ?></p>
                </div>
                <div class="stat-card">
                    <h5>Edad Promedio</h5>
                    <p class="stat-number"><?php echo number_format($estadisticas['promedio'], 1); ?> años</p>
                </div>
                <div class="stat-card">
                    <h5>Edad Mínima</h5>
                    <p class="stat-number"><?php echo $estadisticas['minima']; ?> años</p>
                </div>
                <div class="stat-card">
                    <h5>Edad Máxima</h5>
                    <p class="stat-number"><?php echo $estadisticas['maxima']; ?> años</p>
                </div>
                <div class="stat-card">
                    <h5>Moda</h5>
                    <p class="stat-number"><?php echo $estadisticas['moda']; ?> años</p>
                </div>
            </div>
        </div>

        <div class="distribution">
            <h4>Distribución por Categorías</h4>
            <div class="categories-grid">
                <?php foreach ($categorias as $categoria => $datos): ?>
                <div class="category-card <?php echo $categoria; ?>">
                    <h5><?php echo ucfirst(str_replace('_', ' ', $categoria)); ?></h5>
                    <p class="category-count"><?php echo $datos['contador']; ?> personas</p>
                    <p class="category-range"><?php echo $datos['min']; ?>-<?php echo $datos['max']; ?> años</p>
                    <?php if ($datos['contador'] > 0): ?>
                    <p class="category-ages">Edades: <?php echo implode(', ', $datos['edades']); ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-row">
                <div class="chart-container">
                    <h5>Distribución por Categoría</h5>
                    <canvas id="categoriasChart" width="400" height="300"></canvas>
                </div>
                <div class="chart-container">
                    <h5>Porcentajes</h5>
                    <canvas id="porcentajesChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
<?php if (!empty($estadisticas)): ?>
const ctxBarras = document.getElementById('categoriasChart').getContext('2d');
const categoriasChart = new Chart(ctxBarras, {
    type: 'bar',
    data: {
        labels: ['Niño', 'Adolescente', 'Adulto', 'Adulto Mayor'],
        datasets: [{
            label: 'Cantidad de Personas',
            data: [
                <?php echo $categorias['niño']['contador']; ?>,
                <?php echo $categorias['adolescente']['contador']; ?>,
                <?php echo $categorias['adulto']['contador']; ?>,
                <?php echo $categorias['adulto_mayor']['contador']; ?>
            ],
            backgroundColor: ['#6CB2EB', '#E53E3E', '#38A169', '#D69E2E'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

const ctxTorta = document.getElementById('porcentajesChart').getContext('2d');
const porcentajesChart = new Chart(ctxTorta, {
    type: 'pie',
    data: {
        labels: ['Niño', 'Adolescente', 'Adulto', 'Adulto Mayor'],
        datasets: [{
            data: [
                <?php echo $categorias['niño']['contador']; ?>,
                <?php echo $categorias['adolescente']['contador']; ?>,
                <?php echo $categorias['adulto']['contador']; ?>,
                <?php echo $categorias['adulto_mayor']['contador']; ?>
            ],
            backgroundColor: ['#6CB2EB', '#E53E3E', '#38A169', '#D69E2E'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = <?php echo $estadisticas['total']; ?>;
                        const value = context.raw;
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${value} personas (${percentage}%)`;
                    }
                }
            }
        }
    }
});
<?php endif; ?>
</script>

<?php include_once '../footer.php'; ?>