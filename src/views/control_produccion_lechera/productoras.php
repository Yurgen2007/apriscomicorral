<?php require_once __DIR__ . '/../../../includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabras con producción</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        .producers-page { max-width: 1180px; }
        .producers-intro {
            margin-bottom: 22px;
            padding: 20px;
            background: #fff8ed;
            border: 1px solid #ead7bf;
            border-radius: 16px;
        }
        .producers-intro h2 { margin: 0 0 6px; color: #402b16; }
        .producers-intro p { margin: 0; color: #806b58; }
        .producer-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }
        .producer-card {
            display: block;
            overflow: hidden;
            color: #402b16;
            text-decoration: none;
            background: #fff;
            border: 1px solid #ead7bf;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(79, 50, 25, .08);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .producer-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 26px rgba(79, 50, 25, .15);
        }
        .producer-photo {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 180px;
            background: #f4e3cc;
        }
        .producer-photo img {
            width: 100%;
            height: 100%;
            padding: 16px;
            object-fit: contain;
        }
        .producer-card-body { padding: 16px; }
        .producer-card-body h3 { margin: 0 0 10px; }
        .producer-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            color: #806b58;
            font-size: .86rem;
        }
        .producer-stat {
            padding: 8px;
            background: #fff8ed;
            border-radius: 8px;
        }
        .producer-stat strong { display: block; color: #5c3e20; font-size: 1rem; }
        .producer-empty {
            padding: 30px;
            text-align: center;
            color: #806b58;
            background: #fff;
            border: 1px solid #ead7bf;
            border-radius: 16px;
        }
        .annual-summary-card {
            display: block;
            margin-bottom: 22px;
            color: #402b16;
            background: #fff;
            border: 1px solid #ead7bf;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(79, 50, 25, .08);
            overflow: hidden;
        }
        .annual-summary-card summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px;
            cursor: pointer;
            list-style: none;
        }
        .annual-summary-card summary::-webkit-details-marker { display: none; }
        .annual-summary-card summary::after {
            content: '📊';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #f4e3cc;
            font-size: 1.25rem;
        }
        .annual-summary-card summary h2 { margin: 0 0 5px; color: #402b16; font-size: 1.15rem; }
        .annual-summary-card summary p { margin: 0; color: #806b58; font-size: .92rem; }
        .annual-summary-content { padding: 0 22px 22px; }
        .annual-summary-table-wrap {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #ead7bf;
            border-radius: 10px;
        }
        .annual-summary-table {
            width: 100%;
            min-width: 720px;
            border-collapse: collapse;
            background: #fff;
        }
        .annual-summary-table th {
            padding: 13px 14px;
            background: #ead0aa;
            color: #3a2614;
            font-size: .88rem;
            text-align: left;
            white-space: nowrap;
        }
        .annual-summary-table td {
            padding: 12px 14px;
            border-top: 1px solid #f0e2d1;
            color: #4b3522;
            font-size: .92rem;
            white-space: nowrap;
        }
        .annual-summary-table tbody tr:hover { background: #fff8ed; }
        .annual-summary-empty {
            margin: 0;
            padding: 18px;
            color: #806b58;
            text-align: center;
            background: #fff8ed;
            border: 1px dashed #d7af72;
            border-radius: 10px;
        }
        .annual-summary-filter {
            display: flex;
            align-items: end;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .annual-summary-filter label {
            display: flex;
            flex-direction: column;
            gap: 5px;
            color: #5c3e20;
            font-size: .86rem;
            font-weight: 700;
        }
        .annual-summary-filter select {
            min-width: 150px;
            padding: 9px 12px;
            color: #402b16;
            background: #fff;
            border: 1px solid #d7af72;
            border-radius: 8px;
        }
        .annual-summary-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
            flex-wrap: wrap;
        }
        .annual-summary-pagination a,
        .annual-summary-pagination span {
            padding: 8px 12px;
            color: #5c3e20;
            background: #fff8ed;
            border: 1px solid #d7af72;
            border-radius: 8px;
            text-decoration: none;
            font-size: .88rem;
        }
        .annual-summary-pagination .current {
            color: #fff;
            background: #ac815b;
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
<div class="container producers-page">
    <header class="dashboard-header">
        <h1>🐐 Cabras con producción lechera</h1>
        <div class="detail-actions">
            <a href="<?= BASE_URL ?>/control-produccion-lechera" class="btn btn-secondary">Volver a producción</a>
            <a href="<?= BASE_URL ?>/control-produccion-lechera/create" class="btn btn-primary">+ Registrar producción</a>
        </div>
    </header>

    <main class="main-content">
        <section class="producers-intro">
            <h2>Productoras registradas</h2>
            <p>La información se actualiza con cada nuevo registro de leche. Selecciona una cabra para ver sus registros, lactancias, totales y picos de producción.</p>
        </section>

        <details class="annual-summary-card" <?= ($anioResumen || $paginaResumen > 1) ? 'open' : '' ?>>
            <summary>
                <div>
                    <h2>Resumen anual por cabra y condición sanitaria</h2>
                    <p>Selecciona esta card para consultar el resumen de producción.</p>
                </div>
            </summary>
            <div class="annual-summary-content">
                <form class="annual-summary-filter" method="GET" action="<?= BASE_URL ?>/control-produccion-lechera/productoras">
                    <label>
                        Filtrar por año
                        <select name="anio" onchange="this.form.submit()">
                            <option value="">Todos los años</option>
                            <?php foreach ($aniosResumen as $anio): ?>
                                <option value="<?= $anio ?>" <?= $anioResumen === $anio ? 'selected' : '' ?>><?= $anio ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <input type="hidden" name="pagina_resumen" value="1">
                </form>

                <?php if (!empty($resumenAnual)): ?>
                    <div class="annual-summary-table-wrap">
                        <table class="annual-summary-table">
                            <thead>
                                <tr>
                                    <th>Año</th>
                                    <th>Cabra</th>
                                    <th>Condición</th>
                                    <th>Registros</th>
                                    <th>Total (L)</th>
                                    <th>Pico (L)</th>
                                    <th>Fecha pico</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resumenAnual as $resumen): ?>
                                    <tr>
                                        <td><?= (int)$resumen['anio'] ?></td>
                                        <td><?= e($resumen['nombre_cabra']) ?></td>
                                        <td><?= e($resumen['condicion_sanitaria']) ?></td>
                                        <td><?= (int)$resumen['total_registros'] ?></td>
                                        <td><?= number_format((float)$resumen['total_litros'], 2, ',', '.') ?></td>
                                        <td><?= number_format((float)$resumen['produccion_maxima'], 2, ',', '.') ?></td>
                                        <td><?= e(substr($resumen['fecha_produccion_maxima'], 0, 10)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($totalPaginasResumen > 1): ?>
                        <nav class="annual-summary-pagination" aria-label="Paginación del resumen anual">
                            <?php if ($paginaResumen > 1): ?>
                                <a href="?anio=<?= $anioResumen ? (int)$anioResumen : '' ?>&pagina_resumen=<?= $paginaResumen - 1 ?>">Anterior</a>
                            <?php endif; ?>
                            <span class="current">Página <?= $paginaResumen ?> de <?= $totalPaginasResumen ?></span>
                            <?php if ($paginaResumen < $totalPaginasResumen): ?>
                                <a href="?anio=<?= $anioResumen ? (int)$anioResumen : '' ?>&pagina_resumen=<?= $paginaResumen + 1 ?>">Siguiente</a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="annual-summary-empty">No hay datos anuales de producción disponibles.</p>
                <?php endif; ?>
            </div>
        </details>

        <?php if (!empty($productoras)): ?>
            <section class="producer-cards">
                <?php foreach ($productoras as $productora): ?>
                    <a class="producer-card" href="<?= BASE_URL ?>/control-produccion-lechera/cabra/<?= (int)$productora['id_cabra'] ?>">
                        <div class="producer-photo">
                            <img src="<?= BASE_URL ?>/uploads/<?= !empty($productora['foto']) ? e($productora['foto']) : 'default-goat.png' ?>"
                                 alt="<?= e($productora['nombre_cabra']) ?>">
                        </div>
                        <div class="producer-card-body">
                            <h3><?= e($productora['nombre_cabra']) ?></h3>
                            <div class="producer-stats">
                                <div class="producer-stat">
                                    <strong><?= number_format((float)$productora['total_litros'], 2, ',', '.') ?> L</strong>
                                    Total registrado
                                </div>
                                <div class="producer-stat">
                                    <strong><?= (int)$productora['total_registros'] ?></strong>
                                    Registros
                                </div>
                            </div>
                            <?php if ($productora['lactancia_activa']): ?>
                                <small>Lactancia activa #<?= (int)$productora['lactancia_activa'] ?></small>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <div class="producer-empty">Las cabras aparecerán aquí después de registrar su primera producción.</div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
