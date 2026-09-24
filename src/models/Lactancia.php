<?php

class Lactancia
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll($idCabra = null)
    {
        $where = $idCabra ? 'WHERE l.id_cabra = :id_cabra' : '';
        $sql = "SELECT l.*, c.nombre AS nombre_cabra, p.fecha_parto,
                       COALESCE(SUM(cpl.cantidad_litros), 0) AS total_litros,
                       MAX(cpl.cantidad_litros) AS produccion_maxima,
                       MIN(DATE(cpl.fecha_registro)) AS fecha_primer_registro,
                       MAX(DATE(cpl.fecha_registro)) AS fecha_ultimo_registro,
                       MAX(CASE WHEN cpl.cantidad_litros = (
                           SELECT MAX(cpl2.cantidad_litros)
                           FROM control_produccion_lechera cpl2
                           WHERE cpl2.id_lactancia = l.id_lactancia
                       ) THEN cpl.fecha_registro END) AS fecha_produccion_maxima
                FROM lactancias l
                INNER JOIN cabras c ON c.id_cabra = l.id_cabra
                INNER JOIN partos p ON p.id_parto = l.id_parto
                LEFT JOIN control_produccion_lechera cpl ON cpl.id_lactancia = l.id_lactancia
                {$where}
                GROUP BY l.id_lactancia, c.nombre, p.fecha_parto
                ORDER BY l.fecha_inicio DESC";
        $stmt = $this->db->prepare($sql);
        if ($idCabra) {
            $stmt->bindValue(':id_cabra', (int)$idCabra, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function closeWhenSanitaryConditionIsEmpty()
    {
        $stmt = $this->db->query(
            "SELECT l.id_lactancia, l.fecha_inicio, DATE(cs.fecha_control) AS fecha_control
             FROM lactancias l
             INNER JOIN controles_sanitarios cs ON cs.id_cabra = l.id_cabra
             WHERE l.estado = 'EN LACTANCIA'
               AND DATE(cs.fecha_control) >= l.fecha_inicio
               AND cs.id_control = (
                   SELECT cs2.id_control
                   FROM controles_sanitarios cs2
                   WHERE cs2.id_cabra = l.id_cabra
                   ORDER BY cs2.fecha_control DESC, cs2.id_control DESC
                   LIMIT 1
               )
               AND LOWER(TRIM(REPLACE(cs.condicion_especial, 'í', 'i'))) = 'vacia'"
        );
        $lactancias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $update = $this->db->prepare(
            "UPDATE lactancias
             SET fecha_fin = :fecha_fin, estado = 'SECADA'
             WHERE id_lactancia = :id_lactancia
               AND estado = 'EN LACTANCIA'"
        );
        foreach ($lactancias as $lactancia) {
            $update->execute([
                ':fecha_fin' => $lactancia['fecha_control'],
                ':id_lactancia' => (int)$lactancia['id_lactancia']
            ]);
        }
        return count($lactancias);
    }

    public function closeForSanitaryStatus($idCabra, $fecha, $condicion)
    {
        $condicion = strtolower(trim(str_replace('í', 'i', (string)$condicion)));
        if ($condicion !== 'vacia') {
            return 0;
        }

        $stmt = $this->db->prepare(
            "UPDATE lactancias
             SET fecha_fin = :fecha_fin, estado = 'SECADA'
             WHERE id_cabra = :id_cabra
               AND estado = 'EN LACTANCIA'
               AND fecha_inicio <= :fecha_inicio
               AND (fecha_fin IS NULL OR fecha_fin >= :fecha_fin)"
        );
        $stmt->execute([
            ':fecha_fin' => $fecha,
            ':id_cabra' => (int)$idCabra,
            ':fecha_inicio' => $fecha,
        ]);
        return $stmt->rowCount();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT l.*, c.nombre AS nombre_cabra, p.fecha_parto
             FROM lactancias l
             INNER JOIN cabras c ON c.id_cabra = l.id_cabra
             INNER JOIN partos p ON p.id_parto = l.id_parto
             WHERE l.id_lactancia = :id"
        );
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOptionsByCabra($idCabra)
    {
        $stmt = $this->db->prepare(
            "SELECT id_lactancia, id_cabra, numero_lactancia, fecha_inicio, fecha_fin, estado
             FROM lactancias WHERE id_cabra = :id_cabra
             ORDER BY fecha_inicio DESC"
        );
        $stmt->execute([':id_cabra' => (int)$idCabra]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartosWithoutLactancia($idCabra = null)
    {
        $where = $idCabra ? 'AND p.id_madre = :id_cabra' : '';
        $stmt = $this->db->prepare(
            "SELECT p.id_parto, p.id_madre, p.fecha_parto, c.nombre AS nombre_cabra
             FROM partos p INNER JOIN cabras c ON c.id_cabra = p.id_madre
             LEFT JOIN lactancias l ON l.id_parto = p.id_parto
             WHERE l.id_lactancia IS NULL {$where}
             ORDER BY p.fecha_parto DESC"
        );
        if ($idCabra) {
            $stmt->bindValue(':id_cabra', (int)$idCabra, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($idCabra, $idParto)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO lactancias (id_cabra, id_parto, numero_lactancia, fecha_inicio)
             SELECT :id_cabra, p.id_parto,
                    COALESCE((
                        SELECT MAX(l2.numero_lactancia) + 1
                        FROM lactancias l2
                        WHERE l2.id_cabra = :id_cabra_3
                    ), 1),
                    DATE(p.fecha_parto)
             FROM partos p
             WHERE p.id_parto = :id_parto AND p.id_madre = :id_cabra_2
               AND NOT EXISTS (SELECT 1 FROM lactancias l WHERE l.id_parto = p.id_parto)"
        );
        return $stmt->execute([
            ':id_cabra' => (int)$idCabra,
            ':id_parto' => (int)$idParto,
            ':id_cabra_2' => (int)$idCabra,
            ':id_cabra_3' => (int)$idCabra
        ]);
    }

    public function getOrCreateActive($idCabra, $fecha)
    {
        $stmt = $this->db->prepare(
            "SELECT id_lactancia
             FROM lactancias
             WHERE id_cabra = :id_cabra AND estado = 'EN LACTANCIA'
             ORDER BY numero_lactancia DESC
             LIMIT 1"
        );
        $stmt->execute([':id_cabra' => (int)$idCabra]);
        $active = $stmt->fetchColumn();
        if ($active) {
            return (int)$active;
        }

        $stmt = $this->db->prepare(
            "SELECT p.id_parto
             FROM partos p
             LEFT JOIN lactancias l ON l.id_parto = p.id_parto
             WHERE p.id_madre = :id_cabra
               AND DATE(p.fecha_parto) <= :fecha
               AND l.id_lactancia IS NULL
             ORDER BY p.fecha_parto DESC, p.id_parto DESC
             LIMIT 1"
        );
        $stmt->execute([':id_cabra' => (int)$idCabra, ':fecha' => $fecha]);
        $idParto = $stmt->fetchColumn();
        if (!$idParto || !$this->create($idCabra, (int)$idParto)) {
            return null;
        }

        $stmt = $this->db->prepare(
            "SELECT id_lactancia
             FROM lactancias
             WHERE id_cabra = :id_cabra AND id_parto = :id_parto
             LIMIT 1"
        );
        $stmt->execute([
            ':id_cabra' => (int)$idCabra,
            ':id_parto' => (int)$idParto
        ]);
        $idLactancia = $stmt->fetchColumn();
        return $idLactancia ? (int)$idLactancia : null;
    }

    public function finish($id, $fechaFin)
    {
        $stmt = $this->db->prepare(
            "UPDATE lactancias SET fecha_fin = :fecha_fin, estado = 'SECADA'
             WHERE id_lactancia = :id AND fecha_fin IS NULL AND :fecha_fin >= fecha_inicio"
        );
        return $stmt->execute([':id' => (int)$id, ':fecha_fin' => $fechaFin]);
    }
}
