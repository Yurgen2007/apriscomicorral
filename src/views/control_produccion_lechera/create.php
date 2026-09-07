<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';

$cabras = $cabras ?? [];

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar Producción Lechera</title>

    <link rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/style.css">

    <link
        href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css"
        rel="stylesheet">

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

                <form
                    method="POST"
                    action="<?php echo BASE_URL; ?>/control-produccion-lechera/create">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php echo generateCSRFToken(); ?>">


                    <!-- CABRA -->

                    <div class="form-group">
                        <label for="id_cabra">
                            🐐 Cabra
                        </label>

                        <select
                            name="id_cabra"
                            id="id_cabra"
                            required>
                            <option value="">
                                Seleccione una cabra
                            </option>

                            <?php foreach ($cabras as $cabra): ?>

                                <?php
                                // Solo mostrar hembras, sin importar el estado
                                if (
                                    !isset($cabra['sexo']) ||
                                    strtoupper(trim($cabra['sexo'])) !== 'HEMBRA'
                                ) {
                                    continue;
                                }
                                ?>

                                <option value="<?php echo $cabra['id_cabra']; ?>">
                                    <?php echo e($cabra['nombre']); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>


                    <!-- TURNO -->

                    <div class="form-group">

                        <label for="turno_ordeño">
                            🕐 Turno de Ordeño
                        </label>

                        <select
                            name="turno_ordeño"
                            id="turno_ordeño"
                            required>

                            <option value="">
                                Seleccione el turno
                            </option>

                            <option value="MAÑANA">
                                🌅 Mañana
                            </option>

                            <option value="TARDE">
                                🌇 Tarde
                            </option>

                        </select>

                    </div>


                    <!-- LITROS -->

                    <div class="form-group">

                        <label for="cantidad_litros">
                            🥛 Cantidad de Leche (litros)
                        </label>

                        <input
                            type="number"
                            name="cantidad_litros"
                            id="cantidad_litros"
                            min="0"
                            step="0.01"
                            placeholder="Ejemplo: 2.50"
                            required>

                    </div>






                    <!-- BOTONES -->

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

    <!-- Tom Select -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            new TomSelect('#id_cabra', {
                placeholder: '🔎 Buscar cabra...',
                searchField: ['text'],
                allowEmptyOption: true,
                create: false
            });

        });
    </script>

</body>

</html>

</body>

</html>