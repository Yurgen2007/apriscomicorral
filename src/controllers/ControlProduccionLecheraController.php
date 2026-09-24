<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../models/ControlProduccionLechera.php';
require_once __DIR__ . '/../models/Cabras.php';
require_once __DIR__ . '/../services/ControlProduccionLecheraService.php';

class ControlProduccionLecheraController
{
    private $db;
    private $model;
    private $service;
    private $cabrasModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();

        $this->model = new ControlProduccionLechera($this->db);
        $this->service = new ControlProduccionLecheraService($this->db);
        $this->cabrasModel = new Cabra($this->db);
    }

    /**
     * Mostrar todos los controles
     */
    public function index()
    {
        $this->service->syncLactanciasWithSanitaryStatus();
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $selectedCabra = isset($_GET['id_cabra']) ? (int)$_GET['id_cabra'] : 0;
        $selectedLactancia = isset($_GET['id_lactancia']) ? (int)$_GET['id_lactancia'] : 0;
        $searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
        $fechaDesde = trim($_GET['fecha_desde'] ?? '');
        $fechaHasta = trim($_GET['fecha_hasta'] ?? '');
        $limit = 10;

        $cabras = $this->cabrasModel->getAll();

        $hasFilters = $searchTerm !== '' || $selectedCabra > 0 || $selectedLactancia > 0 || $fechaDesde !== '' || $fechaHasta !== '';
        if ($hasFilters) {
            $totalRegistros = $this->service->countFiltered($searchTerm, $selectedCabra, $selectedLactancia, $fechaDesde, $fechaHasta);
            $totalPages = $totalRegistros > 0 ? (int)ceil($totalRegistros / $limit) : 1;
            $currentPage = min($page, $totalPages);
            $offset = ($currentPage - 1) * $limit;
            $controles = $this->service->getPaginatedFiltered($searchTerm, $selectedCabra, $selectedLactancia, $fechaDesde, $fechaHasta, $limit, $offset);
        } else {
            $totalRegistros = $this->service->count();
            $totalPages = $totalRegistros > 0 ? (int)ceil($totalRegistros / $limit) : 1;
            $currentPage = min($page, $totalPages);
            $offset = ($currentPage - 1) * $limit;
            $controles = $this->service->getPaginated($limit, $offset);
        }

        $topProductoras = $this->service->getTop3Producers();
        $productoras = $this->service->getProducerCards();
        $lactancias = $this->service->getLactancias();

        require_once __DIR__ . '/../views/control_produccion_lechera/index.php';
    }

    public function producers()
    {
        $productoras = $this->service->getProducerCards();
        $resumenAnualCompleto = $this->service->getAnnualSummary();
        $aniosResumen = [];
        foreach ($resumenAnualCompleto as $resumen) {
            $aniosResumen[(int)$resumen['anio']] = (int)$resumen['anio'];
        }
        krsort($aniosResumen);

        $anioResumen = filter_input(INPUT_GET, 'anio', FILTER_VALIDATE_INT);
        $anioResumen = $anioResumen && isset($aniosResumen[$anioResumen]) ? $anioResumen : null;
        $paginaResumen = filter_input(INPUT_GET, 'pagina_resumen', FILTER_VALIDATE_INT);
        $paginaResumen = $paginaResumen && $paginaResumen > 0 ? $paginaResumen : 1;
        $registrosPorPaginaResumen = 9;

        $resumenFiltrado = $anioResumen
            ? array_values(array_filter($resumenAnualCompleto, static function ($resumen) use ($anioResumen) {
                return (int)$resumen['anio'] === $anioResumen;
            }))
            : $resumenAnualCompleto;
        $totalPaginasResumen = max(1, (int)ceil(count($resumenFiltrado) / $registrosPorPaginaResumen));
        $paginaResumen = min($paginaResumen, $totalPaginasResumen);
        $resumenAnual = array_slice(
            $resumenFiltrado,
            ($paginaResumen - 1) * $registrosPorPaginaResumen,
            $registrosPorPaginaResumen
        );
        require_once __DIR__ . '/../views/control_produccion_lechera/productoras.php';
    }

    public function byCabra($idCabra)
    {
        $idCabra = (int)$idCabra;
        $cabra = $this->cabrasModel->getByIdFull($idCabra);

        if (!$cabra) {
            $_SESSION['error'] = 'La cabra seleccionada no existe.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera');
            exit;
        }

        $controles = $this->service->getByCabra($idCabra);
        $lactancias = $this->service->getLactancias($idCabra);
        $aniosCabra = [];
        foreach ($controles as $control) {
            $aniosCabra[(int)date('Y', strtotime($control['fecha_registro']))] = true;
        }
        foreach ($lactancias as $lactancia) {
            $fechaLactancia = $lactancia['fecha_inicio'] ?: $lactancia['fecha_parto'];
            if ($fechaLactancia) {
                $aniosCabra[(int)date('Y', strtotime($fechaLactancia))] = true;
            }
        }
        $aniosCabra = array_keys($aniosCabra);
        rsort($aniosCabra);
        $anioCabra = filter_input(INPUT_GET, 'anio', FILTER_VALIDATE_INT);
        $anioCabra = $anioCabra && in_array($anioCabra, $aniosCabra, true) ? $anioCabra : null;
        if ($anioCabra) {
            $controles = array_values(array_filter($controles, static function ($control) use ($anioCabra) {
                return (int)date('Y', strtotime($control['fecha_registro'])) === $anioCabra;
            }));
            $lactancias = array_values(array_filter($lactancias, static function ($lactancia) use ($anioCabra) {
                $fecha = $lactancia['fecha_inicio'] ?: $lactancia['fecha_parto'];
                return $fecha && (int)date('Y', strtotime($fecha)) === $anioCabra;
            }));
        }
        $condicionActual = $this->service->getSanitaryConditionAtDate($idCabra, date('Y-m-d'));
        require_once __DIR__ . '/../views/control_produccion_lechera/cabra.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->service->syncLactanciasWithSanitaryStatus();
        $cabras = $this->cabrasModel->getAll();
        $lactancias = $this->service->getLactancias();

        require_once __DIR__ . '/../views/control_produccion_lechera/create.php';
    }

    public function sanitaryCondition()
    {
        $idCabra = (int)($_GET['id_cabra'] ?? 0);
        $fecha = trim($_GET['fecha'] ?? date('Y-m-d'));
        $condition = $idCabra && $this->isValidDate($fecha)
            ? $this->service->getSanitaryConditionAtDate($idCabra, $fecha)
            : null;
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($condition ?: ['condicion_especial' => null, 'fecha_control' => null]);
        exit;
    }

    /**
     * Guardar nuevo control
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/control-produccion-lechera');
            exit;
        }

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/create');
            exit;
        }

        $id_cabra = $_POST['id_cabra'] ?? '';
        $fecha_registro = trim($_POST['fecha_registro'] ?? '');
        $turno_ordeño = $_POST['turno_ordeño'] ?? '';
        $cantidad_litros = $_POST['cantidad_litros'] ?? '';

        if (
            empty($id_cabra) || !$this->isValidDate($fecha_registro) ||
            empty($turno_ordeño) ||
            $cantidad_litros === ''
        ) {
            $_SESSION['error'] = 'Cabra, lactancia, fecha, turno y cantidad son obligatorios y válidos.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/create');
            exit;
        }

        if (!in_array($turno_ordeño, ['MAÑANA', 'TARDE'], true)) {
            $_SESSION['error'] = 'El turno de ordeño seleccionado no es válido.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/create');
            exit;
        }

        if (!is_numeric($cantidad_litros) || $cantidad_litros < 0) {
            $_SESSION['error'] = 'La cantidad de litros debe ser un número no negativo.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/create');
            exit;
        }

        if (!$this->service->isLactatingAtDate((int)$id_cabra, $fecha_registro)) {
            $_SESSION['error'] = 'Solo se puede registrar leche cuando la condición sanitaria vigente es LACTANTE.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/create');
            exit;
        }

        $id_lactancia = $this->service->getOrCreateActiveLactancia((int)$id_cabra, $fecha_registro);

        $resultado = $this->service->create(
            (int)$id_cabra, (int)$id_lactancia, $fecha_registro,
            $turno_ordeño,
            (float)$cantidad_litros
        );

        if ($resultado) {
            $_SESSION['success'] = 'Control de producción registrado correctamente.';
        } else {
            $_SESSION['error'] = 'No fue posible registrar el control de producción.';
        }

        header('Location: ' . BASE_URL . '/control-produccion-lechera');
        exit;
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $control = $this->service->getById($id);

        if (!$control) {
            $_SESSION['error'] = 'El control de producción no existe.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera');
            exit;
        }

        $cabras = $this->cabrasModel->getAll();
        $lactancias = $this->service->getLactancias((int)$control['id_cabra']);
        require_once __DIR__ . '/../views/control_produccion_lechera/edit.php';
    }

    /**
     * Actualizar control
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/control-produccion-lechera');
            exit;
        }

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/' . $id . '/edit');
            exit;
        }

        $id_cabra = $_POST['id_cabra'] ?? '';
        $fecha_registro = trim($_POST['fecha_registro'] ?? '');
        $turno_ordeño = $_POST['turno_ordeño'] ?? '';
        $cantidad_litros = $_POST['cantidad_litros'] ?? '';

        if (
            empty($id_cabra) || !$this->isValidDate($fecha_registro) ||
            empty($turno_ordeño) ||
            $cantidad_litros === ''
        ) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/' . $id . '/edit');
            exit;
        }

        if (!in_array($turno_ordeño, ['MAÑANA', 'TARDE'], true)) {
            $_SESSION['error'] = 'Turno de ordeño inválido.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/' . $id . '/edit');
            exit;
        }

        if (!is_numeric($cantidad_litros) || $cantidad_litros < 0) {
            $_SESSION['error'] = 'La cantidad de litros debe ser no negativa.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/' . $id . '/edit');
            exit;
        }

        if (!$this->service->isLactatingAtDate((int)$id_cabra, $fecha_registro)) {
            $_SESSION['error'] = 'Solo se puede registrar leche cuando la condición sanitaria vigente es LACTANTE.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/' . $id . '/edit');
            exit;
        }

        $id_lactancia = (int)$control['id_lactancia'];
        $resultado = $this->service->update(
            (int)$id,
            (int)$id_cabra, (int)$id_lactancia, $fecha_registro,
            $turno_ordeño,
            (float)$cantidad_litros
        );

        if ($resultado) {
            $_SESSION['success'] = 'Control actualizado correctamente.';
        } else {
            $_SESSION['error'] = 'No fue posible actualizar el control.';
        }

        header('Location: ' . BASE_URL . '/control-produccion-lechera');
        exit;
    }

    /**
     * Eliminar control
     */
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/control-produccion-lechera');
            exit;
        }

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera');
            exit;
        }
        $resultado = $this->service->delete($id);

        if ($resultado) {
            $_SESSION['success'] = 'Control eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'No fue posible eliminar el control.';
        }

        header('Location: ' . BASE_URL . '/control-produccion-lechera');
        exit;
    }

    private function isValidDate($date)
    {
        $parsed = DateTime::createFromFormat('Y-m-d', $date);
        return $parsed && $parsed->format('Y-m-d') === $date;
    }

}