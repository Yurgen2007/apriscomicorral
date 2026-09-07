<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($canastilla['nombre']); ?> - Canastilla - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>📦 <?php echo e($canastilla['nombre']); ?></h1>
            <div style="display: flex; gap: 10px;">
                <a href="<?php echo BASE_URL; ?>/pajillas/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Agregar Pajilla
                </a>
                <a href="<?php echo BASE_URL; ?>/canastillas" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Canastillas
                </a>
            </div>
        </header>

        <main class="main-content">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo e($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="cabra-detail-container">
                <div class="cabra-detail-card">
                    <div class="cabra-photo-section" style="min-height: 120px; padding: 20px;">
                        <i class="fas fa-box-open" style="font-size: 60px; color: #ccc;"></i>
                    </div>
                    <div class="cabra-info-section">
                        <div class="info-header">
                            <h2><?php echo e($canastilla['nombre']); ?></h2>
                        </div>

                        <div class="info-grid">
                            <div class="info-group">
                                <h3>📋 Información de la Canastilla</h3>
                                <div class="info-items">
                                    <div class="info-item">
                                        <strong>ID:</strong>
                                        <span><?php echo e($canastilla['id_canastilla']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Descripción:</strong>
                                        <span><?php echo e($canastilla['descripcion'] ?: 'Sin descripción'); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Fecha de Registro:</strong>
                                        <span><?php echo date('d/m/Y H:i:s', strtotime($canastilla['fecha_registro'])); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="detail-actions">
                            <a href="<?php echo BASE_URL; ?>/canastillas/<?php echo $canastilla['id_canastilla']; ?>/edit" class="btn btn-warning">✏️ Editar</a>
                            <form method="POST" action="<?php echo BASE_URL; ?>/canastillas/<?php echo $canastilla['id_canastilla']; ?>/delete" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta canastilla?')">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <button type="submit" class="btn btn-danger">🗑️ Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-group">
                <h3>🐐 Pajillas en esta Canastilla</h3>

                <?php if (!empty($pajillas)): ?>
                    <table class="cabras-table">
                        <thead>
                            <tr>
                                <th>Ejemplar</th>
                                <th>Registro</th>
                                <th>Tamaño</th>
                                <th>Cantidad</th>
                                <th>Dosis</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pajillas as $pajilla): ?>
                                <?php $isAgotada = $pajilla['dosis_disponibles'] <= 0; ?>
                                <tr <?php echo $isAgotada ? 'style="opacity: 0.6;"' : ''; ?>>
                                    <td><strong><?php echo e($pajilla['nombre_ejemplar']); ?></strong><?php echo $isAgotada ? ' <span class="text-muted">(Agotada)</span>' : ''; ?></td>
                                    <td><?php echo e($pajilla['registro_ejemplar'] ?: '-'); ?></td>
                                    <td><?php echo e($pajilla['tamano']); ?></td>
                                    <td><?php echo e($pajilla['cantidad']); ?></td>
                                    <td><?php echo e($pajilla['dosis_disponibles']); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla']; ?>" class="btn btn-sm btn-info">Ver</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No hay pajillas registradas en esta canastilla.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <style>
    .text-muted { color: var(--gray); font-style: italic; }
    .info-grid { display: grid; grid-template-columns: 1fr; gap: 15px; }
    .detail-actions { margin-top: 20px; display: flex; gap: 10px; justify-content: center; }
    .detail-actions .btn { width: auto; }
    .cabra-photo-section { text-align: center; }
    .cabra-detail-container { max-width: 1000px; margin: 20px auto; }
    .cabra-detail-card { background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; display: flex; flex-direction: column; }
    .cabra-photo-section { background: #f8f9fa; display: flex; align-items: center; justify-content: center; }
    .cabra-info-section { padding: 30px; }
    .info-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e9ecef; }
    .info-group { border: 1px solid #e9ecef; border-radius: 10px; padding: 20px; background: #f8f9fa; margin-bottom: 20px; }
    .info-group h3 { margin: 0 0 15px 0; color: #495057; font-size: 1.1em; border-bottom: 2px solid #4CAF50; padding-bottom: 8px; }
    .info-items { display: flex; flex-direction: column; gap: 10px; }
    .info-item { display: flex; align-items: center; gap: 10px; }
    .info-item strong { min-width: 140px; color: #495057; }
    .cabras-table { width: 100%; border-collapse: collapse; margin: 20px 0; background: var(--white); border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .cabras-table th { background: var(--brown); color: var(--white); padding: 12px 15px; text-align: left; font-weight: 600; }
    .cabras-table td { padding: 10px 15px; border-bottom: 1px solid var(--light); }
    .cabras-table tr:hover { background: var(--cream); }
    .btn-sm { padding: 5px 10px; font-size: 12px; }
    </style>
</body>
</html>
