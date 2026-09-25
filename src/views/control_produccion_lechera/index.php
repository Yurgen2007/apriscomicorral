<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producción Lechera</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <style>
        html {
            scroll-behavior: smooth;
        }

        .production-page .dashboard-header {
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
        }

        .production-page .dashboard-header h1 {
            flex: 0 1 auto;
            min-width: 0;
        }

        .production-page .dashboard-header .btn {
            width: auto;
            margin-left: auto;
            min-width: 128px;
            padding: 9px 18px;
            font-size: .88rem;
            line-height: 1.2;
            text-align: center;
        }

        .production-ranking {
            width: 100% !important;
            margin: 0 0 24px !important;
        }

        .production-layout {
            width: 100%;
        }

        .production-main {
            width: 100%;
        }

        .producer-access-card {
            display: grid;
            grid-template-columns: 62px minmax(0, 1fr) auto;
            align-items: center;
            gap: 18px;
            margin-bottom: 24px;
            padding: 20px 24px;
            color: #402b16;
            text-decoration: none;
            background: linear-gradient(135deg, #fff8ed 0%, #ead0aa 100%);
            border: 1px solid #d7af72;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(79, 50, 25, .09);
        }

        .producer-access-card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 62px;
            height: 62px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .65);
            border: 1px solid rgba(184, 135, 82, .45);
            font-size: 2rem;
        }

        .producer-access-card h3 {
            margin: 0 0 6px;
            color: #402b16;
        }

        .producer-access-card p {
            margin: 0;
            color: #806b58;
            line-height: 1.45;
        }

        .producer-access-card .btn {
            width: auto !important;
            min-width: 120px;
            flex: 0 0 auto;
            text-align: center;
            white-space: nowrap;
        }

        .production-section {
            width: 100%;
            margin-bottom: 24px;
            padding: 24px;
            background: #fff;
            border: 1px solid #ead7bf;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(79, 50, 25, 0.08);
        }

        .production-section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }

        .production-section-header>div {
            min-width: 0;
        }

        .production-card-link {
            display: block;
            color: inherit;
            text-decoration: none;
            border-radius: 12px;
            transition: background-color .2s ease, transform .2s ease;
        }

        .production-card-link:hover {
            background: #fff8ed;
            transform: translateY(-1px);
        }

        .production-card-link:focus-visible {
            outline: 3px solid rgba(172, 129, 91, .45);
            outline-offset: 4px;
        }

        .production-section:target {
            animation: production-card-focus 1.4s ease;
        }

        @keyframes production-card-focus {

            0%,
            100% {
                box-shadow: 0 8px 22px rgba(79, 50, 25, 0.08);
            }

            35% {
                box-shadow: 0 0 0 5px rgba(172, 129, 91, .28), 0 12px 28px rgba(79, 50, 25, .14);
            }
        }

        .production-section h3 {
            margin: 0 0 5px;
            color: #402b16;
            font-size: 1.2rem;
        }

        .production-section>p {
            margin: 0 0 16px;
            color: #806b58;
            font-size: .92rem;
        }

        .production-section-header>p {
            margin-bottom: 0;
        }

        .production-card-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #f4e3cc;
            color: #6b4724;
            font-size: 1.25rem;
        }

        .production-table-wrap {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #ead7bf;
            border-radius: 10px;
            scroll-margin-top: 24px;
        }

        .production-section .table,
        .production-records {
            width: 100%;
            min-width: 620px;
            border-collapse: collapse;
            background: #fff;
        }

        .production-section .table th,
        .production-records th {
            padding: 13px 14px;
            background: #ead0aa;
            color: #3a2614;
            font-size: .88rem;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }

        .production-section .table td,
        .production-records td {
            padding: 12px 14px;
            border-top: 1px solid #f0e2d1;
            color: #4b3522;
            font-size: .92rem;
            text-align: left;
            vertical-align: middle;
        }

        .production-section .table tbody tr:hover,
        .production-records tbody tr:hover {
            background: #fff8ed;
        }

        .production-records {
            min-width: 980px;
        }

        .production-records th:nth-child(1),
        .production-records td:nth-child(1),
        .production-records th:nth-child(6),
        .production-records td:nth-child(6) {
            text-align: center;
        }

        .production-records .actions {
            display: flex;
            justify-content: center;
            gap: 8px;
            white-space: nowrap;
        }

        .production-records .actions .btn {
            width: auto !important;
            padding: 8px 12px !important;
            font-size: .82rem;
        }

        .production-empty {
            padding: 28px 16px !important;
            color: #806b58 !important;
            text-align: center !important;
        }

        @media (max-width: 600px) {}

        @media (max-width: 560px) {
            .production-section {
                padding: 16px;
                border-radius: 14px;
            }

            .production-section-header {
                gap: 12px;
            }

            .production-card-icon {
                flex-basis: 36px;
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

        }

        @media (max-width: 600px) {
            .producer-access-card {
                grid-template-columns: 48px minmax(0, 1fr);
                gap: 12px;
                padding: 16px;
            }

            .producer-access-card-icon {
                width: 48px;
                height: 48px;
                font-size: 1.5rem;
            }

            .producer-access-card .btn {
                grid-column: 1 / -1;
                width: 100% !important;
            }

            .production-page .dashboard-header .btn {
                flex: 1 1 100%;
                text-align: center;
            }

            .production-page .dashboard-header {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <div class="container production-page">
        <header class="dashboard-header">
            <h1>🥛 Producción Lechera </h1>
            <a href="<?php echo BASE_URL; ?>/control-produccion-lechera/create"
                class="btn btn-primary btn-sm">
                + Registrar
            </a>
        </header>

        <?php showMessages(); ?>

        <main class="main-content">
            <div class="production-layout">
                <div class="production-main">
                    <a class="producer-access-card" href="<?= BASE_URL ?>/control-produccion-lechera/productoras">
                        <span class="producer-access-card-icon" aria-hidden="true">🐐</span>
                        <div>
                            <h3>🐐 Cabras con producción registrada</h3>
                            <p>Consulta las fotos, el resumen y el historial independiente de cada cabra.</p>
                        </div>
                        <span class="btn btn-primary">Ver cabras</span>
                    </a>
                    <div class="ranking-container production-ranking" style="background: linear-gradient(135deg, #fff9f1 0%, #f4debb 45%, #e8c98f 100%); border: 1px solid rgba(118, 81, 42, 0.22); border-radius: 22px; box-shadow: 0 14px 30px rgba(94, 63, 30, 0.12); padding: 22px 20px 42px; position: relative; overflow: visible;">
                        <div style="position:absolute; inset: 0; background: radial-gradient(circle at top, rgba(255,255,255,0.65), transparent 45%); pointer-events:none;"></div>
                        <h3 style="margin: 0 0 22px 0; text-align: center; color: #402b16; font-size: 1.5rem; letter-spacing: 0.02em; position: relative; z-index: 1;">🏆 Top 3 productoras</h3>

                        <?php if (!empty($topProductoras)): ?>
                            <div style="display:flex; justify-content:center; align-items:flex-end; gap:24px; flex-wrap:wrap; position: relative; z-index: 1;">
                                <?php foreach ($topProductoras as $index => $productora): ?>
                                    <?php
                                    $medalPalette = [
                                        ['main' => '#d8a31a', 'secondary' => '#f4d46d', 'edge' => '#8a5a00', 'ribbon' => '#d71d2d'],
                                        ['main' => '#c9ced3', 'secondary' => '#e9edf1', 'edge' => '#6d737b', 'ribbon' => '#d71d2d'],
                                        ['main' => '#c67c3c', 'secondary' => '#edb37c', 'edge' => '#7a420f', 'ribbon' => '#d71d2d']
                                    ];
                                    $badgeText = ['1', '2', '3'];
                                    $positions = ['1°', '2°', '3°'];
                                    $medalSize = $index === 0 ? '150px' : '128px';
                                    ?>
                                    <div style="display:flex; flex-direction:column; align-items:center; position:relative; min-width: 150px; margin-top: 8px; transition: transform 0.25s ease;">
                                        <div style="position:relative; width: <?php echo $medalSize; ?>; height: <?php echo $medalSize; ?>; display:flex; align-items:center; justify-content:center; margin-bottom: 18px; transition: transform 0.25s ease, filter 0.25s ease; cursor:pointer;" onmouseover="this.style.transform='scale(1.18)'; this.style.filter='drop-shadow(0 12px 18px rgba(90,60,30,0.2))';" onmouseout="this.style.transform='scale(1)'; this.style.filter='none';">
                                            <div style="position:absolute; inset: -10px; border-radius:50%; background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.85), rgba(255,255,255,0) 34%), linear-gradient(145deg, <?php echo $medalPalette[$index]['secondary']; ?> 0%, <?php echo $medalPalette[$index]['main']; ?> 55%, <?php echo $medalPalette[$index]['edge']; ?> 100%); box-shadow: 0 14px 24px rgba(69, 41, 12, 0.18), inset 0 0 18px rgba(255,255,255,0.55); border: 6px solid rgba(255,255,255,0.5);"></div>
                                            <div style="position:absolute; inset: 18px; border-radius:50%; background: linear-gradient(145deg, rgba(255,255,255,0.75), rgba(255,255,255,0.15)); border: 2px solid rgba(255,255,255,0.5);"></div>
                                            <div style="position:absolute; inset: 22px; border-radius:50%; overflow:hidden; background: #f7efe7; border: 4px solid rgba(255,255,255,0.8); box-shadow: inset 0 0 0 2px rgba(99,76,45,0.10); z-index:1; display:flex; align-items:center; justify-content:center;">
                                                <img src="<?php echo BASE_URL; ?>/uploads/<?php echo !empty($productora['foto']) ? e($productora['foto']) : 'default-goat.png'; ?>"
                                                    alt="<?php echo e($productora['nombre_cabra']); ?>"
                                                    style="width: 100%; height: 100%; object-fit:contain; background: #f7efe7; padding: 12px; box-sizing: border-box;">
                                            </div>
                                            <div style="position:absolute; inset: 10px 12px auto 12px; height: 28px; border-radius: 50%; background: linear-gradient(180deg, rgba(255,255,255,0.8), rgba(255,255,255,0)); z-index:2; pointer-events:none;"></div>
                                            <div class="ranking-hover-info" style="position:absolute; left: 50%; bottom: -54px; transform: translateX(-50%); z-index:3; width: 170px; text-align:center; opacity: 1; transition: all 0.2s ease; pointer-events:none; line-height: 1.25;">
                                                <div style="font-size: 0.72rem; font-weight: 700; color: #000000; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 2px;">
                                                    <?php echo e($productora['nombre_cabra']); ?>
                                                </div>
                                                <div style="font-size: 0.72rem; font-weight: 600; color: #000000; margin-bottom: 2px;">
                                                    <?php echo $positions[$index]; ?> · <?php echo number_format((float)$productora['total_litros'], 2, ',', '.'); ?> L
                                                </div>
                                                <div style="font-size: 0.72rem; font-weight: 600; color: #000000;">
                                                    <?php echo e($productora['condicion_actual'] ?? 'Sin control'); ?>
                                                </div>
                                            </div>
                                            <div style="position:absolute; bottom:-4px; right:18px; z-index:4; width:36px; height:36px; border-radius:50%; background: linear-gradient(135deg, #8b5e31, #d49a5d); border: 2px solid #fff; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size: 0.9rem; box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                                                <?php echo $badgeText[$index]; ?>
                                            </div>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="background: #f8f8f8; border: 1px dashed #ccc; border-radius: 10px; padding: 16px; color: #666; text-align: center;">
                                No hay registros suficientes para mostrar el ranking.
                            </div>
                        <?php endif; ?>
                    </div>

                    <section class="production-section production-records-card" id="registros-produccion">
                        <a class="production-card-link" href="#tabla-registros" aria-label="Ver registros de producción">
                            <div class="production-section-header">
                                <div>
                                    <h3>Registros de producción</h3>
                                    <p class="text-muted">Consulta, edita o elimina los registros diarios de leche.</p>
                                </div>
                                <span class="production-card-icon" aria-hidden="true">🥛</span>
                            </div>
                        </a>
                        <div class="production-table-wrap">
                            <table class="production-records" id="tabla-registros">
                                <thead>
                                    <tr style="background: #e3c7a3; color: #3a2614;">
                                        <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">ID</th>
                                        <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Cabra</th>
                                        <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Condición sanitaria</th>
                                        <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Turno</th>
                                        <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Cantidad (L)</th>
                                        <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Fecha</th>
                                        <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($controles)): ?>
                                        <?php foreach ($controles as $control): ?>
                                            <tr>
                                                <td>
                                                    <?php echo e($control['id_control_produccion']); ?>
                                                </td>
                                                <td>
                                                    <?php echo e($control['nombre_cabra']); ?>
                                                </td>
                                                <td>
                                                    <?php echo e($control['condicion_sanitaria'] ?: 'Sin control'); ?>
                                                </td>
                                                <td>
                                                    <?php echo e($control['turno_ordeño']); ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format((float)$control['cantidad_litros'], 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php echo e($control['fecha_registro']); ?>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="<?php echo BASE_URL; ?>/control-produccion-lechera/<?php echo $control['id_control_produccion']; ?>/edit" class="btn btn-secondary btn-sm">Editar</a>
                                                        <form method="POST" action="<?php echo BASE_URL; ?>/control-produccion-lechera/<?php echo $control['id_control_produccion']; ?>/delete" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este registro?');">
                                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="production-empty">No hay registros de producción lechera.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                </div>

                <?php $currentPage = isset($currentPage) ? (int)$currentPage : 1; ?>
                <?php $totalPages = isset($totalPages) ? (int)$totalPages : 1; ?>
                <?php if ($totalPages > 1): ?>
                    <div style="display:flex; justify-content:center; align-items:center; gap:10px; margin-top: 24px; flex-wrap:wrap;">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?php echo BASE_URL; ?>/control-produccion-lechera?page=<?php echo $currentPage - 1; ?><?php echo !empty($selectedCabra) ? '&id_cabra=' . (int)$selectedCabra : ''; ?><?php echo !empty($searchTerm) ? '&q=' . urlencode($searchTerm) : ''; ?>" style="background:#d3b291; border:1px solid #bf9770; color:#2b1c11; border-radius:8px; padding:9px 14px; text-decoration:none; font-weight:600;">Anterior</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $currentPage): ?>
                                <span style="background:#6b4724; color:#fff; border-radius:8px; padding:9px 12px; font-weight:700; min-width:36px; text-align:center;"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="<?php echo BASE_URL; ?>/control-produccion-lechera?page=<?php echo $i; ?><?php echo !empty($selectedCabra) ? '&id_cabra=' . (int)$selectedCabra : ''; ?><?php echo !empty($searchTerm) ? '&q=' . urlencode($searchTerm) : ''; ?>" style="background:#f4eadb; border:1px solid #d7af72; color:#2b1c11; border-radius:8px; padding:9px 12px; text-decoration:none; font-weight:600; min-width:36px; text-align:center;"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="<?php echo BASE_URL; ?>/control-produccion-lechera?page=<?php echo $currentPage + 1; ?><?php echo !empty($selectedCabra) ? '&id_cabra=' . (int)$selectedCabra : ''; ?><?php echo !empty($searchTerm) ? '&q=' . urlencode($searchTerm) : ''; ?>" style="background:#d3b291; border:1px solid #bf9770; color:#2b1c11; border-radius:8px; padding:9px 14px; text-decoration:none; font-weight:600;">Siguiente</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
    </div>
    </main>
    </div>
</body>

</html>