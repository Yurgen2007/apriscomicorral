<?php
// src/models/Pajilla.php
class Pajilla {
    private $db;

    public const TAMANO_050 = '0.50 ml';
    public const TAMANO_025 = '0.25 ml';

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDosisPorPajilla(string $tamano): int {
        return $tamano === self::TAMANO_025 ? 2 : 1;
    }

    public function calcularDosis(int $cantidad, string $tamano): int {
        return $cantidad * $this->getDosisPorPajilla($tamano);
    }

    public function calcularCantidadFromDosis(int $dosis, string $tamano): int {
        $multiplicador = $this->getDosisPorPajilla($tamano);
        return (int)ceil($dosis / $multiplicador);
    }

    public function getAll($includeZeroStock = true) {
        try {
            $sql = "SELECT p.*, c.nombre AS canastilla_nombre
                    FROM pajillas p
                    LEFT JOIN canastillas c ON p.id_canastilla = c.id_canastilla";

            if (!$includeZeroStock) {
                $sql .= " WHERE p.dosis_disponibles > 0";
            }

            $sql .= " ORDER BY p.fecha_registro DESC, p.nombre_ejemplar ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Pajillas: " . $e->getMessage());
            return [];
        }
    }

    public function getDisponibles() {
        try {
            $sql = "SELECT p.*, c.nombre AS canastilla_nombre
                    FROM pajillas p
                    LEFT JOIN canastillas c ON p.id_canastilla = c.id_canastilla
                    WHERE p.dosis_disponibles > 0
                    ORDER BY p.nombre_ejemplar ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Pajillas disponibles: " . $e->getMessage());
            return [];
        }
    }

    public function getByCanastilla($id_canastilla) {
        try {
            $sql = "SELECT p.*, c.nombre AS canastilla_nombre
                    FROM pajillas p
                    LEFT JOIN canastillas c ON p.id_canastilla = c.id_canastilla
                    WHERE p.id_canastilla = :id_canastilla
                    ORDER BY p.nombre_ejemplar ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_canastilla', $id_canastilla, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Pajillas by Canastilla: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        try {
            $sql = "SELECT p.*, c.nombre AS canastilla_nombre
                    FROM pajillas p
                    LEFT JOIN canastillas c ON p.id_canastilla = c.id_canastilla
                    WHERE p.id_pajilla = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Pajilla by ID: " . $e->getMessage());
            return false;
        }
    }

    public function create($data) {
        try {
            $dosis = $this->calcularDosis((int)$data['cantidad'], $data['tamano']);

            $sql = "INSERT INTO pajillas
                    (id_canastilla, nombre_ejemplar, registro_ejemplar, foto, tamano, cantidad, dosis_disponibles, observaciones, fecha_registro)
                    VALUES
                    (:id_canastilla, :nombre_ejemplar, :registro_ejemplar, :foto, :tamano, :cantidad, :dosis_disponibles, :observaciones, :fecha_registro)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_canastilla', $data['id_canastilla']);
            $stmt->bindParam(':nombre_ejemplar', $data['nombre_ejemplar']);
            $stmt->bindParam(':registro_ejemplar', $data['registro_ejemplar']);
            $stmt->bindParam(':foto', $data['foto']);
            $stmt->bindParam(':tamano', $data['tamano']);
            $stmt->bindParam(':cantidad', $data['cantidad']);
            $stmt->bindParam(':dosis_disponibles', $dosis);
            $stmt->bindParam(':observaciones', $data['observaciones']);
            $stmt->bindParam(':fecha_registro', $data['fecha_registro']);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating Pajilla: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $dosis = $this->calcularDosis((int)$data['cantidad'], $data['tamano']);

            $sql = "UPDATE pajillas SET
                        id_canastilla = :id_canastilla,
                        nombre_ejemplar = :nombre_ejemplar,
                        registro_ejemplar = :registro_ejemplar,
                        foto = :foto,
                        tamano = :tamano,
                        cantidad = :cantidad,
                        dosis_disponibles = :dosis_disponibles,
                        observaciones = :observaciones,
                        fecha_registro = :fecha_registro
                    WHERE id_pajilla = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':id_canastilla', $data['id_canastilla']);
            $stmt->bindParam(':nombre_ejemplar', $data['nombre_ejemplar']);
            $stmt->bindParam(':registro_ejemplar', $data['registro_ejemplar']);
            $stmt->bindParam(':foto', $data['foto']);
            $stmt->bindParam(':tamano', $data['tamano']);
            $stmt->bindParam(':cantidad', $data['cantidad']);
            $stmt->bindParam(':dosis_disponibles', $dosis);
            $stmt->bindParam(':observaciones', $data['observaciones']);
            $stmt->bindParam(':fecha_registro', $data['fecha_registro']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating Pajilla: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM pajillas WHERE id_pajilla = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting Pajilla: " . $e->getMessage());
            return false;
        }
    }

    public function descontarDosis($id_pajilla, $dosis_a_descontar = 1) {
        try {
            $sql = "SELECT * FROM pajillas WHERE id_pajilla = :id FOR UPDATE";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id_pajilla, PDO::PARAM_INT);
            $stmt->execute();
            $pajilla = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pajilla) {
                return false;
            }

            if ($pajilla['dosis_disponibles'] < $dosis_a_descontar) {
                return false;
            }

            $nuevas_dosis = $pajilla['dosis_disponibles'] - $dosis_a_descontar;
            $nueva_cantidad = $this->calcularCantidadFromDosis($nuevas_dosis, $pajilla['tamano']);

            $sql = "UPDATE pajillas
                    SET dosis_disponibles = :dosis_disponibles,
                        cantidad = :cantidad
                    WHERE id_pajilla = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':dosis_disponibles', $nuevas_dosis, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $nueva_cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id_pajilla, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error descontando dosis: " . $e->getMessage());
            return false;
        }
    }

    public function registrarVenta($id_pajilla, $cantidad_dosis, $registrado_por, $observaciones = null) {
        try {
            $sql = "SELECT * FROM pajillas WHERE id_pajilla = :id FOR UPDATE";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id_pajilla, PDO::PARAM_INT);
            $stmt->execute();
            $pajilla = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pajilla) {
                return false;
            }

            if ($pajilla['dosis_disponibles'] < $cantidad_dosis) {
                return false;
            }

            $nuevas_dosis = $pajilla['dosis_disponibles'] - $cantidad_dosis;
            $nueva_cantidad = $this->calcularCantidadFromDosis($nuevas_dosis, $pajilla['tamano']);

            $sql = "UPDATE pajillas
                    SET dosis_disponibles = :dosis_disponibles,
                        cantidad = :cantidad
                    WHERE id_pajilla = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':dosis_disponibles', $nuevas_dosis, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $nueva_cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id_pajilla, PDO::PARAM_INT);

            $stmt->execute();

            $sqlVenta = "INSERT INTO ventas_pajillas
                        (id_pajilla, cantidad_dosis, registrado_por, observaciones, fecha_venta)
                        VALUES (:id_pajilla, :cantidad_dosis, :registrado_por, :observaciones, NOW())";

            $stmtVenta = $this->db->prepare($sqlVenta);
            $stmtVenta->bindParam(':id_pajilla', $id_pajilla, PDO::PARAM_INT);
            $stmtVenta->bindParam(':cantidad_dosis', $cantidad_dosis, PDO::PARAM_INT);
            $stmtVenta->bindParam(':registrado_por', $registrado_por);
            $stmtVenta->bindParam(':observaciones', $observaciones);
            $stmtVenta->execute();

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error registrando venta: " . $e->getMessage());
            return false;
        }
    }

    public function getVentas($id_pajilla) {
        try {
            $sql = "SELECT v.*, u.nombre AS nombre_usuario
                    FROM ventas_pajillas v
                    LEFT JOIN usuarios u ON v.registrado_por = u.id
                    WHERE v.id_pajilla = :id
                    ORDER BY v.fecha_venta DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id_pajilla, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Ventas: " . $e->getMessage());
            return [];
        }
    }
}
