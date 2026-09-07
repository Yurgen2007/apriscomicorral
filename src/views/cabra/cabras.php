<!DOCTYPE html>
<html lang="es">

<?php
$currentPage = isset($currentPage) ? (int)$currentPage : 1;
$totalPages = isset($totalPages) ? (int)$totalPages : 1;
$filtroLabels = [
    'prenada' => ' Preñada',
    'vacia' => 'Vacía',
    'hembra' => ' Hembra',
    'macho' => ' Macho',
    'lactante' => ' Lactante',
    'inseminada' => ' Inseminada'
];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cabras - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <style>
        /* === Filtro de cabras: integración con el diseño existente === */
        .search-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }

        .filter-dropdown {
            position: relative;
            display: inline-block;
        }

        .filter-dropdown__toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            font: inherit;
            color: #333;
            line-height: 1.2;
            min-height: 38px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .filter-dropdown__toggle:hover,
        .filter-dropdown.is-open .filter-dropdown__toggle {
            border-color: #4caf50;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, .15);
        }

        .filter-dropdown__toggle .caret {
            transition: transform .2s ease;
        }

        .filter-dropdown.is-open .filter-dropdown__toggle .caret {
            transform: rotate(180deg);
        }

        .filter-dropdown__menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 6px);
            min-width: 220px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            padding: 6px 0;
            z-index: 50;
        }

        .filter-dropdown.is-open .filter-dropdown__menu {
            display: block;
        }

        .filter-dropdown__option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            cursor: pointer;
            user-select: none;
            color: #333;
            font-size: 14px;
        }

        .filter-dropdown__option:hover {
            background: #f5f7f5;
        }

        .filter-dropdown__option input[type="radio"] {
            accent-color: #4caf50;
            margin: 0;
        }

        .filter-dropdown__label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #555;
            font-size: 14px;
            white-space: nowrap;
        }

        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px 20px;
            background: #fff;
            border-radius: 10px;
            color: #666;
        }

        @media (max-width: 600px) {
            .search-container {
                width: 100%;
            }

            .search-form {
                flex: 1 1 100%;
            }

            .filter-dropdown {
                flex: 1 1 100%;
            }

            .filter-dropdown__toggle {
                width: 100%;
                justify-content: space-between;
            }

            .filter-dropdown__menu {
                left: 0;
                right: 0;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 Gestión de Cabras</h1>
            <div class="search-container">
                <div class="search-form">

                    <svg class="search-icon"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>

                    <input
                        type="text"
                        id="buscadorCabras"
                        placeholder="Buscar animal por nombre..."
                        value="<?php echo isset($searchTerm) ? e($searchTerm) : ''; ?>"
                        autocomplete="off">


                </div>

                <div class="filter-dropdown" id="filtroCabrasDropdown">
                    <button type="button" class="filter-dropdown__toggle" id="filtroCabrasToggle" aria-haspopup="true" aria-expanded="false">
                        <span id="filtroCabrasLabel"><?php echo isset($filtro) && $filtro ? $filtroLabels[$filtro] ?? 'Todos' : 'Todos'; ?></span>
                        <svg class="caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <form id="filtroForm" method="GET" action="<?php echo BASE_URL; ?>/cabras">
                        <input type="hidden" name="filtro_cabra" id="filtroHiddenInput" value="<?php echo isset($filtro) ? e($filtro) : ''; ?>">
                        <div class="filter-dropdown__menu" role="menu" aria-label="Filtrar cabras">
                            <label class="filter-dropdown__option">
                                <input type="radio" name="filtro_cabra_radio" value="" <?php echo (!isset($filtro) || !$filtro) ? 'checked' : ''; ?>>
                                <span>Todos</span>
                            </label>
                            <label class="filter-dropdown__option">
                                <input type="radio" name="filtro_cabra_radio" value="prenada" <?php echo (isset($filtro) && $filtro === 'prenada') ? 'checked' : ''; ?>>
                                <span>Preñada</span>
                            </label>
                            <label class="filter-dropdown__option">
                                <input type="radio" name="filtro_cabra_radio" value="vacia" <?php echo (isset($filtro) && $filtro === 'vacia') ? 'checked' : ''; ?>>
                                <span>Vacía</span>
                            </label>
                            <label class="filter-dropdown__option">
                                <input type="radio" name="filtro_cabra_radio" value="hembra" <?php echo (isset($filtro) && $filtro === 'hembra') ? 'checked' : ''; ?>>
                                <span>Hembra</span>
                            </label>
                            <label class="filter-dropdown__option">
                                <input type="radio" name="filtro_cabra_radio" value="macho" <?php echo (isset($filtro) && $filtro === 'macho') ? 'checked' : ''; ?>>
                                <span>Macho</span>
                            </label>
                            <label class="filter-dropdown__option">
                                <input type="radio" name="filtro_cabra_radio" value="lactante" <?php echo (isset($filtro) && $filtro === 'lactante') ? 'checked' : ''; ?>>
                                <span>Lactante</span>
                            </label>
                            <label class="filter-dropdown__option">
                                <input type="radio" name="filtro_cabra_radio" value="inseminada" <?php echo (isset($filtro) && $filtro === 'inseminada') ? 'checked' : ''; ?>>
                                <span>Inseminada</span>
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </header>

        <main class="main-content">
            <!-- Mensajes de éxito o error -->
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



            <!-- Estadísticas rápidas -->
            <div class="stats-summary">
                <div class="stat-card">
                    <h3><?php echo isset($total) ? $total : 0; ?></h3>
                    <p>Total Cabras</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo isset($currentPage) ? $currentPage : 1; ?></h3>
                    <p>Página Actual</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo isset($totalPages) ? $totalPages : 1; ?></h3>
                    <p>Total Páginas</p>
                </div>
            </div>

            <!-- Lista de cabras -->
            <div class="cabras-grid" id="cabrasGrid">
                <?php if (!empty($cabras) && is_array($cabras)): ?>
                    <?php foreach ($cabras as $cabra):
                        $sexoNorm = isset($cabra['sexo']) ? strtoupper($cabra['sexo']) : '';
                        $esPrenada = isset($cabra['es_prenada']) ? (int)$cabra['es_prenada'] : 0;
                        $esVacia = isset($cabra['es_vacia']) ? (int)$cabra['es_vacia'] : 0;
                        $esLactante = isset($cabra['es_lactante']) ? (int)$cabra['es_lactante'] : 0;
                        $esInseminada = isset($cabra['es_inseminada']) ? (int)$cabra['es_inseminada'] : 0;
                    ?>
                        <div class="cabra-card"
                            data-sexo="<?php echo e($sexoNorm); ?>"
                            data-prenada="<?php echo $esPrenada; ?>"
                            data-vacia="<?php echo $esVacia; ?>"
                            data-lactante="<?php echo $esLactante; ?>"
                            data-inseminada="<?php echo $esInseminada; ?>">
                            <div class="cabra-photo">
                                <?php if (!empty($cabra['foto'])): ?>
                                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo e($cabra['foto']); ?>"
                                        alt="<?php echo e($cabra['nombre']); ?>" class="cabra-image">
                                <?php else: ?>
                                    <div class="no-photo">🐐</div>
                                <?php endif; ?>
                            </div>

                            <div class="cabra-info">
                                <h3><?php echo e($cabra['nombre']); ?></h3>
                                <p class="cabra-details">
                                    <span class="sex-badge <?php echo strtolower($cabra['sexo']); ?>">
                                        <?php echo $cabra['sexo'] === 'MACHO' ? '♂' : '♀'; ?> <?php echo e($cabra['sexo']); ?>
                                    </span>
                                </p>

                                <?php if (!empty($cabra['raza_nombre'])): ?>
                                    <p><strong>Raza:</strong> <?php echo e($cabra['raza_nombre']); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($cabra['color'])): ?>
                                    <p><strong>Color:</strong> <?php echo e($cabra['color']); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($cabra['fecha_nacimiento'])): ?>
                                    <p><strong>Nacimiento:</strong> <?php echo date('d/m/Y', strtotime($cabra['fecha_nacimiento'])); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($cabra['propietario_nombre'])): ?>
                                    <p><strong>Propietario:</strong> <?php echo e($cabra['propietario_nombre']); ?></p>
                                <?php endif; ?>

                                <p class="status-badge <?php echo strtolower($cabra['estado']); ?>">
                                    <?php echo e($cabra['estado']); ?>
                                </p>
                            </div>

                            <div class="cabra-actions">
                                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>"
                                    class="btn btn-sm btn-info">Ver</a>
                                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/edit"
                                    class="btn btn-sm btn-warning"> Editar</a>

                                <!-- Formulario inline para eliminación con confirmación JS -->
                                <form method="POST" action="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/delete"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('¿Estás seguro de eliminar la cabra <?php echo e($cabra['nombre']); ?>? Esta acción la marcará como INACTIVA.')">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="id" value="<?php echo $cabra['id_cabra']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>🐐 No hay cabras registradas</h3>
                        <p>Comienza agregando tu primera cabra al sistema.</p>
                        <a href="<?php echo BASE_URL; ?>/cabras/create" class="btn btn-primary">
                            ➕ Registrar Primera Cabra
                        </a>
                    </div>
                <?php endif; ?>

                <div class="no-results" id="cabrasNoResults" style="display:none;">
                    <h3>🐐 Sin resultados</h3>
                    <p>No hay cabras que coincidan con el filtro y/o la búsqueda actual.</p>
                </div>
            </div>

            <!-- Paginación -->
            <?php
            $baseUrl = BASE_URL . '/cabras';
            $filtroParam = isset($filtro) && $filtro ? '&filtro_cabra=' . urlencode($filtro) : '';
            $searchParam = isset($searchTerm) && $searchTerm ? '' : '';
            if (isset($searchTerm) && $searchTerm) {
                $baseUrl = BASE_URL . '/cabras/search';
                $filtroParam = isset($filtro) && $filtro ? '&filtro_cabra=' . urlencode($filtro) : '';
            }
            ?>
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?php echo $baseUrl; ?>?page=<?php echo $currentPage - 1; ?><?php echo $filtroParam; ?>"
                            class="btn btn-secondary">« Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="btn btn-primary current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="<?php echo $baseUrl; ?>?page=<?php echo $i; ?><?php echo $filtroParam; ?>"
                                class="btn btn-secondary"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?php echo $baseUrl; ?>?page=<?php echo $currentPage + 1; ?><?php echo $filtroParam; ?>"
                            class="btn btn-secondary">Siguiente »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script>
        (function() {
            var filtroForm = document.getElementById('filtroForm');
            var filtroHiddenInput = document.getElementById('filtroHiddenInput');
            var radios = document.querySelectorAll('input[name="filtro_cabra_radio"]');
            var baseUrl = '<?php echo BASE_URL; ?>/cabras';

            radios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        var valor = this.value;
                        if (valor === '') {
                            window.location.href = baseUrl;
                        } else {
                            window.location.href = baseUrl + '?filtro_cabra=' + encodeURIComponent(valor);
                        }
                    }
                });
            });

            var buscador = document.getElementById('buscadorCabras');
            if (buscador) {
                var searchUrl = '<?php echo BASE_URL; ?>/cabras/search';
                var listUrl = '<?php echo BASE_URL; ?>/cabras';

                function ejecutarBusqueda() {
                    var term = buscador.value.trim();
                    if (term.length > 0) {
                        window.location.href = searchUrl + '?term=' + encodeURIComponent(term);
                    } else {
                        window.location.href = listUrl;
                    }
                }

                var timeout = null;
                buscador.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(ejecutarBusqueda, 500);
                });
            }

            var dropdown = document.getElementById('filtroCabrasDropdown');
            var toggle = document.getElementById('filtroCabrasToggle');

            if (toggle && dropdown) {
                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var abierto = dropdown.classList.toggle('is-open');
                    toggle.setAttribute('aria-expanded', abierto ? 'true' : 'false');
                });

                document.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('is-open');
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        dropdown.classList.remove('is-open');
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        })();
    </script>

</html>