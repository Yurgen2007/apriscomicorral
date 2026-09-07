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
    <title>Canastillas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>📦 Canastillas</h1>
            <a href="<?php echo BASE_URL; ?>/canastillas/create" class="btn btn-primary btn-nueva-pajilla">
                <i class="fas fa-plus"></i> Nueva Canastilla
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
                <h3>📋 Listado de Canastillas</h3>

                <?php if (!empty($canastillas)): ?>
                    <table class="cabras-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha de Registro</th>
                                <th>Pajillas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($canastillas as $canastilla): ?>
                                <tr>
                                    <td><?php echo e($canastilla['id_canastilla']); ?></td>
                                    <td><strong><?php echo e($canastilla['nombre']); ?></strong></td>
                                    <td><?php echo e($canastilla['descripcion'] ?: '-'); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($canastilla['fecha_registro'])); ?></td>
                                    <td><?php echo e($canastilla['pajilla_count'] ?? 0); ?></td>
                                    <td class="cabra-actions">
                                        <a href="<?php echo BASE_URL; ?>/canastillas/<?php echo $canastilla['id_canastilla']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/canastillas/<?php echo $canastilla['id_canastilla']; ?>/edit" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/canastillas/<?php echo $canastilla['id_canastilla']; ?>/delete" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta canastilla?')">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>No hay canastillas registradas.</p>
                        <a href="<?php echo BASE_URL; ?>/canastillas/create" class="btn btn-primary">
                            Crear primera canastilla
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>

<style>
    .cabras-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: var(--white);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .cabras-table thead th {
        background: var(--brown);
        color: var(--white);
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
    }

    .cabras-table tbody td {
        padding: 10px 15px;
        border-bottom: 1px solid var(--light);
    }

    .cabras-table tbody tr:hover {
        background: var(--cream);
    }

    .cabra-actions .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>