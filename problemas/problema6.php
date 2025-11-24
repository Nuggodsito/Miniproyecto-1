<?php
$base_url = '/MINIPROYECTO1';
include_once '../includes/utilities.php';
include_once '../includes/header.php';

$areas = [
    'Ginecología' => ['porcentaje' => 40, 'color' => '#8B5FBF'],
    'Traumatología' => ['porcentaje' => 35, 'color' => '#6CB2EB'],
    'Pediatría' => ['porcentaje' => 25, 'color' => '#E53E3E']
];

$presupuesto_total = 0;
$distribucion = [];
$error = '';

if ($_POST) {
    $presupuesto = Utilities::sanitizarNumero($_POST['presupuesto'] ?? '');
    
    if (Utilities::validarDecimalPositivo($presupuesto) && $presupuesto > 0) {
        $presupuesto_total = (float)$presupuesto;
        
        foreach ($areas as $area => $datos) {
            $distribucion[$area] = [
                'porcentaje' => $datos['porcentaje'],
                'monto' => ($presupuesto_total * $datos['porcentaje']) / 100,
                'color' => $datos['color']
            ];
        }
    } else {
        $error = "Por favor ingrese un presupuesto válido mayor a 0.";
    }
}
?>

<div class="container">
    <div class="problem-header">
        <h1>Problema #6: Presupuesto Hospital</h1>
        <p>Distribuir presupuesto: Ginecología (40%), Traumatología (35%), Pediatría (25%)</p>
        <?php echo Utilities::generarEnlace($base_url . '/index.php'); ?>
    </div>

    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label>Presupuesto Anual Total ($):</label>
                <input type="number" name="presupuesto" step="0.01" min="0.01" required placeholder="Ej: 20000.00">
            </div>
            <button type="submit" class="btn">Calcular Distribución</button>
        </form>
    </div>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($distribucion)): ?>
    <div class="resultados">
        <h3>Distribución del Presupuesto</h3>
        
        <div class="budget-summary">
            <div class="total-budget">
                <h4>Presupuesto Total</h4>
                <p class="budget-amount">$<?php echo number_format($presupuesto_total, 2); ?></p>
            </div>
        </div>

        <div class="distribution-section">
            <h4>Distribución por Áreas</h4>
            <div class="areas-grid">
                <?php foreach ($distribucion as $area => $datos): ?>
                <div class="area-card" style="border-left: 5px solid <?php echo $datos['color']; ?>">
                    <h5><?php echo $area; ?></h5>
                    <div class="area-details">
                        <p class="area-percentage"><?php echo $datos['porcentaje']; ?>%</p>
                        <p class="area-amount">$<?php echo number_format($datos['monto'], 2); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-row">
                <div class="chart-container">
                    <h5>Distribución Porcentual</h5>
                    <canvas id="presupuestoChart" width="400" height="300"></canvas>
                </div>
                <div class="chart-container">
                    <h5>Montos por Área</h5>
                    <canvas id="montosChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="table-container">
            <h4>Detalle Presupuestario</h4>
            <table class="budget-table">
                <thead>
                    <tr>
                        <th>Área</th>
                        <th>Porcentaje</th>
                        <th>Monto Asignado</th>
                        <th>Proporción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($distribucion as $area => $datos): ?>
                    <tr>
                        <td><strong><?php echo $area; ?></strong></td>
                        <td><?php echo $datos['porcentaje']; ?>%</td>
                        <td>$<?php echo number_format($datos['monto'], 2); ?></td>
                        <td>
                            <div class="proportion-bar">
                                <div class="proportion-fill" style="width: <?php echo $datos['porcentaje']; ?>%; background: <?php echo $datos['color']; ?>"></div>
                                <span class="proportion-text"><?php echo $datos['porcentaje']; ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td><strong>TOTAL</strong></td>
                        <td><strong>100%</strong></td>
                        <td><strong>$<?php echo number_format($presupuesto_total, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
<?php if (!empty($distribucion)): ?>
const ctxTorta = document.getElementById('presupuestoChart').getContext('2d');
const presupuestoChart = new Chart(ctxTorta, {
    type: 'pie',
    data: {
        labels: ['Ginecología', 'Traumatología', 'Pediatría'],
        datasets: [{
            data: [40, 35, 25],
            backgroundColor: ['#8B5FBF', '#6CB2EB', '#E53E3E'],
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
                        const value = context.raw;
                        const amount = (<?php echo $presupuesto_total; ?> * value / 100).toFixed(2);
                        return `${value}% - $${amount}`;
                    }
                }
            }
        }
    }
});

const ctxBarras = document.getElementById('montosChart').getContext('2d');
const montosChart = new Chart(ctxBarras, {
    type: 'bar',
    data: {
        labels: ['Ginecología', 'Traumatología', 'Pediatría'],
        datasets: [{
            label: 'Monto Asignado ($)',
            data: [
                <?php echo $distribucion['Ginecología']['monto']; ?>,
                <?php echo $distribucion['Traumatología']['monto']; ?>,
                <?php echo $distribucion['Pediatría']['monto']; ?>
            ],
            backgroundColor: ['#8B5FBF', '#6CB2EB', '#E53E3E'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return '$' + context.raw.toLocaleString('en-US', {minimumFractionDigits: 2});
                    }
                }
            }
        }
    }
});
<?php endif; ?>
</script>


<?php include_once '../footer.php'; ?>