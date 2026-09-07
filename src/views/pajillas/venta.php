<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$multiplicador = $pajilla['tamano'] === '0.25 ml' ? 2 : 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vender Dosis - <?php echo e($pajilla['nombre_ejemplar']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>💰 Vender Dosis</h1>
        </header>

        <main class="main-content">
            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <h4>Por favor, corrige los siguientes errores:</h4>
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" action="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla']; ?>/venta" class="cabra-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="id_pajilla" value="<?php echo $pajilla['id_pajilla']; ?>">

                    <div class="form-section">
                        <h3>📋 Información de la Pajilla</h3>
                        <div class="info-display">
                            <p><strong>Ejemplar:</strong> <?php echo e($pajilla['nombre_ejemplar']); ?></p>
                            <p><strong>Registro:</strong> <?php echo e($pajilla['registro_ejemplar'] ?: 'No registrado'); ?></p>
                            <p><strong>Canastilla:</strong> <?php echo e($pajilla['canastilla_nombre'] ?: '-'); ?></p>
                            <p><strong>Tamaño:</strong> <?php echo e($pajilla['tamano']); ?> (<?php echo $multiplicador; ?> dosis por pajilla)</p>
                            <p><strong>Dosis Disponibles:</strong> <span style="color: #2e7d32; font-weight: bold;"><?php echo e($pajilla['dosis_disponibles']); ?></span></p>
                            <p><strong>Pájillas Disponibles:</strong> <?php echo e($pajilla['cantidad']); ?></p>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>💵 Vender Dosis</h3>

                        <div class="form-group">
                            <label for="cantidad_dosis">Cantidad de Dosis a Vender *</label>
                            <input type="number" id="cantidad_dosis" name="cantidad_dosis" min="1" max="<?php echo e($pajilla['dosis_disponibles']); ?>" value="1" required>
                            <small class="form-help">
                                Máximo disponible: <?php echo e($pajilla['dosis_disponibles']); ?> dosis
                                <?php if ($pajilla['tamano'] === '0.25 ml'): ?>
                                    | Cada pajilla de 0.25 ml contiene 2 dosis
                                <?php else: ?>
                                    | Cada pajilla de 0.50 ml contiene 1 dosis
                                <?php endif; ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3"
                                      placeholder="Ej: Cliente, motivo de venta, etc."></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Confirmar Venta</button>
                        <a href="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla']; ?>" class="btn btn-secondary">❌ Cancelar</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    const cantidadInput = document.getElementById('cantidad_dosis');
    const maxDosis = <?php echo e($pajilla['dosis_disponibles']); ?>;

    cantidadInput.addEventListener('input', function() {
        if (parseInt(this.value) > maxDosis) {
            this.value = maxDosis;
        }
        if (parseInt(this.value) < 1) {
            this.value = 1;
        }
    });

    document.querySelector('.cabra-form').addEventListener('submit', function(e) {
        const cantidad = parseInt(cantidadInput.value);
        if (!cantidad || cantidad <= 0) {
            alert('Debe ingresar una cantidad válida');
            e.preventDefault();
            return;
        }
        if (cantidad > maxDosis) {
            alert('No se pueden vender ' + cantidad + ' dosis. Solo hay ' + maxDosis + ' disponibles.');
            e.preventDefault();
            return;
        }
    });
    </script>

    <?php
    if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }
    ?>
</body>
</html>