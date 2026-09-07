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
    <title>Editar Pajilla - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>✏️ Editar Pajilla</h1>
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
                <form method="POST" action="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla']; ?>/edit" enctype="multipart/form-data" class="cabra-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                    <div class="form-section">
                        <h3>📦 Información de la Pajilla</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_canastilla">Canastilla *</label>
                                <select id="id_canastilla" name="id_canastilla" required>
                                    <option value="">Seleccionar canastilla</option>
                                    <?php foreach ($canastillas as $canasta): ?>
                                        <option value="<?php echo $canasta['id_canastilla']; ?>"
                                            <?php echo (isset($_SESSION['form_data']['id_canastilla']) && $_SESSION['form_data']['id_canastilla'] == $canasta['id_canastilla'])
                                                ? 'selected'
                                                : ($pajilla['id_canastilla'] == $canasta['id_canastilla'] ? 'selected' : ''); ?>>
                                            <?php echo e($canasta['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tamano">Tamaño *</label>
                                <select id="tamano" name="tamano" required>
                                    <option value="">Seleccionar tamaño</option>
                                    <option value="0.50 ml" <?php echo (isset($_SESSION['form_data']['tamano']) && $_SESSION['form_data']['tamano'] === '0.50 ml') ? 'selected' : ($pajilla['tamano'] === '0.50 ml' ? 'selected' : ''); ?>>0.50 ml (1 dosis por pajilla)</option>
                                    <option value="0.25 ml" <?php echo (isset($_SESSION['form_data']['tamano']) && $_SESSION['form_data']['tamano'] === '0.25 ml') ? 'selected' : ($pajilla['tamano'] === '0.25 ml' ? 'selected' : ''); ?>>0.25 ml (2 dosis por pajilla)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nombre_ejemplar">Nombre del Ejemplar *</label>
                            <input type="text" id="nombre_ejemplar" name="nombre_ejemplar" required
                                   value="<?php echo isset($_SESSION['form_data']['nombre_ejemplar']) ? e($_SESSION['form_data']['nombre_ejemplar']) : e($pajilla['nombre_ejemplar']); ?>"
                                   placeholder="Ej: KONA ICE, BAYONET, ARESA...">
                        </div>

                        <div class="form-group">
                            <label for="registro_ejemplar">Registro / Identificación del Ejemplar</label>
                            <input type="text" id="registro_ejemplar" name="registro_ejemplar"
                                   value="<?php echo isset($_SESSION['form_data']['registro_ejemplar']) ? e($_SESSION['form_data']['registro_ejemplar']) : e($pajilla['registro_ejemplar']); ?>"
                                   placeholder="Ej: ABC123, DKGHA12345...">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="cantidad">Cantidad de Pájillas *</label>
                                <input type="number" id="cantidad" name="cantidad" min="0" required
                                       value="<?php echo isset($_SESSION['form_data']['cantidad']) ? e($_SESSION['form_data']['cantidad']) : e($pajilla['cantidad']); ?>">
                                <small class="form-help">
                                    Dosis actuales: <strong><?php echo e($pajilla['dosis_disponibles']); ?></strong> |
                                    No puede reducir por debajo de las dosis ya consumidas.
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="fecha_registro">Fecha de Registro *</label>
                                <input type="date" id="fecha_registro" name="fecha_registro" required
                                       value="<?php echo isset($_SESSION['form_data']['fecha_registro']) ? e($_SESSION['form_data']['fecha_registro']) : date('Y-m-d', strtotime($pajilla['fecha_registro'])); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Dosis Disponibles (calculadas)</label>
                                <div class="info-display">
                                    <?php
                                    $multiplicador = $pajilla['tamano'] === '0.25 ml' ? 2 : 1;
                                    $dosis_actuales = $pajilla['cantidad'] * $multiplicador;
                                    ?>
                                    Cantidad actual: <strong><?php echo e($pajilla['cantidad']); ?></strong> pajillas
                                    → <strong><?php echo e($dosis_actuales); ?></strong> dosis
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Dosis ya consumidas</label>
                                <div class="info-display">
                                    <?php echo e($pajilla['dosis_disponibles']); ?> dosis disponibles
                                    <br>
                                    Max posible: <?php echo e($pajilla['cantidad'] * $multiplicador); ?> dosis
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>📷 Fotografía del Ejemplar</h3>
                        <div class="form-group">
                            <label for="foto">Cambiar Foto del Ejemplar</label>
                            <input type="file" id="foto" name="foto" accept="image/*">
                            <small class="form-help">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB. (Opcional)</small>

                            <?php if (!empty($pajilla['foto'])): ?>
                                <div id="existing-photo" style="margin-top: 10px;">
                                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo e($pajilla['foto']); ?>" alt="Foto actual" style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                                    <p><small>Foto actual</small></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div id="preview-container" style="display: none;">
                            <img id="photo-preview" src="" alt="Vista previa" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>📝 Observaciones</h3>
                        <div class="form-group">
                            <textarea name="observaciones" id="observaciones" rows="3"
                                      placeholder="Observaciones adicionales sobre la pajilla..."><?php echo isset($_SESSION['form_data']['observaciones']) ? e($_SESSION['form_data']['observaciones']) : e($pajilla['observaciones']); ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
                        <a href="<?php echo BASE_URL; ?>/pajillas/<?php echo $pajilla['id_pajilla']; ?>" class="btn btn-secondary">❌ Cancelar</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('preview-container').style.display = 'none';
        }
    });

    document.querySelector('.cabra-form').addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre_ejemplar').value.trim();
        const canastilla = document.getElementById('id_canastilla').value;
        const tamano = document.getElementById('tamano').value;
        const cantidad = document.getElementById('cantidad').value;

        if (!nombre) {
            alert('El nombre del ejemplar es obligatorio');
            e.preventDefault();
            return;
        }
        if (!canastilla) {
            alert('Debe seleccionar una canastilla');
            e.preventDefault();
            return;
        }
        if (!tamano) {
            alert('Debe seleccionar el tamaño de la pajilla');
            e.preventDefault();
            return;
        }
        if (!cantidad || cantidad < 0) {
            alert('La cantidad no puede ser negativa');
            e.preventDefault();
            return;
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