<?php
$base_url = '/MINIPROYECTO1';
include_once '../includes/utilities.php';
include_once '../includes/header.php';

// Calcular suma del 1 al 1000
$suma = 0;
$proceso = [];

for ($i = 1; $i <= 1000; $i++) {
    $suma += $i;
    // Guardar algunos pasos para mostrar el proceso
    if ($i <= 10 || $i % 100 == 0 || $i >= 990) {
        $proceso[] = [
            'numero' => $i,
            'suma_parcial' => $suma
        ];
    }
}

// Verificación con fórmula
$suma_formula = (1000 * 1001) / 2;
?>

<div class="container">
    <div class="problem-header">
        <h1>Problema #2: Suma del 1 al 1000</h1>
        <p>Calcular la suma de todos los números del 1 al 1000</p>
        <?php echo Utilities::generarEnlace($base_url . '/index.php'); ?>
    </div>

    <div class="resultados">
        <h3>Resultado Final</h3>
        
        <div class="final-result">
            <div class="result-card">
                <h4>Suma por Iteración</h4>
                <p class="result-number"><?php echo number_format($suma); ?></p>
                <p>1 + 2 + 3 + ... + 1000 = <?php echo number_format($suma); ?></p>
            </div>
            
            <div class="result-card">
                <h4>Verificación con Fórmula</h4>
                <p class="result-number"><?php echo number_format($suma_formula); ?></p>
                <p>n(n+1)/2 = 1000×1001/2 = <?php echo number_format($suma_formula); ?></p>
            </div>
        </div>

        <div class="process">
            <h4>Proceso de Cálculo (Algunos pasos)</h4>
            <div class="process-steps">
                <?php foreach ($proceso as $paso): ?>
                    <div class="process-step">
                        <span class="step-number">n=<?php echo $paso['numero']; ?></span>
                        <span class="step-result">Suma: <?php echo number_format($paso['suma_parcial']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../footer.php'; ?>