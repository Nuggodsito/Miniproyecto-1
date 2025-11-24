<?php
$base_url = '/MINIPROYECTO1';
include_once '../includes/utilities.php';
include_once '../includes/header.php';

$resultados = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = Utilities::sanitizarNumero($_POST['numero'] ?? '');
    
    if (Utilities::validarEnteroPositivo($numero) && $numero >= 1 && $numero <= 9) {
        $numero = (int)$numero;
        $resultados = [];
        
        // Calcular las primeras 15 potencias
        for ($exponente = 1; $exponente <= 15; $exponente++) {
            $potencia = pow($numero, $exponente);
            $resultados[] = [
                'exponente' => $exponente,
                'potencia' => $potencia,
                'formula' => "$numero<sup>$exponente</sup> = " . number_format($potencia)
            ];
        }
    } else {
        $error = "Por favor ingrese un número entero entre 1 y 9.";
    }
}
?>

<div class="container">
    <div class="problem-header">
        <h1>Problema #9: Potencia de Números</h1>
        <p>Generar las primeras 15 potencias de un número del 1 al 9</p>
        <?php echo Utilities::generarEnlace('../index.php'); ?>
    </div>

    <div class="form-container">
        <form method="POST" action="">
            <div class="form-group">
                <label for="numero">Ingrese un número (1-9):</label>
                <input type="number" id="numero" name="numero" min="1" max="9" required 
                       placeholder="Ej: 4">
            </div>
            
            <button type="submit" class="btn">Calcular Potencias</button>
        </form>
    </div>

    <?php if ($error): ?>
        <div class="error-message">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($resultados)): ?>
        <div class="resultados">
            <h3>Primeras 15 Potencias de <?php echo $numero; ?></h3>
            
            <div class="powers-grid">
                <?php foreach ($resultados as $potencia): ?>
                    <div class="power-card">
                        <div class="power-formula">
                            <?php echo $potencia['formula']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include_once '../footer.php'; ?>