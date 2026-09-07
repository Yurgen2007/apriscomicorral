<?php
// src/controllers/EventoReproductivoController.php
require_once __DIR__ . '/../models/EventoReproductivo.php';
require_once __DIR__ . '/../models/Pajilla.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';


class EventoReproductivoController
{
    private $model;
    private $db;
    private $pajillaModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->model = new EventoReproductivo($this->db);
        $this->pajillaModel = new Pajilla($this->db);
    }

    private function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    private function redirectToLogin()
    {
        header("Location: " . BASE_URL . "/login");
        exit();
    }

    private function redirectToCabra($id)
    {
        header("Location: " . BASE_URL . "/cabras/" . $id);
        exit();
    }

    private function getIdFromUrl()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $index = array_search('eventos', $segments);
        return ($index !== false && isset($segments[$index + 1])) ? (int)$segments[$index + 1] : ($_GET['id'] ?? null);
    }

    public function index()
    {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $eventos = $this->model->getByCabra($id);
        $this->loadView('index', ['eventos' => $eventos, 'id_cabra' => $id]);
    }

    public function create()
    {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $csrf_token = generateCSRFToken();
        $sementales = $this->model->getSementalesDisponibles();
        $pajillas = $this->pajillaModel->getDisponibles();

        $this->loadView('create_edit', [
            'csrf_token' => $csrf_token,
            'id_cabra' => $id,
            'sementales' => $sementales,
            'pajillas' => $pajillas
        ]);
    }

    public function store()
    {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCabra($_POST['id_cabra']);
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            $this->redirectToCabra($data['id_cabra']);
        }

        $this->db->beginTransaction();
        try {
            $evento_id = $this->model->create($data);

            if (!$evento_id) {
                throw new Exception('Error al registrar el evento reproductivo');
            }

            // Si es inseminación y se seleccionó pajilla, descontar una dosis
            if ($data['tipo_evento'] === 'INSEMINACION' && !empty($data['id_pajilla'])) {
                $pajilla = $this->pajillaModel->getById($data['id_pajilla']);
                if (!$pajilla) {
                    throw new Exception('La pajilla seleccionada no existe');
                }
                if ($pajilla['dosis_disponibles'] <= 0) {
                    throw new Exception('La pajilla seleccionada no tiene dosis disponibles');
                }
                $descontado = $this->pajillaModel->descontarDosis($data['id_pajilla'], 1);
                if (!$descontado) {
                    throw new Exception('No se pudo descontar la dosis de la pajilla');
                }
            }

            $this->db->commit();
            $_SESSION['success'] = 'Evento registrado correctamente';
            $this->redirectToCabra($data['id_cabra']);
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = 'Error al registrar el evento: ' . $e->getMessage();
            $_SESSION['form_data'] = $data;
            $this->redirectToCabra($data['id_cabra']);
        }
    }

    public function edit()
    {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $evento = $this->model->getById($id);
        $csrf_token = generateCSRFToken();
        $sementales = $this->model->getSementalesDisponibles();
        $pajillas = $this->pajillaModel->getAll();

        $this->loadView('create_edit', [
            'evento' => $evento,
            'csrf_token' => $csrf_token,
            'id_cabra' => $evento['id_cabra'],
            'sementales' => $sementales,
            'pajillas' => $pajillas
        ]);
    }

    public function update()
    {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        $id = $this->getIdFromUrl();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCabra($_POST['id_cabra']);
        }

        $oldEvento = $this->model->getById($id);

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            $this->redirectToCabra($data['id_cabra']);
        }

        $this->db->beginTransaction();
        try {
            if (!$this->model->update($id, $data)) {
                throw new Exception('Error al actualizar el evento reproductivo');
            }

            // Gestión de dosis al editar
            $oldEraInseminacion = ($oldEvento && $oldEvento['tipo_evento'] === 'INSEMINACION' && !empty($oldEvento['id_pajilla']));
            $nuevaEsInseminacion = ($data['tipo_evento'] === 'INSEMINACION' && !empty($data['id_pajilla']));

            $oldPajilla = $oldEvento['id_pajilla'] ?? null;
            $newPajilla = $data['id_pajilla'] ?? null;

            // Caso 1: Antes era inseminación y ahora no, o cambió de pajilla
            if ($oldEraInseminacion && (!$nuevaEsInseminacion || $oldPajilla != $newPajilla)) {
                $pajillaOld = $this->pajillaModel->getById($oldPajilla);
                if ($pajillaOld) {
                    $dosisRestauradas = $pajillaOld['dosis_disponibles'] + 1;
                    $cantidadRestaurada = $this->pajillaModel->calcularCantidadFromDosis($dosisRestauradas, $pajillaOld['tamano']);
                    $this->restaurarDosis($oldPajilla, $dosisRestauradas, $cantidadRestaurada);
                }
            }

            // Caso 2: Ahora es inseminación con pajilla (y es diferente al original)
            if ($nuevaEsInseminacion && (!$oldEraInseminacion || $oldPajilla != $newPajilla)) {
                $pajillaNew = $this->pajillaModel->getById($newPajilla);
                if (!$pajillaNew) {
                    throw new Exception('La pajilla seleccionada no existe');
                }
                if ($pajillaNew['dosis_disponibles'] <= 0) {
                    throw new Exception('La pajilla seleccionada no tiene dosis disponibles');
                }
                $descontado = $this->pajillaModel->descontarDosis($newPajilla, 1);
                if (!$descontado) {
                    throw new Exception('No se pudo descontar la dosis de la pajilla');
                }
            }

            $this->db->commit();
            $_SESSION['success'] = 'Evento actualizado correctamente';
            $this->redirectToCabra($data['id_cabra']);
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = 'Error al actualizar el evento: ' . $e->getMessage();
            $_SESSION['form_data'] = $data;
            $this->redirectToCabra($data['id_cabra']);
        }
    }

    public function delete()
    {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        $id = $this->getIdFromUrl();
        $evento = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->db->beginTransaction();
            try {
                // Restaurar dosis si el evento era una inseminación con pajilla
                if ($evento && $evento['tipo_evento'] === 'INSEMINACION' && !empty($evento['id_pajilla'])) {
                    $pajilla = $this->pajillaModel->getById($evento['id_pajilla']);
                    if ($pajilla) {
                        $dosisRestauradas = $pajilla['dosis_disponibles'] + 1;
                        $cantidadRestaurada = $this->pajillaModel->calcularCantidadFromDosis($dosisRestauradas, $pajilla['tamano']);
                        $this->restaurarDosis($evento['id_pajilla'], $dosisRestauradas, $cantidadRestaurada);
                    }
                }

                $this->model->delete($id);
                $this->db->commit();
                $_SESSION['success'] = 'Evento eliminado correctamente';
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = 'Error al eliminar el evento: ' . $e->getMessage();
            }
        }

        $this->redirectToCabra($evento['id_cabra']);
    }

    private function getDataFromRequest(): array
    {
        return [
            'id_cabra' => $_POST['id_cabra'] ?? null,
            'fecha_evento' => $_POST['fecha_evento'] ?? null,
            'tipo_evento' => $_POST['tipo_evento'] ?? null,
            'id_semental' => $_POST['id_semental'] ?? null,
            'id_pajilla' => $_POST['id_pajilla'] ?? null,
            'observaciones' => $_POST['observaciones'] ?? null,
            'registrado_por' => $_SESSION['user_id'] ?? null
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if (empty($data['id_cabra'])) $errors[] = 'La cabra es requerida';
        if (empty($data['fecha_evento'])) $errors[] = 'La fecha del evento es requerida';
        if (empty($data['tipo_evento'])) $errors[] = 'El tipo de evento es requerido';

        // Validar pajilla para INSEMINACION
        if ($data['tipo_evento'] === 'INSEMINACION') {
            if (!empty($data['id_pajilla'])) {
                $pajilla = $this->pajillaModel->getById($data['id_pajilla']);
                if (!$pajilla) {
                    $errors[] = 'La pajilla seleccionada no existe';
                } elseif ($pajilla['dosis_disponibles'] <= 0) {
                    $errors[] = 'La pajilla seleccionada no tiene dosis disponibles';
                }
            } else {
                $errors[] = 'Debe seleccionar una pajilla para la inseminación';
            }
        }

        return $errors;
    }

    private function restaurarDosis($id_pajilla, $nuevas_dosis, $nueva_cantidad)
    {
        try {
            $sql = "UPDATE pajillas
                    SET dosis_disponibles = :dosis_disponibles,
                        cantidad = :cantidad
                    WHERE id_pajilla = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id_pajilla, PDO::PARAM_INT);
            $stmt->bindParam(':dosis_disponibles', $nuevas_dosis, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $nueva_cantidad, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error restaurando dosis: " . $e->getMessage());
            return false;
        }
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        $views = [
            'index' => __DIR__ . '/../views/eventos/index_eventos.php',
            'create_edit' => __DIR__ . '/../views/eventos/create_edit_evento.php'
        ];

        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada';
            $this->redirectToCabra($data['id_cabra'] ?? '');
        }
    }
}
