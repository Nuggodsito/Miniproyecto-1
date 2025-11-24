<?php
$base_url = '/MINIPROYECTO1';
include_once '../includes/utilities.php';
include_once '../includes/header.php';

// Vendedores fijos
$vendedores = [
    1 => ['nombre' => 'Luis', 'apellido' => 'Calderón'],
    2 => ['nombre' => 'Luis', 'apellido' => 'Ortega'],
    3 => ['nombre' => 'María', 'apellido' => 'González'],
    4 => ['nombre' => 'Carlos', 'apellido' => 'Rodríguez']
];

$error = '';
$resultados = [];
$ventas = [];

// Inicializar matriz 5x4 con ceros
for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 4; $j++) {
        $ventas[$i][$j] = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dias = (int)$_POST['num_dias'];
    
    for ($dia = 1; $dia <= $dias; $dia++) {
        for ($vendedor_num = 1; $vendedor_num <= 4; $vendedor_num++) {
            $num_notas = (int)$_POST["notas_dia{$dia}_vend{$vendedor_num}"];
            
            for ($nota = 1; $nota <= $num_notas; $nota++) {
                $producto = (int)$_POST["dia{$dia}_vend{$vendedor_num}_nota{$nota}_producto"];
                $valor = (float)$_POST["dia{$dia}_vend{$vendedor_num}_nota{$nota}_valor"];
                
                if ($producto >= 1 && $producto <= 5 && $valor > 0) {
                    $ventas[$producto - 1][$vendedor_num - 1] += $valor;
                }
            }
        }
    }

    // Calcular totales
    $totalesFila = [];
    $totalesColumna = [];
    
    for ($i = 0; $i < 5; $i++) {
        $totalesFila[$i] = array_sum($ventas[$i]);
    }
    
    for ($j = 0; $j < 4; $j++) {
        $suma = 0;
        for ($i = 0; $i < 5; $i++) {
            $suma += $ventas[$i][$j];
        }
        $totalesColumna[$j] = $suma;
    }

    $granTotal = array_sum($totalesFila);

    $resultados = [
        'ventas' => $ventas,
        'totalesFila' => $totalesFila,
        'totalesColumna' => $totalesColumna,
        'granTotal' => $granTotal
    ];
}
?>

<div class="container">
    <div class="problem-header">
        <h1>Problema #10: Sistema de Ventas</h1>
        <p>Registro y resumen de ventas por vendedor y producto</p>
        <?php echo Utilities::generarEnlace('../index.php'); ?>
    </div>

    <div class="form-container">
        <form method="POST" action="">
            <div class="input-group">
                <label>Número de días a registrar (1-31):</label>
                <input type="number" name="num_dias" min="1" max="31" value="7" required>
            </div>

            <div id="dias-container">
                <!-- Los días se generarán aquí con JavaScript -->
            </div>

            <button type="submit" class="btn">Procesar Ventas</button>
        </form>
    </div>

    <?php if (!empty($resultados)): ?>
        <div class="resultados">
            <h3>Resumen de Ventas</h3>
            
            <table class="ventas-tabla">
                <thead>
                    <tr>
                        <th>Producto \ Vendedor</th>
                        <?php for ($j = 0; $j < 4; $j++): ?>
                            <th><?php echo $vendedores[$j + 1]['nombre'] . ' ' . $vendedores[$j + 1]['apellido']; ?></th>
                        <?php endfor; ?>
                        <th>Total Producto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <tr>
                            <td><strong>Producto <?php echo $i + 1; ?></strong></td>
                            <?php for ($j = 0; $j < 4; $j++): ?>
                                <td>$<?php echo number_format($resultados['ventas'][$i][$j], 2); ?></td>
                            <?php endfor; ?>
                            <td class="total"><strong>$<?php echo number_format($resultados['totalesFila'][$i], 2); ?></strong></td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total Vendedor</th>
                        <?php for ($j = 0; $j < 4; $j++): ?>
                            <th class="total">$<?php echo number_format($resultados['totalesColumna'][$j], 2); ?></th>
                        <?php endfor; ?>
                        <th class="gran-total">$<?php echo number_format($resultados['granTotal'], 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const numDiasInput = document.querySelector('input[name="num_dias"]');
    const diasContainer = document.getElementById('dias-container');
    
    function generarDias() {
        const numDias = parseInt(numDiasInput.value);
        diasContainer.innerHTML = '';
        
        for (let dia = 1; dia <= numDias; dia++) {
            const diaDiv = document.createElement('div');
            diaDiv.className = 'dia-card';
            diaDiv.innerHTML = `
                <h4>Día ${dia}</h4>
                ${[1,2,3,4].map(vendedor => `
                    <div class="vendedor-dia">
                        <label>Vendedor ${vendedor}</label>
                        <select name="notas_dia${dia}_vend${vendedor}" class="notas-selector">
                            <option value="0">0 notas</option>
                            <option value="1">1 nota</option>
                            <option value="2">2 notas</option>
                            <option value="3">3 notas</option>
                            <option value="4">4 notas</option>
                            <option value="5">5 notas</option>
                        </select>
                        <div class="notas-container">
                            ${[1,2,3,4,5].map(nota => `
                                <div class="nota-fields" style="display: none;">
                                    <select name="dia${dia}_vend${vendedor}_nota${nota}_producto">
                                        <option value="">Producto</option>
                                        <option value="1">Producto 1</option>
                                        <option value="2">Producto 2</option>
                                        <option value="3">Producto 3</option>
                                        <option value="4">Producto 4</option>
                                        <option value="5">Producto 5</option>
                                    </select>
                                    <input type="number" step="0.01" min="0.01" name="dia${dia}_vend${vendedor}_nota${nota}_valor" placeholder="Valor $">
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `).join('')}
            `;
            diasContainer.appendChild(diaDiv);
        }
        
        // Agregar event listeners
        document.querySelectorAll('.notas-selector').forEach(select => {
            select.addEventListener('change', function() {
                const numNotas = parseInt(this.value);
                const container = this.closest('.vendedor-dia').querySelector('.notas-container');
                const notaFields = container.querySelectorAll('.nota-fields');
                
                notaFields.forEach((field, index) => {
                    field.style.display = index < numNotas ? 'flex' : 'none';
                });
            });
        });
    }
    
    numDiasInput.addEventListener('change', generarDias);
    generarDias();
});
</script>

<?php include_once '../footer.php'; ?>