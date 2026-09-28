DROP TEMPORARY TABLE IF EXISTS tmp_lactancia_backfill;

CREATE TEMPORARY TABLE tmp_lactancia_backfill AS
SELECT
    cpl.id_cabra,
    MIN(DATE(cpl.fecha_registro)) AS fecha_inicio,
    MAX(DATE(cpl.fecha_registro)) AS ultimo_registro,
    COALESCE((
        SELECT MAX(l.numero_lactancia) + 1
        FROM lactancias l
        WHERE l.id_cabra = cpl.id_cabra
    ), 1) AS numero_lactancia,
    COALESCE((
        SELECT UPPER(TRIM(REPLACE(cs.condicion_especial, 'í', 'i')))
        FROM controles_sanitarios cs
        WHERE cs.id_cabra = cpl.id_cabra
        ORDER BY cs.fecha_control DESC, cs.id_control DESC
        LIMIT 1
    ), '') AS condicion_actual,
    (
        SELECT DATE(cs.fecha_control)
        FROM controles_sanitarios cs
        WHERE cs.id_cabra = cpl.id_cabra
        ORDER BY cs.fecha_control DESC, cs.id_control DESC
        LIMIT 1
    ) AS fecha_condicion
FROM control_produccion_lechera cpl
WHERE cpl.id_lactancia IS NULL
GROUP BY cpl.id_cabra;

INSERT INTO lactancias (id_cabra, numero_lactancia, fecha_inicio, fecha_fin, estado)
SELECT
    id_cabra,
    numero_lactancia,
    fecha_inicio,
    CASE
        WHEN condicion_actual = 'VACIA' THEN COALESCE(fecha_condicion, ultimo_registro)
        ELSE NULL
    END,
    CASE WHEN condicion_actual = 'VACIA' THEN 'SECADA' ELSE 'EN LACTANCIA' END
FROM tmp_lactancia_backfill;

UPDATE control_produccion_lechera cpl
INNER JOIN tmp_lactancia_backfill b
    ON b.id_cabra = cpl.id_cabra
INNER JOIN lactancias l
    ON l.id_cabra = b.id_cabra
   AND l.numero_lactancia = b.numero_lactancia
   AND l.fecha_inicio = b.fecha_inicio
SET cpl.id_lactancia = l.id_lactancia
WHERE cpl.id_lactancia IS NULL;

DROP TEMPORARY TABLE tmp_lactancia_backfill;