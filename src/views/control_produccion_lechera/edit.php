<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';

$cabras = $cabras ?? [];
$control = $control ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producción</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <div class="container">
        <header class="dashboard-header">
            <h1>✏️ Editar producción lechera</h1>
        </header>

        <?php showMessages(); ?>

        <main class="main-content">
            <div class="form-container">
                <form method="POST" action="<?php echo BASE_URL; ?>/control-produccion-lechera/<?php echo $control['id_control_produccion']; ?>/edit">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                    <div class="form-group">
                        <label for="id_cabra">🐐 Cabra</label>
                        <select name="id_cabra" id="id_cabra" required>
                            <option value="">Seleccione una cabra</option>
                            <?php foreach ($cabras as $cabra): ?>
                                <option value="<?php echo e($cabra['id_cabra']); ?>" <?php echo ((int)$control['id_cabra'] === (int)$cabra['id_cabra']) ? 'selected' : ''; ?>>
                                    <?php echo e($cabra['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="turno_ordeño">🕐 Turno de ordeño</label>
                        <select name="turno_ordeño" id="turno_ordeño" required>
                            <option value="">Seleccione el turno</option>
                            <option value="MAÑANA" <?php echo strtoupper($control['turno_ordeño']) === 'MAÑANA' ? 'selected' : ''; ?>>Mañana</option>
                            <option value="TARDE" <?php echo strtoupper($control['turno_ordeño']) === 'TARDE' ? 'selected' : ''; ?>>Tarde</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cantidad_litros">🥛 Cantidad de leche (litros)</label>
                        <input type="number" name="cantidad_litros" id="cantidad_litros" min="0" step="0.01" value="<?php echo e($control['cantidad_litros']); ?>" required>
                    </div>

                    <div class="detail-actions">
                        <a href="<?php echo BASE_URL; ?>/control-produccion-lechera" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
