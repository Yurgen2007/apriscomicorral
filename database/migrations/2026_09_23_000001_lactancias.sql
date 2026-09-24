/*
 * Cambios manuales para numerar las lactancias por cabra.
 *
 * Requiere que la tabla lactancias ya exista.
 */

ALTER TABLE `lactancias`
  ADD COLUMN `numero_lactancia` INT NOT NULL;
