<?php
include_once '../includes/utilities.php';
include_once '../includes/header.php';

$resultados = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = Utilities::sanitizarNumero($_POST['n'] ?? '');
    
    if (Utilities::validarEnteroPositivo($n) && $n > 0) {
        $n = (int)$n;
        $resultados = [];
        
        for ($i = 1; $i <= $n; $i++) {
            $resultados[] = [
                'multiplicador' => $i,
                'resultado' => 4 * $i,
                'formula' => "4 × $i = " . (4 * $i)
            ];
        }
    } else {
        $error = "❌ Por favor ingrese un número entero positivo válido.";
    }
}
?>

<div class="container">
    <div class="problem-header">
        <h1> Problema #3: Múltiplos de 4</h1>
        <p>Generar los N primeros múltiplos de 4</p>
        <?php echo Utilities::generarEnlace('../index.php'); ?>
    </div>

    <div class="form-container">
        <form method="POST" action="">
            <div class="form-group">
                <label for="n">¿Cuántos múltiplos de 4 deseas generar?</label>
                <input type="number" id="n" name="n" min="1" max="100" required 
                       placeholder="Ej: 10">
            </div>
            
            <button type="submit" class="btn"> Generar Múltiplos</button>
        </form>
    </div>

    <?php if ($error): ?>
        <div class="error-message">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($resultados)): ?>
        <div class="resultados">
            <h3> Primeros <?php echo count($resultados); ?> múltiplos de 4</h3>
            
            <div class="multiples-grid">
                <?php foreach ($resultados as $multiplo): ?>
                    <div class="multiple-card">
                        <span class="formula"><?php echo $multiplo['formula']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="summary">
                <p><strong>Último múltiplo generado:</strong> 4 × <?php echo count($resultados); ?> = <?php echo 4 * count($resultados); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include_once '../footer.php'; ?>