<?php

class ControlProduccionLechera
{
    private $conn;
    private $table = 'control_produccion_lechera';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Obtener todos los controles de producción
     */
    public function getAll()
    {
        $query = "
            SELECT 
                cpl.id_control_produccion,
                cpl.id_cabra,
                cpl.turno_ordeño,
                cpl.cantidad_litros,
                cpl.fecha_registro,
                c.nombre AS nombre_cabra
            FROM {$this->table} cpl
            INNER JOIN cabras c 
                ON cpl.id_cabra = c.id_cabra
            ORDER BY cpl.fecha_registro DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener un control por ID
     */
    public function getById($id)
    {
        $query = "
            SELECT 
                cpl.id_control_produccion,
                cpl.id_cabra,
                cpl.turno_ordeño,
                cpl.cantidad_litros,
                cpl.fecha_registro,
                c.nombre AS nombre_cabra
            FROM {$this->table} cpl
            INNER JOIN cabras c 
                ON cpl.id_cabra = c.id_cabra
            WHERE cpl.id_control_produccion = :id
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener producción de una cabra
     */
    public function getByCabra($id_cabra)
    {
        $query = "
            SELECT 
                cpl.id_control_produccion,
                cpl.id_cabra,
                cpl.turno_ordeño,
                cpl.cantidad_litros,
                cpl.fecha_registro,
                c.nombre AS nombre_cabra
            FROM {$this->table} cpl
            INNER JOIN cabras c 
                ON cpl.id_cabra = c.id_cabra
            WHERE cpl.id_cabra = :id_cabra
            ORDER BY cpl.fecha_registro DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cabra', $id_cabra, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated($limit, $offset)
    {
        $query = "
            SELECT 
                cpl.id_control_produccion,
                cpl.id_cabra,
                cpl.turno_ordeño,
                cpl.cantidad_litros,
                cpl.fecha_registro,
                c.nombre AS nombre_cabra
            FROM {$this->table} cpl
            INNER JOIN cabras c 
                ON cpl.id_cabra = c.id_cabra
            ORDER BY cpl.fecha_registro DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedFiltered($searchTerm, $id_cabra, $limit, $offset)
    {
        $where = [];
        $params = [
            ':limit' => $limit,
            ':offset' => $offset
        ];

        if ($id_cabra > 0) {
            $where[] = 'cpl.id_cabra = :id_cabra';
            $params[':id_cabra'] = $id_cabra;
        }

        if (!empty($searchTerm)) {
            $where[] = 'c.nombre LIKE :search_term';
            $params[':search_term'] = '%' . trim($searchTerm) . '%';
        }

        $sql = "
            SELECT 
                cpl.id_control_produccion,
                cpl.id_cabra,
                cpl.turno_ordeño,
                cpl.cantidad_litros,
                cpl.fecha_registro,
                c.nombre AS nombre_cabra
            FROM {$this->table} cpl
            INNER JOIN cabras c 
                ON cpl.id_cabra = c.id_cabra";

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY cpl.fecha_registro DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === ':limit' || $key === ':offset') {
                $stmt->bindValue($key, (int)$value, PDO::PARAM_INT);
            } elseif ($key === ':id_cabra') {
                $stmt->bindValue($key, (int)$value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedByCabra($id_cabra, $limit, $offset)
    {
        $query = "
            SELECT 
                cpl.id_control_produccion,
                cpl.id_cabra,
                cpl.turno_ordeño,
                cpl.cantidad_litros,
                cpl.fecha_registro,
                c.nombre AS nombre_cabra
            FROM {$this->table} cpl
            INNER JOIN cabras c 
                ON cpl.id_cabra = c.id_cabra
            WHERE cpl.id_cabra = :id_cabra
            ORDER BY cpl.fecha_registro DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cabra', $id_cabra, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function countFiltered($searchTerm, $id_cabra)
    {
        $where = [];
        $params = [];

        if ($id_cabra > 0) {
            $where[] = 'id_cabra = :id_cabra';
            $params[':id_cabra'] = (int)$id_cabra;
        }

        if (!empty($searchTerm)) {
            $where[] = 'id_cabra IN (SELECT id_cabra FROM cabras WHERE nombre LIKE :search_term)';
            $params[':search_term'] = '%' . trim($searchTerm) . '%';
        }

        $query = "SELECT COUNT(*) AS total FROM {$this->table}";
        if (!empty($where)) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            if ($key === ':id_cabra') {
                $stmt->bindValue($key, (int)$value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function countByCabra($id_cabra)
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->table} WHERE id_cabra = :id_cabra";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cabra', $id_cabra, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    /**
     * Obtener las 3 cabras con mayor producción total
     */
    public function getTop3Producers()
    {
        $query = "
            SELECT
                c.id_cabra,
                c.nombre AS nombre_cabra,
                c.foto,
                SUM(cpl.cantidad_litros) AS total_litros
            FROM {$this->table} cpl
            INNER JOIN cabras c
                ON cpl.id_cabra = c.id_cabra
            GROUP BY c.id_cabra, c.nombre, c.foto
            ORDER BY total_litros DESC, c.nombre ASC
            LIMIT 3
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crear nuevo control
     * La fecha se genera automáticamente en MySQL
     */
    public function create($id_cabra, $turno_ordeño, $cantidad_litros)
    {
        $query = "
            INSERT INTO {$this->table}
            (
                id_cabra,
                turno_ordeño,
                cantidad_litros
            )
            VALUES
            (
                ?,
                ?,
                ?
            )
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $id_cabra,
            $turno_ordeño,
            $cantidad_litros
        ]);
    }

    /**
     * Actualizar control
     */
    public function update($id, $id_cabra, $turno_ordeño, $cantidad_litros)
    {
        $query = "
            UPDATE {$this->table}
            SET
                id_cabra = ?,
                turno_ordeño = ?,
                cantidad_litros = ?
            WHERE id_control_produccion = ?
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $id_cabra,
            $turno_ordeño,
            $cantidad_litros,
            $id
        ]);
    }

    /**
     * Eliminar control
     */
    public function delete($id)
    {
        $query = "
            DELETE FROM {$this->table}
            WHERE id_control_produccion = :id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}