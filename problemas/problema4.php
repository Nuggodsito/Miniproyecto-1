<?php
$base_url = '/MINIPROYECTO1';
include_once '../includes/utilities.php';
include_once '../includes/header.php';

$pares = [];
$impares = [];
$suma_pares = 0;
$suma_impares = 0;

// Calcular pares e impares del 1 al 200
for ($i = 1; $i <= 200; $i++) {
    if ($i % 2 == 0) {
        $pares[] = $i;
        $suma_pares += $i;
    } else {
        $impares[] = $i;
        $suma_impares += $i;
    }
}

// Estadísticas
$total_pares = count($pares);
$total_impares = count($impares);
$diferencia = abs($suma_pares - $suma_impares);
?>

<div class="container">
    <div class="problem-header">
        <h1>Problema #4: Pares e Impares 1-200</h1>
        <p>Calcular suma de números pares e impares del 1 al 200</p>
        <?php echo Utilities::generarEnlace($base_url . '/index.php'); ?>
    </div>

    <div class="resultados">
        <h3>Resultados</h3>
        
        <div class="stats-container">
            <div class="stat-row">
                <div class="stat-box even">
                    <h4>Números Pares</h4>
                    <p class="stat-count"><?php echo $total_pares; ?> números</p>
                    <p class="stat-sum">Suma: <?php echo number_format($suma_pares); ?></p>
                    <p class="stat-avg">Promedio: <?php echo number_format($suma_pares / $total_pares, 2); ?></p>
                </div>
                
                <div class="stat-box odd">
                    <h4>Números Impares</h4>
                    <p class="stat-count"><?php echo $total_impares; ?> números</p>
                    <p class="stat-sum">Suma: <?php echo number_format($suma_impares); ?></p>
                    <p class="stat-avg">Promedio: <?php echo number_format($suma_impares / $total_impares, 2); ?></p>
                </div>
            </div>
            
            <div class="comparison">
                <h4>Comparación</h4>
                <div class="comp-grid">
                    <div class="comp-item">
                        <span>Diferencia de sumas:</span>
                        <strong><?php echo number_format($diferencia); ?></strong>
                    </div>
                    <div class="comp-item">
                        <span>Total general:</span>
                        <strong><?php echo number_format($suma_pares + $suma_impares); ?></strong>
                    </div>
                    <div class="comp-item">
                        <span>Relación Pares/Impares:</span>
                        <strong><?php echo number_format($total_pares / $total_impares, 2); ?>:1</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="numbers-preview">
            <div class="numbers-section">
                <h5>Primeros 10 Pares:</h5>
                <div class="numbers-list">
                    <?php for ($i = 0; $i < min(10, count($pares)); $i++): ?>
                        <span class="number-tag even"><?php echo $pares[$i]; ?></span>
                    <?php endfor; ?>
                    <span class="more-numbers">... y <?php echo count($pares) - 10; ?> más</span>
                </div>
            </div>
            
            <div class="numbers-section">
                <h5>Primeros 10 Impares:</h5>
                <div class="numbers-list">
                    <?php for ($i = 0; $i < min(10, count($impares)); $i++): ?>
                        <span class="number-tag odd"><?php echo $impares[$i]; ?></span>
                    <?php endfor; ?>
                    <span class="more-numbers">... y <?php echo count($impares) - 10; ?> más</span>
                </div>
            </div>
        </div>

        <div class="chart-container">
            <h4>Distribución Visual</h4>
            <canvas id="comparisonChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('comparisonChart').getContext('2d');
const comparisonChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Pares', 'Impares'],
        datasets: [{
            label: 'Cantidad de Números',
            data: [<?php echo $total_pares; ?>, <?php echo $total_impares; ?>],
            backgroundColor: ['#6CB2EB', '#E53E3E'],
            borderWidth: 2
        }, {
            label: 'Suma Total',
            data: [<?php echo $suma_pares / 1000; ?>, <?php echo $suma_impares / 1000; ?>],
            backgroundColor: ['#90CDF4', '#FC8181'],
            borderWidth: 2,
            type: 'line',
            yAxisID: 'y1'
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Cantidad de Números' }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                title: { display: true, text: 'Suma (miles)' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});
</script>

<?php include_once '../footer.php'; ?>