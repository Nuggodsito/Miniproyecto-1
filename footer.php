<?php
// Función para obtener fecha en español
function obtenerFechaEnEspanol() {
    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    
    $fecha = getdate();
    return $dias[$fecha['wday']] . ', ' . $fecha['mday'] . ' de ' . $meses[$fecha['mon']-1] . ' de ' . $fecha['year'];
}

$base_url = '/MINIPROYECTO1';
?>
        </div>
        
        <footer class="footer">
            <div class="container">
                <p> <?php echo obtenerFechaEnEspanol(); ?></p>
                <p>Universidad Tecnológica de Panamá - Ingeniería Web</p>
                <p>Desarrollado por: Luis Calderón & Luis Ortega</p>
            </div>
        </footer>
    </div>
</body>
</html>