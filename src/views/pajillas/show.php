<?php
$pajilla = $pajilla ?? [];
$ventas = $ventas ?? [];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pajilla['nombre_ejemplar'] ?? 'Pajilla'); ?> - Pajilla - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>🧪 <?php echo e($pajilla['nombre_ejemplar'] ?? 'Pajilla'); ?></h1>
            <div style="display: flex; gap: 10px;">
                <a href="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla'] ?? 0; ?>/edit" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <?php if (($pajilla['dosis_disponibles'] ?? 0) > 0): ?>
                    <a href="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla'] ?? 0; ?>/venta" class="btn btn-success">
                        <i class="fas fa-shopping-cart"></i> Vender Dosis
                    </a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/pajillas" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Inventario
                </a>
            </div>
        </header>

        <main class="main-content">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo e($_SESSION['success']);
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo e($_SESSION['error']);
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="cabra-detail-container">
                <div class="cabra-detail-card">
                    <div class="cabra-photo-section">
                        <?php if (!empty($pajilla['foto'])): ?>
                            <img src="<?php echo BASE_URL; ?>/uploads/<?php echo e($pajilla['foto']); ?>"
                                alt="<?php echo e($pajilla['nombre_ejemplar']); ?>" class="cabra-detail-image">
                        <?php else: ?>
                            <div class="no-photo-large">🧪</div>
                        <?php endif; ?>
                    </div>

                    <div class="cabra-info-section">
                        <div class="info-header">
                            <h2><?php echo e($pajilla['nombre_ejemplar'] ?? 'Pajilla'); ?></h2>
                            <?php if (($pajilla['dosis_disponibles'] ?? 0) > 0): ?>
                                <span class="status-badge-large activa">En stock</span>
                            <?php else: ?>
                                <span class="status-badge-large inactiva">Agotada</span>
                            <?php endif; ?>
                        </div>

                        <div class="info-grid">
                            <div class="info-group">
                                <h3>📋 Información de la Pajilla</h3>
                                <div class="info-items">
                                    <div class="info-item">
                                        <strong>ID:</strong>
                                        <span><?php echo e($pajilla['id_pajilla'] ?? '-'); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Canastilla:</strong>
                                        <span><?php echo e($pajilla['canastilla_nombre'] ?? 'Sin canastilla'); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Nombre del Ejemplar:</strong>
                                        <span><?php echo e($pajilla['nombre_ejemplar'] ?? 'Sin nombre'); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Registro:</strong>
                                        <span><?php echo e($pajilla['registro_ejemplar'] ?? 'No registrado'); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Tamaño:</strong>
                                        <span><?php echo e($pajilla['tamano'] ?? '-'); ?>
                                            <?php echo ($pajilla['tamano'] ?? '') === '0.25 ml'
                                                ? '(2 dosis por pajilla)'
                                                : '(1 dosis por pajilla)'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="info-group">
                                <h3>📊 Inventario</h3>
                                <div class="info-items">
                                    <div class="info-item">
                                        <strong>Cantidad de Pájillas:</strong>
                                        <span class="sex-badge-large <?php echo (($pajilla['cantidad'] ?? 0) > 0) ? 'macho' : 'hembra'; ?>">
                                            <?php echo e($pajilla['cantidad'] ?? 0); ?> paja(s)
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Dosis Disponibles:</strong>
                                        <span class="sex-badge-large <?php echo (($pajilla['dosis_disponibles'] ?? 0) > 0) ? 'hembra' : 'macho'; ?>">
                                            <?php echo e($pajilla['dosis_disponibles'] ?? 0); ?> dosis
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Fecha de Registro:</strong>
                                        <span><?php echo !empty($pajilla['fecha_registro']) ? date('d/m/Y H:i:s', strtotime($pajilla['fecha_registro'])) : '-'; ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($pajilla['observaciones'])): ?>
                                <div class="info-group">
                                    <h3>📝 Observaciones</h3>
                                    <div class="info-items">
                                        <div class="info-item">
                                            <span><?php echo nl2br(e($pajilla['observaciones'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="detail-actions">
                            <form method="POST" action="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla'] ?? 0; ?>/delete" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta pajilla? Esta acción no se puede deshacer.')">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <button type="submit" class="btn btn-danger">🗑️ Eliminar Pajilla</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($ventas)): ?>
                <div class="info-group">
                    <h3>💰 Historial de Ventas</h3>
                    <div class="table-responsive">
                        <table class="cabras-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Dosis Vendidas</th>
                                    <th>Fecha</th>
                                    <th>Vendido por</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ventas as $venta): ?>
                                    <tr>
                                        <td><?php echo e($venta['id_venta']); ?></td>
                                        <td><?php echo e($venta['cantidad_dosis']); ?></td>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($venta['fecha_venta'])); ?></td>
                                        <td><?php echo e($venta['nombre_usuario'] ?: '-'); ?></td>
                                        <td><?php echo e($venta['observaciones'] ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <style>
        .text-muted {
            color: var(--gray);
            font-style: italic;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .detail-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .detail-actions .btn {
            width: auto;
        }

        .cabra-detail-container {
            max-width: 1000px;
            margin: 20px auto;
        }

        .cabra-detail-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .cabra-photo-section {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }

        .cabra-detail-image {
            width: 100%;
            height: 400px;
            object-fit: contain;
            border-radius: 20px;
        }

        .no-photo-large {
            font-size: 100px;
            color: #ddd;
        }

        .cabra-info-section {
            padding: 30px;
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .info-group {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            background: #f8f9fa;
            margin-bottom: 20px;
        }

        .info-group h3 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 1.1em;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 8px;
        }

        .info-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item strong {
            min-width: 140px;
            color: #495057;
        }

        .status-badge-large {
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .status-badge-large.activa {
            background: #d4edda;
            color: #155724;
        }

        .status-badge-large.inactiva {
            background: #f8d7da;
            color: #721c24;
        }

        .sex-badge-large {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .sex-badge-large.macho {
            background: #fff8f8;
            color: #d32f2f;
        }

        .sex-badge-large.hembra {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cabras-table {
            width: 100%;
            min-width: 660px;
            border-collapse: collapse;
            background: var(--white);
        }

        .cabras-table th {
            background: var(--brown);
            color: var(--white);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }

        .cabras-table td {
            padding: 10px 15px;
            border-bottom: 1px solid var(--light);
            white-space: nowrap;
        }

        .cabras-table tr:hover {
            background: var(--cream);
        }

        .btn-success {
            background-color: #4caf50;
        }

        .btn-success:hover {
            background-color: #388e3c;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .cabra-detail-image {
                height: 260px;
            }
        }
    </style>
</body>

</html>