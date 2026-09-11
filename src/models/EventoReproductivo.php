<?php
// src/models/EventoReproductivo.php
class EventoReproductivo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getByCabra($id_cabra) {
        $sql = "SELECT e.*, c.nombre AS nombre_semental,
                       p.nombre_ejemplar AS nombre_pajilla,
                       (SELECT nombre FROM usuarios WHERE id = e.registrado_por) AS nombre_usuario
                FROM eventos_reproductivos e
                LEFT JOIN cabras c ON e.id_semental = c.id_cabra
                LEFT JOIN pajillas p ON e.id_pajilla = p.id_pajilla
                WHERE e.id_cabra = :id_cabra
                ORDER BY fecha_evento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cabra', $id_cabra, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_evento) {
        $sql = "SELECT * FROM eventos_reproductivos WHERE id_evento = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_evento, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO eventos_reproductivos (id_cabra, fecha_evento, tipo_evento, id_semental, id_pajilla, observaciones, registrado_por)
                VALUES (:id_cabra, :fecha_evento, :tipo_evento, :id_semental, :id_pajilla, :observaciones, :registrado_por)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_cabra' => $data['id_cabra'],
            ':fecha_evento' => $data['fecha_evento'],
            ':tipo_evento' => $data['tipo_evento'],
            ':id_semental' => !empty($data['id_semental']) ? $data['id_semental'] : null,
            ':id_pajilla' => !empty($data['id_pajilla']) ? $data['id_pajilla'] : null,
            ':observaciones' => $data['observaciones'],
            ':registrado_por' => $data['registrado_por']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE eventos_reproductivos SET
                    id_cabra = :id_cabra,
                    fecha_evento = :fecha_evento,
                    tipo_evento = :tipo_evento,
                    id_semental = :id_semental,
                    id_pajilla = :id_pajilla,
                    observaciones = :observaciones
                WHERE id_evento = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_cabra', $data['id_cabra']);
        $stmt->bindParam(':fecha_evento', $data['fecha_evento']);
        $stmt->bindParam(':tipo_evento', $data['tipo_evento']);

        $id_semental = !empty($data['id_semental']) ? $data['id_semental'] : null;
        $stmt->bindValue(':id_semental', $id_semental, PDO::PARAM_INT);

        $id_pajilla = !empty($data['id_pajilla']) ? $data['id_pajilla'] : null;
        $stmt->bindValue(':id_pajilla', $id_pajilla, PDO::PARAM_INT);

        $stmt->bindParam(':observaciones', $data['observaciones']);
        return $stmt->execute();
    }

    public function delete($id_evento) {
        $sql = "DELETE FROM eventos_reproductivos WHERE id_evento = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_evento, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getSementalesDisponibles() {
        $sql = "SELECT id_cabra, nombre FROM cabras WHERE sexo = 'MACHO' ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Nuevo: obtener controles sanitarios por cabra
    public function getControlesSanitariosByCabra($id_cabra) {
        $sql = "SELECT * FROM controles_sanitarios
                WHERE id_cabra = :id_cabra
                ORDER BY fecha_control DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cabra', $id_cabra, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
