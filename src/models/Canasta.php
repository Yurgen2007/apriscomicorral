<?php
// src/models/Canasta.php
class Canasta {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        try {
            $sql = "SELECT * FROM canastillas ORDER BY nombre ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Canastillas: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        try {
            $sql = "SELECT * FROM canastillas WHERE id_canastilla = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Canasta by ID: " . $e->getMessage());
            return false;
        }
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO canastillas (nombre, descripcion, fecha_registro)
                    VALUES (:nombre, :descripcion, :fecha_registro)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':fecha_registro', $data['fecha_registro']);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating Canasta: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE canastillas SET
                        nombre = :nombre,
                        descripcion = :descripcion,
                        fecha_registro = :fecha_registro
                    WHERE id_canastilla = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':fecha_registro', $data['fecha_registro']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating Canasta: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM canastillas WHERE id_canastilla = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting Canasta: " . $e->getMessage());
            return false;
        }
    }

    public function getPajillaCount($id) {
        try {
            $sql = "SELECT COUNT(*) as total FROM pajillas WHERE id_canastilla = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error counting Pajillas in Canasta: " . $e->getMessage());
            return 0;
        }
    }
}
