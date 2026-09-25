/*
 * Agrega el peso al nacer al registro principal de la cabra.
 * El campo se mueve desde los controles sanitarios para que quede asociado al animal.
 */

ALTER TABLE `cabras`
  ADD COLUMN `peso_nacer_kg` DECIMAL(5,2) NULL AFTER `fecha_nacimiento`;
