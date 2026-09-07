<?php
// src/controllers/CanastaController.php
require_once __DIR__ . '/../models/Canasta.php';
require_once __DIR__ . '/../models/Pajilla.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

class CanastaController {
    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->model = new Canasta($this->db);
    }

    private function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    private function redirectToLogin() {
        header("Location: " . BASE_URL . "/login");
        exit();
    }

    private function redirectToCanastillas() {
        header("Location: " . BASE_URL . "/canastillas");
        exit();
    }

    private function getIdFromUrl() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $index = array_search('canastillas', $segments);
        return ($index !== false && isset($segments[$index + 1])) ? (int)$segments[$index + 1] : (int)($_GET['id'] ?? 0);
    }

    public function index() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $canastillas = $this->model->getAll();
        foreach ($canastillas as &$c) {
            $c['pajilla_count'] = $this->model->getPajillaCount($c['id_canastilla']);
        }
        $this->loadView('index', ['canastillas' => $canastillas]);
    }

    public function create() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $csrf_token = generateCSRFToken();
        $this->loadView('create', ['csrf_token' => $csrf_token]);
    }

    public function store() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCanastillas();
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . '/canastillas/create');
            exit();
        }

        if ($this->model->create($data)) {
            $_SESSION['success'] = 'Canastilla registrada correctamente';
        } else {
            $_SESSION['error'] = 'Error al registrar la canastilla';
        }
        $this->redirectToCanastillas();
    }

    public function show() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $canastilla = $this->model->getById($id);

        if (!$canastilla) {
            $_SESSION['error'] = 'Canastilla no encontrada';
            $this->redirectToCanastillas();
        }

        $pajillaModel = new Pajilla($this->db);
        $pajillas = $pajillaModel->getByCanastilla($id);

        $this->loadView('show', [
            'canastilla' => $canastilla,
            'pajillas' => $pajillas
        ]);
    }

    public function edit() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $canastilla = $this->model->getById($id);

        if (!$canastilla) {
            $_SESSION['error'] = 'Canastilla no encontrada';
            $this->redirectToCanastillas();
        }

        $csrf_token = generateCSRFToken();
        $this->loadView('edit', [
            'canastilla' => $canastilla,
            'csrf_token' => $csrf_token
        ]);
    }

    public function update() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCanastillas();
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . '/canastillas/' . $id . '/edit');
            exit();
        }

        if ($this->model->update($id, $data)) {
            $_SESSION['success'] = 'Canastilla actualizada correctamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar la canastilla';
        }
        $this->redirectToCanastillas();
    }

    public function delete() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCanastillas();
        }

        if ($this->model->delete($id)) {
            $_SESSION['success'] = 'Canastilla eliminada correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la canastilla (puede tener pajillas asociadas)';
        }
        $this->redirectToCanastillas();
    }

    private function getDataFromRequest(): array {
        return [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'fecha_registro' => $_POST['fecha_registro'] ?? date('Y-m-d H:i:s')
        ];
    }

    private function validate(array $data): array {
        $errors = [];

        if (empty($data['nombre']) || strlen(trim($data['nombre'])) < 2) {
            $errors[] = 'El nombre es obligatorio y debe tener al menos 2 caracteres.';
        }

        if (empty($data['fecha_registro'])) {
            $errors[] = 'La fecha de registro es obligatoria.';
        }

        return $errors;
    }

    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../../includes/functions.php';

        $views = [
            'index' => __DIR__ . '/../views/canastillas/index.php',
            'create' => __DIR__ . '/../views/canastillas/create.php',
            'edit' => __DIR__ . '/../views/canastillas/edit.php',
            'show' => __DIR__ . '/../views/canastillas/show.php'
        ];

        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada: ' . $view;
            $this->redirectToCanastillas();
        }
    }
}
