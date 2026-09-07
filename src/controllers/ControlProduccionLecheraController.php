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
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $selectedCabra = isset($_GET['id_cabra']) ? (int)$_GET['id_cabra'] : 0;
        $searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
        $limit = 10;

        $cabras = $this->cabrasModel->getAll();

        if (!empty($searchTerm) || $selectedCabra > 0) {
            $totalRegistros = $this->service->countFiltered($searchTerm, $selectedCabra);
            $totalPages = $totalRegistros > 0 ? (int)ceil($totalRegistros / $limit) : 1;
            $currentPage = min($page, $totalPages);
            $offset = ($currentPage - 1) * $limit;
            $controles = $this->service->getPaginatedFiltered($searchTerm, $selectedCabra, $limit, $offset);
        } else {
            $totalRegistros = $this->service->count();
            $totalPages = $totalRegistros > 0 ? (int)ceil($totalRegistros / $limit) : 1;
            $currentPage = min($page, $totalPages);
            $offset = ($currentPage - 1) * $limit;
            $controles = $this->service->getPaginated($limit, $offset);
        }

        $topProductoras = $this->service->getTop3Producers();

        require_once __DIR__ . '/../views/control_produccion_lechera/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $cabras = $this->cabrasModel->getAll();

        require_once __DIR__ . '/../views/control_produccion_lechera/create.php';
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

        $id_cabra = $_POST['id_cabra'] ?? '';
        $turno_ordeño = $_POST['turno_ordeño'] ?? '';
        $cantidad_litros = $_POST['cantidad_litros'] ?? '';

        if (
            empty($id_cabra) ||
            empty($turno_ordeño) ||
            $cantidad_litros === ''
        ) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/create');
            exit;
        }

        if (!in_array($turno_ordeño, ['MAÑANA', 'TARDE'], true)) {
            $_SESSION['error'] = 'El turno de ordeño seleccionado no es válido.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/create');
            exit;
        }

        if (!is_numeric($cantidad_litros) || $cantidad_litros < 0) {
            $_SESSION['error'] = 'La cantidad de litros debe ser un número válido.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/create');
            exit;
        }

        $resultado = $this->service->create(
            (int)$id_cabra,
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
        $cabras = $this->cabrasModel->getAll();

        if (!$control) {
            $_SESSION['error'] = 'El control de producción no existe.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera');
            exit;
        }

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

        $id_cabra = $_POST['id_cabra'] ?? '';
        $turno_ordeño = $_POST['turno_ordeño'] ?? '';
        $cantidad_litros = $_POST['cantidad_litros'] ?? '';

        if (
            empty($id_cabra) ||
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
            $_SESSION['error'] = 'La cantidad de litros debe ser válida.';
            header('Location: ' . BASE_URL . '/control-produccion-lechera/' . $id . '/edit');
            exit;
        }

        $resultado = $this->service->update(
            (int)$id,
            (int)$id_cabra,
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

        $resultado = $this->service->delete($id);

        if ($resultado) {
            $_SESSION['success'] = 'Control eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'No fue posible eliminar el control.';
        }

        header('Location: ' . BASE_URL . '/control-produccion-lechera');
        exit;
    }
}