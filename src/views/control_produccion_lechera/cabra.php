<?php
require_once __DIR__ . '/../../../includes/functions.php';

$cabra = $cabra ?? [];
$controles = $controles ?? [];
$lactancias = $lactancias ?? [];
$condicionActual = $condicionActual ?? null;
$condicionActualNormalizada = is_array($condicionActual) && !empty($condicionActual['condicion_especial'])
    ? strtolower(trim(str_replace('í', 'i', $condicionActual['condicion_especial'])))
    : 'sin control';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($cabra['nombre']) ?> - Producción Lechera</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        .goat-production-page {
            max-width: 1180px;
        }

        .goat-hero,
        .goat-card {
            background: #fff;
            border: 1px solid #ead7bf;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(79, 50, 25, .08);
        }

        .goat-hero {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 22px;
            margin-bottom: 22px;
        }

        .goat-hero-info {
            min-width: 0;
        }

        .goat-hero img {
            width: 96px;
            height: 96px;
            object-fit: contain;
            padding: 8px;
            border: 3px solid #d8b78e;
            border-radius: 50%;
            background: #f4e3cc;
        }

        .goat-hero h2 {
            margin: 0 0 5px;
            color: #402b16;
        }

        .goat-hero p {
            margin: 0;
            color: #806b58;
        }

        .goat-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(280px, 1fr);
            gap: 22px;
        }

        .goat-card {
            padding: 22px;
            margin-bottom: 22px;
        }

        .goat-card h3 {
            margin: 0 0 16px;
            color: #402b16;
        }

        .goat-table-wrap {
            overflow-x: auto;
            border: 1px solid #ead7bf;
            border-radius: 10px;
        }

        .goat-table {
            width: 100%;
            min-width: 620px;
            border-collapse: collapse;
        }

        .goat-table th {
            padding: 12px;
            background: #ead0aa;
            color: #3a2614;
            text-align: left;
            white-space: nowrap;
        }

        .goat-table td {
            padding: 12px;
            border-top: 1px solid #f0e2d1;
            color: #4b3522;
            white-space: nowrap;
        }

        .lactation-item {
            padding: 14px;
            margin-bottom: 10px;
            background: #fffaf4;
            border: 1px solid #ead7bf;
            border-radius: 12px;
        }

        .lactation-item strong {
            color: #402b16;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            background: #ead0aa;
            color: #5c3e20;
            font-size: .78rem;
        }

        .goat-year-filter {
            display: flex;
            align-items: end;
            gap: 10px;
            margin: 0 0 0 auto;
            padding: 0;
            background: transparent;
            border: 0;
        }

        .goat-year-filter label {
            display: flex;
            flex-direction: column;
            gap: 5px;
            color: #5c3e20;
            font-size: .86rem;
            font-weight: 700;
        }

        .goat-year-filter select {
            min-width: 150px;
            padding: 8px 10px;
            color: #402b16;
            background: #fff;
            border: 1px solid #d7af72;
            border-radius: 8px;
        }

        @media (max-width: 800px) {
            .goat-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 500px) {
            .goat-hero {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .goat-hero img {
                width: 70px;
                height: 70px;
            }

            .goat-year-filter {
                width: 100%;
                margin-left: 0;
            }

            .goat-year-filter select {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container goat-production-page">
        <header class="dashboard-header">
            <h1>🥛 Producción de <?= e($cabra['nombre']) ?></h1>
            <div class="detail-actions">
                <a href="<?= BASE_URL ?>/control-produccion-lechera/create" class="btn btn-primary">+ Registrar</a>
                <a href="<?= BASE_URL ?>/control-produccion-lechera" class="btn btn-secondary">Volver al resumen</a>
            </div>
        </header>

        <?php showMessages(); ?>

        <section class="goat-hero">
            <img src="<?= BASE_URL ?>/uploads/<?= !empty($cabra['foto']) ? e($cabra['foto']) : 'default-goat.png' ?>" alt="<?= e($cabra['nombre']) ?>">
            <div class="goat-hero-info">
                <h2><?= e($cabra['nombre']) ?></h2>
                <p>Historial de producción, lactancias y registros diarios.</p>
            </div>
            <form class="goat-year-filter" method="GET">
                <label>
                    Filtrar información por año
                    <select name="anio" onchange="this.form.submit()">
                        <option value="">Todos los años</option>
                        <?php foreach (($aniosCabra ?? []) as $anio): ?>
                            <option value="<?= (int)$anio ?>" <?= ($anioCabra ?? null) === (int)$anio ? 'selected' : '' ?>>
                                <?= (int)$anio ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </form>
        </section>

        <div class="goat-grid">
            <section class="goat-card">
                <h3>🥛 Registros de producción</h3>
                <div class="goat-table-wrap">
                    <table class="goat-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Condición</th>
                                <th>Turno</th>
                                <th>Litros</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($controles as $control): ?>
                                <tr>
                                    <td><?= e($control['fecha_registro']) ?></td>
                                    <td><?= e($control['condicion_sanitaria'] ?: 'Sin control') ?></td>
                                    <td><?= e($control['turno_ordeño']) ?></td>
                                    <td><?= number_format((float)$control['cantidad_litros'], 2, ',', '.') ?> L</td>
                                    <td><a class="btn btn-secondary btn-sm" href="<?= BASE_URL ?>/control-produccion-lechera/<?= (int)$control['id_control_produccion'] ?>/edit">Editar</a></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($controles)): ?><tr>
                                    <td colspan="5">No hay registros para esta cabra.</td>
                                </tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="goat-card">
                <h3>📚 Lactancias</h3>
                <?php foreach ($lactancias as $lactancia): ?>
                    <?php
                    $inicioProduccion = $lactancia['fecha_primer_registro'] ?: $lactancia['fecha_inicio'];
                    $finProduccion = $lactancia['fecha_ultimo_registro'] ?: $inicioProduccion;
                    $intervalo = (new DateTime($inicioProduccion))->diff(new DateTime($finProduccion));
                    $duracion = $intervalo->days + 1;
                    $mesesProduccion = ($intervalo->y * 12) + $intervalo->m;
                    ?>
                    <div class="lactation-item">
                        <strong>Lactancia #<?= (int)$lactancia['numero_lactancia'] ?></strong>
                        <span class="badge"><?= e($lactancia['estado']) ?></span>
                        <br>Parto: <?= e($lactancia['fecha_parto']) ?>
                        <br>Periodo con registros: <?= e($inicioProduccion) ?> - <?= e($finProduccion) ?>
                        <br>Duración registrada: <?= (int)$mesesProduccion ?> meses (<?= (int)$duracion ?> días)
                        <br>Total: <?= number_format((float)$lactancia['total_litros'], 2, ',', '.') ?> L
                        <br>Máxima: <?= $lactancia['produccion_maxima'] === null ? '-' : number_format((float)$lactancia['produccion_maxima'], 2, ',', '.') . ' L' ?>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($lactancias) && !empty($controles)): ?>
                    <?php
                    $registrosSinLactancia = array_filter($controles, function ($control) {
                        return empty($control['id_lactancia']);
                    });
                    $fechas = array_map(function ($control) {
                        return substr($control['fecha_registro'], 0, 10);
                    }, $registrosSinLactancia);
                    $litros = array_map(function ($control) {
                        return (float)$control['cantidad_litros'];
                    }, $registrosSinLactancia);
                    $inicioSinLactancia = $fechas ? min($fechas) : null;
                    $finSinLactancia = $fechas ? max($fechas) : null;
                    $maximoSinLactancia = $litros ? max($litros) : 0;
                    $intervaloSinLactancia = $inicioSinLactancia && $finSinLactancia
                        ? (new DateTime($inicioSinLactancia))->diff(new DateTime($finSinLactancia))
                        : null;
                    $numeroLactanciaPendiente = count($lactancias) + 1;
                    ?>
                    <?php if ($registrosSinLactancia): ?>
                        <div class="lactation-item">
                            <strong>Lactancia #<?= $numeroLactanciaPendiente ?></strong>
                            <span class="badge"><?= $condicionActualNormalizada === 'vacia' ? 'SECADA' : 'SIN VINCULAR' ?></span>
                            <br>Periodo registrado: <?= e($inicioSinLactancia) ?> - <?= e($finSinLactancia) ?>
                            <?php if ($condicionActualNormalizada === 'vacia' && is_array($condicionActual) && !empty($condicionActual['fecha_control'])): ?>
                                <br>Fecha de cierre sanitario: <?= e(substr($condicionActual['fecha_control'], 0, 10)) ?>
                            <?php endif; ?>
                            <br>Duración registrada: <?= $intervaloSinLactancia ? (int)(($intervaloSinLactancia->y * 12) + $intervaloSinLactancia->m) . ' meses (' . ((int)$intervaloSinLactancia->days + 1) . ' días)' : '0 días' ?>
                            <br>Total: <?= number_format(array_sum($litros), 2, ',', '.') ?> L
                            <br>Máxima: <?= number_format($maximoSinLactancia, 2, ',', '.') ?> L
                        </div>
                    <?php endif; ?>
                <?php elseif (empty($lactancias)): ?>
                    <p>No hay lactancias registradas.</p>
                <?php endif; ?>
            </section>
        </div>
    </div>
</body>

</html>