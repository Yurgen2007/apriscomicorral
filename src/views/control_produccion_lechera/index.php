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
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <div class="container">
        <header class="dashboard-header">
            <h1>🥛 Producción Lechera</h1>
            <a href="<?php echo BASE_URL; ?>/control-produccion-lechera/create"
                class="btn btn-primary btn-sm"
                style="width: auto; min-width: 128px; margin-left: auto; padding: 9px 18px; font-size: 0.88rem; border-radius: 8px; line-height: 1.2; display: inline-block;">
                + Registrar
            </a>
        </header>

        <?php showMessages(); ?>

        <main class="main-content">


            <div class="ranking-container" style="width: 82%; margin: 0 auto 28px auto; background: linear-gradient(135deg, #f9f2e8 0%, #e9d0a8 100%); border: 1px solid #d7af72; border-radius: 18px; box-shadow: 0 10px 25px rgba(79, 50, 25, 0.10); padding: 18px 20px;">
                <h3 style="margin: 0 0 18px 0; text-align: center; color: #402b16; font-size: 1.5rem;">🏆 Top 3 productoras</h3>

                <?php if (!empty($topProductoras)): ?>
                    <div style="display:flex; justify-content:center; align-items:flex-end; gap:26px; flex-wrap:wrap;">
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
                                    <div style="position:absolute; bottom:-4px; right:18px; z-index:3; width:36px; height:36px; border-radius:50%; background: linear-gradient(135deg, #8b5e31, #d49a5d); border: 2px solid #fff; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size: 0.9rem; box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                                        <?php echo $badgeText[$index]; ?>
                                    </div>
                                </div>

                                <div style="position:relative; width: 130px; height: 42px; margin-top: -8px; display:flex; align-items:flex-start; justify-content:center;">
                                    <div style="position:absolute; left: 10px; right: 10px; top: 0; height: 20px; border-radius: 10px; background: linear-gradient(180deg, #e51832, #bf1025); transform: skewX(-30deg); box-shadow: 0 6px 12px rgba(123, 8, 16, 0.2);"></div>
                                    <div style="position:absolute; left: 10px; right: 10px; top: 14px; height: 22px; border-radius: 8px; background: linear-gradient(180deg, #f11d32, #d01023); transform: skewX(-30deg); box-shadow: 0 6px 12px rgba(123, 8, 16, 0.2);"></div>
                                    <div style="position:absolute; left: 50%; transform: translateX(-50%); top: 0; width: 12px; height: 42px; background: linear-gradient(180deg, #ff3c52, #c70d1c); border-radius: 6px; box-shadow: 0 0 0 2px rgba(255,255,255,0.15);"></div>
                                </div>

                                <div style="text-align:center; margin-top: 8px;">
                                    <div style="font-size: 0.8rem; font-weight: 800; color: #6b4724; letter-spacing: 1px; margin-bottom: 4px;">
                                        <?php echo $positions[$index]; ?>
                                    </div>
                                    <div style="font-size: 1.05rem; font-weight: 700; color: #2f1d0d; margin-bottom: 6px;">
                                        <?php echo e($productora['nombre_cabra']); ?>
                                    </div>
                                    <div style="font-size: 0.95rem; color: #4d3015; font-weight: 700;">
                                        <?php echo number_format((float)$productora['total_litros'], 2, ',', '.'); ?> L
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

            <div class="table-container" style="background: #efe2d0; border: 1px solid #d3af78; border-radius: 18px; box-shadow: 0 8px 18px rgba(56, 36, 15, 0.07); overflow: hidden;">
                <table class="table" style="width: 100%; border-collapse: collapse; background: #f7efe5; text-align: center; margin: 0;">
                    <thead>
                        <tr style="background: #e3c7a3; color: #3a2614;">
                            <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">ID</th>
                            <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Cabra</th>
                            <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Turno</th>
                            <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Cantidad (L)</th>
                            <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Fecha</th>
                            <th style="padding: 16px 12px; border-bottom: 1px solid #d0ab75; font-size: 1.05rem;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($controles)): ?>
                            <?php foreach ($controles as $control): ?>
                                <tr style="background: rgba(255,255,255,0.25);">
                                    <td style="padding: 16px 12px; border-bottom: 1px solid #e4cfaa; color: #3d2a16; font-weight: 700;">
                                        <?php echo e($control['id_control_produccion']); ?>
                                    </td>
                                    <td style="padding: 16px 12px; border-bottom: 1px solid #e4cfaa; color: #3d2a16; font-weight: 600;">
                                        <?php echo e($control['nombre_cabra']); ?>
                                    </td>
                                    <td style="padding: 16px 12px; border-bottom: 1px solid #e4cfaa; color: #3d2a16; font-weight: 600;">
                                        <?php echo e($control['turno_ordeño']); ?>
                                    </td>
                                    <td style="padding: 16px 12px; border-bottom: 1px solid #e4cfaa; color: #3d2a16; font-weight: 600;">
                                        <?php echo number_format((float)$control['cantidad_litros'], 2, ',', '.'); ?>
                                    </td>
                                    <td style="padding: 16px 12px; border-bottom: 1px solid #e4cfaa; color: #3d2a16; font-weight: 600;">
                                        <?php echo e($control['fecha_registro']); ?>
                                    </td>
                                    <td style="padding: 16px 12px; border-bottom: 1px solid #e4cfaa;">
                                        <div style="display:flex; justify-content:center; gap:10px;">
                                            <a href="<?php echo BASE_URL; ?>/control-produccion-lechera/<?php echo $control['id_control_produccion']; ?>/edit" class="btn btn-secondary btn-sm" style="background: #d3b291; color: #2b1c11; border: 1px solid #bf9770; border-radius: 8px; padding: 10px 18px; text-decoration: none; font-weight: 600;">Editar</a>
                                            <form method="POST" action="<?php echo BASE_URL; ?>/control-produccion-lechera/<?php echo $control['id_control_produccion']; ?>/delete" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este registro?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" style="background: #c68a5c; color: #fff; border: 1px solid #b67443; border-radius: 8px; padding: 10px 18px; font-weight: 700; cursor: pointer;">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 18px; color: #5a3d24;">No hay registros de producción lechera.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
        </main>
    </div>
</body>

</html>