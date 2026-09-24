<?php

class ControlProduccionLechera
{
    private $conn;
    private $table = 'control_produccion_lechera';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function selectSql()
    {
        return "SELECT cpl.id_control_produccion, cpl.id_cabra, cpl.id_lactancia,
                       cpl.turno_ordeño, cpl.cantidad_litros, cpl.fecha_registro,
                       c.nombre AS nombre_cabra, l.numero_lactancia,
                       l.fecha_inicio AS lactancia_inicio,
                       l.fecha_fin AS lactancia_fin, l.estado AS lactancia_estado,
                       (
                           SELECT cs.condicion_especial
                           FROM controles_sanitarios cs
                           WHERE cs.id_cabra = cpl.id_cabra
                             AND DATE(cs.fecha_control) <= DATE(cpl.fecha_registro)
                           ORDER BY cs.fecha_control DESC, cs.id_control DESC
                           LIMIT 1
                       ) AS condicion_sanitaria
                FROM {$this->table} cpl
                INNER JOIN cabras c ON c.id_cabra = cpl.id_cabra
                LEFT JOIN lactancias l ON l.id_lactancia = cpl.id_lactancia";
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare($this->selectSql() . ' ORDER BY cpl.fecha_registro DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare($this->selectSql() . ' WHERE cpl.id_control_produccion = :id LIMIT 1');
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCabra($idCabra)
    {
        $stmt = $this->conn->prepare($this->selectSql() . ' WHERE cpl.id_cabra = :id_cabra ORDER BY cpl.fecha_registro DESC');
        $stmt->execute([':id_cabra' => (int)$idCabra]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated($limit, $offset)
    {
        $stmt = $this->conn->prepare($this->selectSql() . ' ORDER BY cpl.fecha_registro DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedFiltered($search, $idCabra, $idLactancia, $fechaDesde, $fechaHasta, $limit, $offset)
    {
        $where = [];
        $params = [];
        if ($idCabra > 0) {
            $where[] = 'cpl.id_cabra = :id_cabra';
            $params[':id_cabra'] = [(int)$idCabra, PDO::PARAM_INT];
        }
        if ($idLactancia > 0) {
            $where[] = 'cpl.id_lactancia = :id_lactancia';
            $params[':id_lactancia'] = [(int)$idLactancia, PDO::PARAM_INT];
        }
        if ($search !== '') {
            $where[] = 'c.nombre LIKE :search';
            $params[':search'] = ['%' . $search . '%', PDO::PARAM_STR];
        }
        if ($fechaDesde !== '') {
            $where[] = 'DATE(cpl.fecha_registro) >= :fecha_desde';
            $params[':fecha_desde'] = [$fechaDesde, PDO::PARAM_STR];
        }
        if ($fechaHasta !== '') {
            $where[] = 'DATE(cpl.fecha_registro) <= :fecha_hasta';
            $params[':fecha_hasta'] = [$fechaHasta, PDO::PARAM_STR];
        }
        $sql = $this->selectSql();
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY cpl.fecha_registro DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value[0], $value[1]);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        return (int)$this->conn->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }

    public function countFiltered($search, $idCabra, $idLactancia = 0, $fechaDesde = '', $fechaHasta = '')
    {
        $where = [];
        $params = [];
        if ($idCabra > 0) {
            $where[] = 'cpl.id_cabra = :id_cabra';
            $params[':id_cabra'] = [(int)$idCabra, PDO::PARAM_INT];
        }
        if ($idLactancia > 0) {
            $where[] = 'cpl.id_lactancia = :id_lactancia';
            $params[':id_lactancia'] = [(int)$idLactancia, PDO::PARAM_INT];
        }
        if ($search !== '') {
            $where[] = 'c.nombre LIKE :search';
            $params[':search'] = ['%' . $search . '%', PDO::PARAM_STR];
        }
        if ($fechaDesde !== '') {
            $where[] = 'DATE(cpl.fecha_registro) >= :fecha_desde';
            $params[':fecha_desde'] = [$fechaDesde, PDO::PARAM_STR];
        }
        if ($fechaHasta !== '') {
            $where[] = 'DATE(cpl.fecha_registro) <= :fecha_hasta';
            $params[':fecha_hasta'] = [$fechaHasta, PDO::PARAM_STR];
        }
        $sql = "SELECT COUNT(*) FROM {$this->table} cpl INNER JOIN cabras c ON c.id_cabra = cpl.id_cabra";
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value[0], $value[1]);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getTop3Producers()
    {
        $stmt = $this->conn->query(
            "SELECT c.id_cabra, c.nombre AS nombre_cabra, c.foto,
                    SUM(cpl.cantidad_litros) AS total_litros
             FROM {$this->table} cpl
             INNER JOIN cabras c ON c.id_cabra = cpl.id_cabra
             GROUP BY c.id_cabra, c.nombre, c.foto
             ORDER BY total_litros DESC, c.nombre ASC LIMIT 3"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProducerCards()
    {
        $stmt = $this->conn->query(
            "SELECT c.id_cabra, c.nombre AS nombre_cabra, c.foto,
                    SUM(cpl.cantidad_litros) AS total_litros,
                    COUNT(cpl.id_control_produccion) AS total_registros,
                    (
                        SELECT l.numero_lactancia
                        FROM lactancias l
                        WHERE l.id_cabra = c.id_cabra AND l.estado = 'EN LACTANCIA'
                        ORDER BY l.numero_lactancia DESC
                        LIMIT 1
                    ) AS lactancia_activa
             FROM {$this->table} cpl
             INNER JOIN cabras c ON c.id_cabra = cpl.id_cabra
             GROUP BY c.id_cabra, c.nombre, c.foto
             ORDER BY c.nombre ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnnualSummary()
    {
        $stmt = $this->conn->query(
            "SELECT anio, id_cabra, nombre_cabra, condicion_sanitaria,
                    SUM(cantidad_litros) AS total_litros,
                    MAX(cantidad_litros) AS produccion_maxima,
                    MAX(CASE WHEN cantidad_litros = pico_grupo
                             THEN fecha_registro END) AS fecha_produccion_maxima,
                    COUNT(*) AS total_registros
             FROM (
                 SELECT YEAR(cpl.fecha_registro) AS anio, cpl.id_cabra,
                        c.nombre AS nombre_cabra, cpl.cantidad_litros,
                        cpl.fecha_registro,
                        COALESCE((
                            SELECT cs.condicion_especial
                            FROM controles_sanitarios cs
                            WHERE cs.id_cabra = cpl.id_cabra
                              AND DATE(cs.fecha_control) <= DATE(cpl.fecha_registro)
                            ORDER BY cs.fecha_control DESC, cs.id_control DESC
                            LIMIT 1
                        ), 'SIN CONTROL') AS condicion_sanitaria,
                        (
                            SELECT MAX(cpl2.cantidad_litros)
                            FROM {$this->table} cpl2
                            WHERE cpl2.id_cabra = cpl.id_cabra
                              AND YEAR(cpl2.fecha_registro) = YEAR(cpl.fecha_registro)
                              AND COALESCE((
                                  SELECT cs2.condicion_especial
                                  FROM controles_sanitarios cs2
                                  WHERE cs2.id_cabra = cpl2.id_cabra
                                    AND DATE(cs2.fecha_control) <= DATE(cpl2.fecha_registro)
                                  ORDER BY cs2.fecha_control DESC, cs2.id_control DESC
                                  LIMIT 1
                              ), 'SIN CONTROL') = COALESCE((
                                  SELECT cs3.condicion_especial
                                  FROM controles_sanitarios cs3
                                  WHERE cs3.id_cabra = cpl.id_cabra
                                    AND DATE(cs3.fecha_control) <= DATE(cpl.fecha_registro)
                                  ORDER BY cs3.fecha_control DESC, cs3.id_control DESC
                                  LIMIT 1
                              ), 'SIN CONTROL')
                        ) AS pico_grupo
                 FROM {$this->table} cpl
                 INNER JOIN cabras c ON c.id_cabra = cpl.id_cabra
             ) resumen
             GROUP BY anio, id_cabra, nombre_cabra, condicion_sanitaria
             ORDER BY anio DESC, total_litros DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSanitaryConditionAtDate($idCabra, $fecha)
    {
        $stmt = $this->conn->prepare(
            "SELECT condicion_especial, fecha_control
             FROM controles_sanitarios
             WHERE id_cabra = :id_cabra AND DATE(fecha_control) <= :fecha
             ORDER BY fecha_control DESC, id_control DESC
             LIMIT 1"
        );
        $stmt->execute([':id_cabra' => (int)$idCabra, ':fecha' => $fecha]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getTotalsBySanitaryCondition()
    {
        $stmt = $this->conn->query(
            "SELECT COALESCE(condicion_sanitaria, 'SIN CONTROL') AS condicion_sanitaria,
                    SUM(cantidad_litros) AS total_litros,
                    COUNT(*) AS total_registros
             FROM (
                 SELECT cpl.cantidad_litros,
                        (
                            SELECT cs.condicion_especial
                            FROM controles_sanitarios cs
                            WHERE cs.id_cabra = cpl.id_cabra
                              AND DATE(cs.fecha_control) <= DATE(cpl.fecha_registro)
                            ORDER BY cs.fecha_control DESC, cs.id_control DESC
                            LIMIT 1
                        ) AS condicion_sanitaria
                 FROM {$this->table} cpl
             ) produccion
             GROUP BY condicion_sanitaria
             ORDER BY total_litros DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($idCabra, $idLactancia, $fechaRegistro, $turno, $cantidad)
    {
        if (!$idLactancia) {
            $stmt = $this->conn->prepare(
                "INSERT INTO {$this->table}
                 (id_cabra, id_lactancia, fecha_registro, turno_ordeño, cantidad_litros)
                 VALUES (:id_cabra, NULL, :fecha_registro, :turno, :cantidad)"
            );
            return $stmt->execute([
                ':id_cabra' => (int)$idCabra,
                ':fecha_registro' => $fechaRegistro,
                ':turno' => $turno,
                ':cantidad' => $cantidad
            ]);
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table}
             (id_cabra, id_lactancia, fecha_registro, turno_ordeño, cantidad_litros)
             SELECT :id_cabra, l.id_lactancia, :fecha_registro, :turno, :cantidad
             FROM lactancias l
             WHERE l.id_lactancia = :id_lactancia AND l.id_cabra = :id_cabra_2
               AND l.estado = 'EN LACTANCIA'
               AND :fecha_validacion >= l.fecha_inicio
               AND (l.fecha_fin IS NULL OR :fecha_validacion_2 <= l.fecha_fin)"
        );
        return $stmt->execute([
            ':id_cabra' => (int)$idCabra,
            ':id_lactancia' => (int)$idLactancia,
            ':fecha_registro' => $fechaRegistro,
            ':turno' => $turno,
            ':cantidad' => $cantidad,
            ':id_cabra_2' => (int)$idCabra,
            ':fecha_validacion' => $fechaRegistro,
            ':fecha_validacion_2' => $fechaRegistro
        ]);
    }

    public function update($id, $idCabra, $idLactancia, $fechaRegistro, $turno, $cantidad)
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} cpl
             INNER JOIN lactancias l ON l.id_lactancia = :id_lactancia
             SET cpl.id_cabra = :id_cabra, cpl.id_lactancia = l.id_lactancia,
                 cpl.fecha_registro = :fecha_registro, cpl.turno_ordeño = :turno,
                 cpl.cantidad_litros = :cantidad
             WHERE cpl.id_control_produccion = :id AND l.id_cabra = :id_cabra_2
               AND l.estado = 'EN LACTANCIA'
               AND :fecha_validacion >= l.fecha_inicio
               AND (l.fecha_fin IS NULL OR :fecha_validacion_2 <= l.fecha_fin)"
        );
        return $stmt->execute([
            ':id' => (int)$id, ':id_cabra' => (int)$idCabra,
            ':id_lactancia' => (int)$idLactancia, ':fecha_registro' => $fechaRegistro,
            ':turno' => $turno, ':cantidad' => $cantidad,
            ':id_cabra_2' => (int)$idCabra, ':fecha_validacion' => $fechaRegistro,
            ':fecha_validacion_2' => $fechaRegistro
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_control_produccion = :id");
        return $stmt->execute([':id' => (int)$id]);
    }
}
