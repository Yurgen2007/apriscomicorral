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
    <title>Editar Canastilla - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>✏️ Editar Canastilla</h1>
        </header>

        <main class="main-content">
            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <h4>Por favor, corrige los siguientes errores:</h4>
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" action="<?php echo BASE_URL; ?>/canastillas/<?php echo $canastilla['id_canastilla']; ?>/edit" class="cabra-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                    <div class="form-section">
                        <h3>📋 Información de la Canastilla</h3>

                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" required
                                   value="<?php echo isset($_SESSION['form_data']['nombre']) ? e($_SESSION['form_data']['nombre']) : e($canastilla['nombre']); ?>"
                                   placeholder="Ej: Canastilla 1 - BOVINOS DIEGO">
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3"
                                      placeholder="Descripción adicional de la canastilla..."><?php echo isset($_SESSION['form_data']['descripcion']) ? e($_SESSION['form_data']['descripcion']) : e($canastilla['descripcion']); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fecha_registro">Fecha de Registro *</label>
                                <input type="date" id="fecha_registro" name="fecha_registro" required
                                       value="<?php echo isset($_SESSION['form_data']['fecha_registro']) ? e($_SESSION['form_data']['fecha_registro']) : date('Y-m-d', strtotime($canastilla['fecha_registro'])); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
                        <a href="<?php echo BASE_URL; ?>/canastillas" class="btn btn-secondary">❌ Cancelar</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    document.querySelector('.cabra-form').addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value.trim();
        if (!nombre) {
            alert('El nombre de la canastilla es obligatorio');
            e.preventDefault();
            return;
        }
    });

    document.getElementById('fecha_registro').addEventListener('change', function() {
        const dateValue = this.value;
        if (dateValue) {
            const date = new Date(dateValue);
            const today = new Date();
            if (date > today) {
                alert('La fecha de registro no puede ser futura.');
                this.value = '';
            }
        }
    });
    </script>

    <?php
    if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }
    ?>
</body>
</html>
