<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';

$cabras = $cabras ?? [];
$lactancias = $lactancias ?? [];

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar Producción Lechera</title>

    <link rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/style.css">

    <style>
        .production-form-intro {
            margin-bottom: 20px;
            padding: 16px;
            border: 1px solid #ead7bf;
            border-radius: 12px;
            background: #fff8ed;
            color: #5c3e20;
        }
        .production-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .production-form-grid .form-group { margin-bottom: 0; }
        .production-form-grid .form-group.full-width { grid-column: 1 / -1; }
        .production-status {
            min-height: 42px;
            font-weight: 700;
        }
        .production-status.is-valid { color: #287a45; }
        .production-status.is-invalid { color: #a33d2d; }
        @media (max-width: 700px) {
            .production-form-grid { grid-template-columns: 1fr; }
            .production-form-grid .form-group.full-width { grid-column: auto; }
        }
    </style>

</head>



<body>

    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <div class="container">

        <header class="dashboard-header">

            <h1>🥛 Registrar Producción Lechera</h1>
            <a
                href="<?php echo BASE_URL; ?>/control-produccion-lechera"
                class="btn btn-secondary">
                ↩️ regresar
            </a>

        </header>

        <main class="main-content">

            <?php if (isset($_SESSION['error'])): ?>

                <div class="alert alert-error">

                    <?php
                    echo e($_SESSION['error']);
                    unset($_SESSION['error']);
                    ?>

                </div>

            <?php endif; ?>


            <div class="form-container">
                <div class="production-form-intro">
                    <strong>Regla del registro:</strong>
                    solo se puede registrar leche cuando la condición sanitaria vigente de la cabra sea
                    <strong>LACTANTE</strong>. Si todavía no existe un parto o lactancia asociada, el registro se conserva sin vincular para revisión.
                </div>

                <form
                    method="POST"
                    action="<?php echo BASE_URL; ?>/control-produccion-lechera/create">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php echo generateCSRFToken(); ?>">

                    <div class="production-form-grid">
                    <div class="form-group full-width">
                        <label for="id_cabra">🐐 Cabra</label>
                        <select name="id_cabra" id="id_cabra" required>
                            <option value="">Seleccione una cabra</option>
                            <?php foreach ($cabras as $cabra): ?>
                                <?php if (strtoupper(trim($cabra['sexo'] ?? '')) === 'HEMBRA'): ?>
                                    <option value="<?= (int)$cabra['id_cabra'] ?>"><?= e($cabra['nombre']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_registro">📅 Fecha del registro</label>
                        <input type="date" name="fecha_registro" id="fecha_registro"
                               value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="condicion_sanitaria">🩺 Condición sanitaria vigente</label>
                        <input class="production-status" type="text" id="condicion_sanitaria" value="Seleccione una cabra" readonly>
                        <small>Se consulta desde Control Sanitario; no se almacena una copia en Producción.</small>
                    </div>

                    <div class="form-group">
                        <label for="turno_ordeño">🕐 Turno de ordeño</label>
                        <select name="turno_ordeño" id="turno_ordeño" required>
                            <option value="">Seleccione el turno</option>
                            <option value="MAÑANA">🌅 Mañana</option>
                            <option value="TARDE">🌇 Tarde</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cantidad_litros">🥛 Cantidad de leche (litros)</label>
                        <input type="number" name="cantidad_litros" id="cantidad_litros"
                               min="0" step="0.01" placeholder="Ejemplo: 2.50" required>
                    </div>
                    </div>
                    <div class="detail-actions">
                        <button
                            type="submit"
                            class="btn btn-primary">
                            💾 Guardar Producción
                        </button>

                    </div>

                </form>

            </div>

        </main>

    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cabra = document.querySelector('#id_cabra');
            const fecha = document.querySelector('#fecha_registro');
            const condicion = document.querySelector('#condicion_sanitaria');
            const submit = document.querySelector('button[type="submit"]');
            submit.disabled = true;

            function actualizarCondicion() {
                if (!cabra.value || !fecha.value) {
                    condicion.value = 'Seleccione cabra y fecha';
                    submit.disabled = true;
                    return;
                }
                fetch('<?php echo BASE_URL; ?>/control-produccion-lechera/condicion-sanitaria?id_cabra=' + encodeURIComponent(cabra.value) + '&fecha=' + encodeURIComponent(fecha.value))
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        const estado = data.condicion_especial || '';
                        condicion.value = estado || 'Sin control sanitario para esa fecha';
                        const esLactante = estado.trim().toUpperCase() === 'LACTANTE';
                        condicion.classList.toggle('is-valid', esLactante);
                        condicion.classList.toggle('is-invalid', !esLactante);
                        submit.disabled = !esLactante;
                    })
                    .catch(function() {
                        condicion.value = 'No fue posible consultar la condición';
                        submit.disabled = true;
                    });
            }

            cabra.addEventListener('change', actualizarCondicion);
            fecha.addEventListener('change', actualizarCondicion);
            actualizarCondicion();
        });
    </script>

</body>

</html>