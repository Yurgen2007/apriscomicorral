SET @lactancia_parto_fk = (
    SELECT CONSTRAINT_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'lactancias'
      AND COLUMN_NAME = 'id_parto'
      AND REFERENCED_TABLE_NAME IS NOT NULL
    LIMIT 1
);

SET @drop_lactancia_parto_fk = IF(
    @lactancia_parto_fk IS NULL,
    'SELECT 1',
    CONCAT('ALTER TABLE `lactancias` DROP FOREIGN KEY `', @lactancia_parto_fk, '`')
);

PREPARE drop_lactancia_parto_fk_stmt FROM @drop_lactancia_parto_fk;
EXECUTE drop_lactancia_parto_fk_stmt;
DEALLOCATE PREPARE drop_lactancia_parto_fk_stmt;

ALTER TABLE `lactancias` DROP COLUMN `id_parto`;