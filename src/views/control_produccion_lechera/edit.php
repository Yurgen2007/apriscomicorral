<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';

$cabras = $cabras ?? [];
$control = $control ?? [];
$lactancias = $lactancias ?? [];
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
                        <label for="lactancia_actual">🐐 Lactancia asociada</label>
                        <input type="text" id="lactancia_actual"
                               value="Lactancia #<?= (int)($control['numero_lactancia'] ?? $control['id_lactancia']) ?>"
                               readonly>
                        <small>La lactancia pertenece al período original y no se puede cambiar desde la edición.</small>
                    </div>

                    <input type="hidden" name="id_cabra" value="<?= (int)$control['id_cabra'] ?>">

                    <div class="form-group">
                        <label for="fecha_registro">📅 Fecha del registro</label>
                        <input type="date" name="fecha_registro" id="fecha_registro" value="<?= e(substr($control['fecha_registro'], 0, 10)) ?>" required>
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
