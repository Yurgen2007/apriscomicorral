<?php
// src/controllers/PajillaController.php
require_once __DIR__ . '/../models/Pajilla.php';
require_once __DIR__ . '/../models/Canasta.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

class PajillaController {
    private $model;
    private $canastaModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->model = new Pajilla($this->db);
        $this->canastaModel = new Canasta($this->db);
    }

    private function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    private function redirectToLogin() {
        header("Location: " . BASE_URL . "/login");
        exit();
    }

    private function redirectToPajillas() {
        header("Location: " . BASE_URL . "/pajillas");
        exit();
    }

    private function getIdFromUrl() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $index = array_search('pajillas', $segments);
        return ($index !== false && isset($segments[$index + 1])) ? (int)$segments[$index + 1] : (int)($_GET['id'] ?? 0);
    }

    public function index() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $pajillas = $this->model->getAll();
        $this->loadView('index', ['pajillas' => $pajillas]);
    }

    public function create() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        $csrf_token = generateCSRFToken();
        $canastillas = $this->canastaModel->getAll();

        $this->loadView('create', [
            'csrf_token' => $csrf_token,
            'canastillas' => $canastillas
        ]);
    }

    public function store() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            header('Location: ' . BASE_URL . '/pajillas/create');
            exit();
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . '/pajillas/create');
            exit();
        }

        $fotoPath = $this->handleFotoUpload();
        $data['foto'] = $fotoPath;

        $this->db->beginTransaction();
        try {
            $id = $this->model->create($data);
            if ($id) {
                $this->db->commit();
                $_SESSION['success'] = 'Pajilla registrada correctamente';
                header('Location: ' . BASE_URL . '/pajillas');
                exit();
            } else {
                throw new Exception('Error al registrar la pajilla');
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            if ($fotoPath && file_exists(__DIR__ . '../../../uploads/pajillas/' . basename($fotoPath))) {
                unlink(__DIR__ . '../../../uploads/pajillas/' . basename($fotoPath));
            }
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . '/pajillas/create');
            exit();
        }
    }

    public function show() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $pajilla = $this->model->getById($id);

        if (!$pajilla) {
            $_SESSION['error'] = 'Pajilla no encontrada';
            $this->redirectToPajillas();
        }

        $ventas = $this->model->getVentas($id);

        $this->loadView('show', [
            'pajilla' => $pajilla,
            'ventas' => $ventas
        ]);
    }

    public function edit() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $pajilla = $this->model->getById($id);

        if (!$pajilla) {
            $_SESSION['error'] = 'Pajilla no encontrada';
            $this->redirectToPajillas();
        }

        $csrf_token = generateCSRFToken();
        $canastillas = $this->canastaModel->getAll();

        $this->loadView('edit', [
            'pajilla' => $pajilla,
            'csrf_token' => $csrf_token,
            'canastillas' => $canastillas
        ]);
    }

    public function update() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            header('Location: ' . BASE_URL . '/pajillas/' . $id . '/edit');
            exit();
        }

        $existing = $this->model->getById($id);
        if (!$existing) {
            $_SESSION['error'] = 'Pajilla no encontrada';
            $this->redirectToPajillas();
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data, $existing);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . '/pajillas/' . $id . '/edit');
            exit();
        }

        $fotoPath = $this->handleFotoUpload();
        $data['foto'] = $fotoPath ?? $existing['foto'];

        $this->db->beginTransaction();
        try {
            if ($this->model->update($id, $data)) {
                $this->db->commit();
                $_SESSION['success'] = 'Pajilla actualizada correctamente';
            } else {
                throw new Exception('Error al actualizar la pajilla');
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            if ($fotoPath && file_exists(__DIR__ . '../../../uploads/pajillas/' . basename($fotoPath))) {
                unlink(__DIR__ . '../../../uploads/pajillas/' . basename($fotoPath));
            }
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . '/pajillas/' . $id . '/edit');
            exit();
        }

        header('Location: ' . BASE_URL . '/pajillas');
        exit();
    }

    public function delete() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToPajillas();
        }

        $this->db->beginTransaction();
        try {
            if ($this->model->delete($id)) {
                $this->db->commit();
                $_SESSION['success'] = 'Pajilla eliminada correctamente';
            } else {
                throw new Exception('Error al eliminar la pajilla');
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = 'La pajilla no se pudo eliminar (puede estar referenciada en eventos o ventas)';
        }

        $this->redirectToPajillas();
    }

    public function venta() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $pajilla = $this->model->getById($id);

        if (!$pajilla) {
            $_SESSION['error'] = 'Pajilla no encontrada';
            $this->redirectToPajillas();
        }

        $csrf_token = generateCSRFToken();
        $this->loadView('venta', [
            'pajilla' => $pajilla,
            'csrf_token' => $csrf_token
        ]);
    }

    public function storeVenta() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToPajillas();
        }

        $id_pajilla = (int)($_POST['id_pajilla'] ?? 0);
        $cantidad_dosis = (int)($_POST['cantidad_dosis'] ?? 0);
        $observaciones = trim($_POST['observaciones'] ?? '');

        $pajilla = $this->model->getById($id_pajilla);

        if (!$pajilla) {
            $_SESSION['error'] = 'La pajilla seleccionada no existe';
            header('Location: ' . BASE_URL . '/pajillas/' . $id_pajilla . '/venta');
            exit();
        }

        if ($cantidad_dosis <= 0) {
            $_SESSION['error'] = 'La cantidad de dosis a vender debe ser mayor que 0';
            header('Location: ' . BASE_URL . '/pajillas/' . $id_pajilla . '/venta');
            exit();
        }

        if ($cantidad_dosis > $pajilla['dosis_disponibles']) {
            $_SESSION['error'] = 'No se pueden vender ' . $cantidad_dosis . ' dosis. Solo hay ' . $pajilla['dosis_disponibles'] . ' dosis disponibles.';
            header('Location: ' . BASE_URL . '/pajillas/' . $id_pajilla . '/venta');
            exit();
        }

        $this->db->beginTransaction();
        try {
            $venta_id = $this->model->registrarVenta(
                $id_pajilla,
                $cantidad_dosis,
                $_SESSION['user_id'],
                $observaciones ?: null
            );

            if ($venta_id) {
                $this->db->commit();
                $_SESSION['success'] = 'Venta registrada. Se descuentan ' . $cantidad_dosis . ' dosis de la pajilla.';
            } else {
                throw new Exception('Error al registrar la venta');
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/pajillas/' . $id_pajilla);
        exit();
    }

    public function getDisponiblesAjax() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        $pajillas = $this->model->getDisponibles();
        header('Content-Type: application/json');
        echo json_encode($pajillas);
        exit();
    }

    private function getDataFromRequest(): array {
        $cantidad = (int)($_POST['cantidad'] ?? 0);
        $tamano = $_POST['tamano'] ?? '';
        $fecha_registro = $_POST['fecha_registro'] ?? date('Y-m-d H:i:s');

        return [
            'id_canastilla' => (int)($_POST['id_canastilla'] ?? 0),
            'nombre_ejemplar' => trim($_POST['nombre_ejemplar'] ?? ''),
            'registro_ejemplar' => trim($_POST['registro_ejemplar'] ?? ''),
            'foto' => '',
            'tamano' => $tamano,
            'cantidad' => $cantidad,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'fecha_registro' => $fecha_registro
        ];
    }

    private function validate(array $data, $existing = null): array {
        $errors = [];

        if (empty($data['id_canastilla']) || $data['id_canastilla'] <= 0) {
            $errors[] = 'Debe seleccionar una canastilla válida.';
        } else {
            if (!$this->canastaModel->getById($data['id_canastilla'])) {
                $errors[] = 'La canastilla seleccionada no existe.';
            }
        }

        if (empty($data['nombre_ejemplar']) || strlen(trim($data['nombre_ejemplar'])) < 2) {
            $errors[] = 'El nombre del ejemplar es obligatorio y debe tener al menos 2 caracteres.';
        }

        $valid_tamanos = [Pajilla::TAMANO_050, Pajilla::TAMANO_025];
        if (!in_array($data['tamano'], $valid_tamanos)) {
            $errors[] = 'El tamaño seleccionado no es válido. Solo se permiten 0.50 ml o 0.25 ml.';
        }

        if ($data['cantidad'] <= 0) {
            $errors[] = 'La cantidad debe ser mayor que 0.';
        }

        if ($existing) {
            $nuevas_dosis_esperadas = $this->model->calcularDosis($data['cantidad'], $data['tamano']);
            if ($existing['dosis_disponibles'] > $nuevas_dosis_esperadas) {
                $errors[] = 'No se puede reducir la cantidad por debajo de las dosis ya consumidas. Dosis actuales: ' . $existing['dosis_disponibles'] . ', dosis máximas con la nueva cantidad: ' . $nuevas_dosis_esperadas . '.';
            }
        }

        return $errors;
    }

    private function handleFotoUpload(): ?string {
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Error al subir la fotografía.';
            return null;
        }

        $file = $_FILES['foto'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = 'Tipo de archivo no permitido. Solo se permiten imágenes JPG, PNG y GIF.';
            return null;
        }

        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'El archivo es demasiado grande. Máximo 5MB.';
            return null;
        }

        $uploadDir = __DIR__ . '/../../uploads/pajillas/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid('pajilla_') . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'pajillas/' . $filename;
        }

        $_SESSION['error'] = 'Error al guardar la fotografía.';
        return null;
    }

    private function loadView($view, $data = []) {
        extract($data);

        $views = [
            'index' => __DIR__ . '/../views/pajillas/index.php',
            'create' => __DIR__ . '/../views/pajillas/create.php',
            'edit' => __DIR__ . '/../views/pajillas/edit.php',
            'show' => __DIR__ . '/../views/pajillas/show.php',
            'venta' => __DIR__ . '/../views/pajillas/venta.php'
        ];

        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada: ' . $view;
            $this->redirectToPajillas();
        }
    }
}
