<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Pájillas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>🧪 Inventario de Pájillas</h1>
            <a href="<?php echo BASE_URL; ?>/pajillas/create" class="btn btn-primary btn-nueva-pajilla">
                <i class="fas fa-plus"></i> Nueva Pajilla
            </a>
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

            <div class="info-group">
                <h3>📦 Stock de Pájillas</h3>

                <?php if (!empty($pajillas)): ?>
                    <div class="table-responsive">
                        <table class="cabras-table">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Ejemplar</th>
                                    <th>Registro</th>
                                    <th>Canastilla</th>
                                    <th>Tamaño</th>
                                    <th>Pajillas</th>
                                    <th>Dosis Disponibles</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pajillas as $pajilla): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($pajilla['foto'])): ?>
                                                <img src="<?php echo BASE_URL; ?>/uploads/<?php echo e($pajilla['foto']); ?>" alt="Foto" style="max-width: 50px; max-height: 50px; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <div style="width:50px;height:50px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#999;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?php echo e($pajilla['nombre_ejemplar']); ?></strong></td>
                                        <td><?php echo e($pajilla['registro_ejemplar'] ?: '-'); ?></td>
                                        <td><?php echo e($pajilla['canastilla_nombre'] ?: '-'); ?></td>
                                        <td><?php echo e($pajilla['tamano']); ?></td>
                                        <td><?php echo e($pajilla['cantidad']); ?></td>
                                        <td>
                                            <?php if ($pajilla['dosis_disponibles'] > 0): ?>
                                                <strong style="color: #2e7d32;"><?php echo e($pajilla['dosis_disponibles']); ?></strong>
                                            <?php else: ?>
                                                <strong style="color: #c62828;">0</strong>
                                            <?php endif; ?>
                                        </td>
                                        <td class="cabra-actions">
                                            <a href="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla']; ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla']; ?>/edit" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <?php if ($pajilla['dosis_disponibles'] > 0): ?>
                                                <a href="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla']; ?>/venta" class="btn btn-sm btn-success">
                                                    <i class="fas fa-shopping-cart"></i> Vender
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>No hay pájillas registradas.</p>
                        <a href="<?php echo BASE_URL; ?>/pajillas/create" class="btn btn-primary">
                            Registrar primera pajilla
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <style>
        .text-muted {
            color: var(--gray);
            font-style: italic;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-success {
            background-color: #4caf50;
        }

        .btn-success:hover {
            background-color: #388e3c;
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
            min-width: 780px;
            border-collapse: collapse;
            background: var(--white);
            overflow: hidden;
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

        .cabra-actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            align-items: center;
        }

        @media (max-width: 768px) {
            .table-responsive {
                margin-left: 0;
                margin-right: 0;
                border: 1px solid rgba(88, 54, 25, 0.15);
            }

            .cabras-table {
                min-width: 760px;
            }

            .cabra-actions {
                flex-direction: column;
                align-items: stretch;
                min-width: 110px;
            }

            .cabra-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</body>

</html>